<?php

namespace App\Auth;

//use App\Auth\Contracts\HasInn;
//use App\Conventions\UserProvidersConvention;
use App\Services\Geocoding\Address;
//use App\Utils\FlowLogger;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class KyivIdProvider extends BankIdProvider implements ProviderInterface
{
    const IDENTIFIER = 'KYIV_ID';

    const ADDRESS_TYPE_LIVING = 'FACTUAL';
    const ADDRESS_TYPE_REGISTRATION = 'REGISTRATION';

    protected $user;

    protected $authUrl = '/authorize';

    protected $tokenUrl = '/token';

    protected $dataUrl = '/profile/query/api/v1/query';

    protected $scopes = [];

    protected $host;

    protected $hostApi;

    public function __construct(Request $request, $clientId, $clientSecret, $redirectUrl)
    {
        parent::__construct($request, $clientId, $clientSecret, $redirectUrl);

        $this->host = env('KYIV_ID_HOST');
        $this->hostApi = env('KYIV_ID_HOST_API');
    }

    public static function getLoginUrl()
    {
        return env('KYIV_ID_HOST') .
            env('KYIV_ID_FORCE_LOGIN_URI') .
            '?callback=' .
            url('/') .
            '/auth/kyiv_id/attempt' .
            '&provider=nbubankid' .
            '&provider=eds' .
            '&provider=pbbankid';
    }

    public static function getLogoutUrl()
    {
        return env('KYIV_ID_HOST') .
            env('KYIV_ID_LOGOUT_URI') .
            '?callback=' .
            url('/');
    }

    public function redirect()
    {
        $state = null;

        if ($this->usesState()) {
            $this->request->session()->put('state', $state = $this->getState());
        }

        return new RedirectResponse($this->getAuthUrl($state));
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            $this->host . $this->authUrl, $state
        );
    }

    protected function getCodeFields($state = null)
    {
        $fields = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
//            'callback' => $this->redirectUrl,
            'response_type' => 'code',
            'scope' => '' .
                'openid+' .
                'profile.documents+' .
                'profile.auxiliary+' .
                'profile.addresses+' .
                'profile.basic+' .
                'profile.accounts+' .
                'profile.phones+' .
                'profile.emails+' .
                'profile.passport+' .
                'profile.itin'
        ];

        if ($this->usesState()) {
            $fields['state'] = $state;
        }

        return array_merge($fields, $this->parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => strpos($this->redirectUrl, 'http') === 0 ? $this->redirectUrl : route($this->redirectUrl)
        ];
    }


    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return $this->host . $this->tokenUrl;
    }

    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
            ],
            'form_params' => $this->getTokenFields($code)
        ]);

        $token = json_decode($response->getBody(), true);
        session(['kyiv_id_access_token' => $token['access_token']]);
        return $token;
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param  string $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        list($header, $payload, $signature) = explode (".", $token);
        $id = json_decode(base64_decode($payload), true)['sub'];
        $response = $this->getHttpClient()->post($this->hostApi . $this->dataUrl,[
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer  ' . $token,
            ],
            'body' => '{ 
                    "query":"{profile(id: '.$id.') {id name {lastName firstName middleName shortName verified} gender {gender verified} passportInternal {type firstName middleName lastName series number birthday issueDate issuedBy issueId expiryDate verified} itin {itin verified} birthday {date verified} documents {type firstName middleName lastName series number birthday issueDate issuedBy issueId expiryDate verified} addresses {id type zipCode country area district settlementName street house frame flat verified} accounts {authProvider providerProfileId displayName imageUrl profileUrl verified} phones {phoneNumber confirmed type verified} emails {email confirmed type verified}}}"
                }'
        ]);

        $responseBody = $response->getBody()->getContents();
        $response = json_decode($responseBody, true);

//        FlowLogger::put(UserProvidersConvention::AUTH_KYIV_ID,[FlowLogger::KEY_KYIV_ID_RESPONSE => $response]);

        return $response;
    }

    protected function convertAddress(array $addr = null) :? Address {
        if (!$addr) return null;

        $sourceKeys = ['flat', 'street', 'house', 'area', 'settlementName', 'district', 'country'];
        $sourceData = array_fill_keys($sourceKeys, null);
        $sourceData = array_merge($sourceData, array_only($addr, $sourceKeys));
        $sourceData['country'] = $sourceData['country'] ? mb_convert_case($sourceData['country'], MB_CASE_LOWER) : null;

        $sourceData['settlementName'] = preg_replace(['/^[^a-zA-Zа-яА-ЯіїєІЇЄ0-9]+/u', '/[^a-zA-Zа-яА-ЯіїєІЇЄ0-9]+$/u'], ['', ''], $sourceData['settlementName']);

        return new Address(array_combine(
            ['apartment', 'street', 'building', 'district', 'city', 'state', 'country_code'],
            $sourceData
        ));
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array $user
     * @return \Laravel\Socialite\Two\User
     */
    public function mapUserToObject(array $user)
    {
        $debugInfo = $user;
        $debugInfo['data']['profile']['passportInternal']['series'] = strlen(array_get($debugInfo, 'data.profile.passportInternal.series'));
        $debugInfo['data']['profile']['passportInternal']['number'] = strlen(array_get($debugInfo, 'data.profile.passportInternal.number'));
        \debugbar()->addMessage($debugInfo, 'kyivId response');

        $data = [
            'id' => array_get($user, 'data.profile.id'),
            'inn' => array_get($user, 'data.profile.itin.itin'),
            'first_name' => array_get($user, 'data.profile.name.firstName'),
            'surname' => array_get($user, 'data.profile.name.lastName'),
            'patronymic' => array_get($user, 'data.profile.name.middleName'),
            'phone' => array_get($user, 'data.profile.phones.0.phoneNumber') ,
            'birth' => array_get($user, 'data.profile.birthday.date') ? Carbon::createFromFormat('Y-m-d', array_get($user, 'data.profile.birthday.date')) : null,
            'email' => trim(strtolower(array_get($user, 'data.profile.emails.0.email'))) ?: null,
            'passport' => trim(strtoupper(array_get($user, 'data.profile.passportInternal.series').
                array_get($user, 'data.profile.passportInternal.number'))) ?: null,
            'cities' => [],
            'city' => null,
            'address_living' => null,
            'address_registration' => null,
            'gender' => array_get($user, 'data.profile.gender.gender') ? array_get($user, 'data.profile.gender.gender') === 'MALE' ? 1 : 0 : null,
        ];

        $addresses = array_get($user, 'data.profile.addresses', []);
        if ($addresses) {
            $addressLiving = $this->getByType($addresses, self::ADDRESS_TYPE_LIVING);
            $addressRegistration = $this->getByType($addresses, self::ADDRESS_TYPE_REGISTRATION);

            $data['address_living'] = $this->convertAddress($addressLiving);
            $data['address_registration'] = $this->convertAddress($addressRegistration);
            if ($data['address_living'] && !$data['address_registration']) {
                $data['address_registration'] = $data['address_living'];
            }
        }

//        FlowLogger::put(UserProvidersConvention::AUTH_KYIV_ID, [FlowLogger::KEY_MAP_USER_TO_OBJECT => $data]);
        return (new User())->map($data);
    }

    function getByType(array $data, string $type)
    {
        return collect($data)->first(function($item) use ($type) {
            return $item['type'] === $type;
        });
    }

    function getId()
    {
        return md5($this->user()->id);
    }

    /**
     * {@inheritdoc}
     */
    public function user()
    {
        if (!$this->user) {
            if ($this->hasInvalidState()) {
                throw new InvalidStateException();
            }
            $token = $this->getAccessTokenResponse($this->getCode());
            $user = $this->mapUserToObject($this->getUserByToken(array_get($token, 'access_token')));
            $this->user = $user->setToken(array_get($token, 'access_token'));
        }

        return $this->user;
    }

    public function getUserAttributes()
    {
//        $this->basicValidation(); //BankIdUserResolver::checkMinimumDataReqs()
        return [
            'gender' => $this->user()->gender,
            'name' => $this->user()->first_name,
            'surname' => $this->user()->surname,
            'patronymic' => $this->user()->patronymic,
            'email' => $this->user()->email,
            'phone' => $this->user()->phone,
            'birth' => $this->user()->birth && !$this->user()->birth->isToday() ? $this->user()->birth->format("Y-m-d") : null,
            'passport' => $this->user()->passport,
            'address_living' => $this->user()->address_living,
            'address_registration' => $this->user()->address_registration,
            'address_living_apartment' => $this->user()->address_living ? $this->user()->address_living->getApartment() : null,
            'address_registration_apartment' => $this->user()->address_registration ? $this->user()->address_registration->getApartment() : null
        ];
    }

    public function getExternalAttributes()
    {
//        $this->basicValidation(); //BankIdUserResolver::checkMinimumDataReqs()

        return [
            'gender' => $this->user()->gender,
            'first_name' => $this->user()->first_name,
            'surname' => $this->user()->surname,
            'patronymic' => $this->user()->patronymic,
            'email' => $this->user()->email,
            'phone' => $this->user()->phone,
            'birth' => $this->user()->birth && !$this->user()->birth->isToday() ? $this->user()->birth->format("Y-m-d") : null,
            'passport' => $this->user()->passport,
            'external_id' => $this->getId(),
//            'inn_hash' => $this instanceof HasInn ? $this->hashInn() : null,
//            'provider' => UserProvidersConvention::getNameByProvider(get_class($this)),
            'address_living' => $this->user()->address_living,
            'address_registration' => $this->user()->address_registration
        ];
    }

    public static function getAttributesForUpdate()
    {
        return [
            "gender",
            "email",
            "phone",
            "address_living",
            "address_registration",
            "address_living_apartment",
            "address_registration_apartment"
        ];
    }
}