<?php

namespace App\Auth;

//use App\Auth\Contracts\HashInn;
//use App\Auth\Contracts\HasInn;
//use App\Conventions\UserProvidersConvention;
use App\Services\Geocoding\Address;
use Carbon\Carbon;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class KyivIdProvider extends AbstractProvider implements ProviderInterface//, HasInn
{
//    use HashInn;

//    protected $stateless = true;

    const IDENTIFIER = 'KYIV_ID';

    const ADDRESS_TYPE_BIRTH = 'birth';
    const ADDRESS_TYPE_LIVING = 'FACTUAL';
    const ADDRESS_TYPE_REGISTRATION = 'REGISTRATION';

    protected $user;

    protected $authUrl = '/authorize';

    protected $tokenUrl = '/token';

    protected $dataUrl = '/api/v1/query';

    protected $scopes = [];

    protected $host;

    protected $hostApi;

    public function __construct(Request $request, $clientId, $clientSecret, $redirectUrl)
    {
        parent::__construct($request, $clientId, $clientSecret, $redirectUrl);

        $this->host = config('services.kyivID.host');
        $this->hostApi = config('services.kyivID.host_api');
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

    public static function getLogoutUrl()
    {
        return config('services.kyivID.host') .
            config('services.kyivID.logout') .
            '?callback=' .
            url('/');
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

    /**
     * Get the raw user for the given access token.
     *
     * @param  string $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        [,$payload,] = explode (".", $token);
        $id = json_decode(base64_decode($payload), true)['sub'];

        $response = $this->getHttpClient()->post($this->hostApi . $this->dataUrl, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
            'body' => '{ 
                    "query":"{ profile(id: ' . $id . ') { '.
                            'id '.
                            'name {lastName firstName middleName shortName} '.
                            'gender {gender} '.
                            'passportInternal {type firstName middleName lastName series number birthday issueDate issuedBy issueId expiryDate} '.
                            'itin {itin} '.
                            'birthday {date} '.
                            'addresses {id type zipCode country area district settlementName street house frame flat} ' .
                            'phones {phoneNumber confirmed type } '.
                            'emails {email confirmed type} }}"
                }'
        ]);

        $responseBody = $response->getBody()->getContents();

        return json_decode($responseBody, true);
    }

    protected function getCodeFields($state = null)
    {
        $fields = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'response_type' => 'code',
            'scope' => 'portal.user.profile.read',
        ];

        if ($this->usesState()) {
            $fields['state'] = $state;
        }

        return array_merge($fields, $this->parameters);
    }

    protected function convertAddress(array $addr = null) :? Address {
        if (!$addr) return null;

        $addrChanged = $this->convertToOldAddress($addr);

        $sourceKeys = ['flat', 'street', 'house', 'area', 'settlementName', 'district', 'country'];
        $sourceData = array_fill_keys($sourceKeys, null);
        $sourceData = array_merge($sourceData, $addrChanged);
        $sourceData['country'] = $sourceData['country'] ? mb_convert_case($sourceData['country'], MB_CASE_LOWER) : null;

        $sourceData['settlementName'] = preg_replace(['/^[^a-zA-Zа-яА-ЯіїєІЇЄ0-9]+/u', '/[^a-zA-Zа-яА-ЯіїєІЇЄ0-9]+$/u'], ['', ''], $sourceData['settlementName']);

        return new Address(array_combine(
            ['apartment', 'street', 'building', 'district', 'city', 'state', 'country_code'],
            $sourceData
        ));
    }

    protected function convertToOldAddress($addr)
    {
        $addrChanged['flat'] = $addr['flat'];
        $addrChanged['street'] = $addr['street'];
        $addrChanged['house'] = $addr['house'];
        $addrChanged['area'] = $addr['region'];
        $addrChanged['settlementName'] = $addr['locality'];
        $addrChanged['district'] = $addr['subregion'];
        $addrChanged['country'] = $addr['country'];

        return $addrChanged;
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array $user
     * @return User
     */
    public function mapUserToObject(array $user)
    {
        \RhaLogger::addPayload(['KiyvID response' => $user]);

        $birthday = array_get($user, 'data.profile.birthday.date');

        $data = [
            'ext_id' => array_get($user, 'data.profile.id'),
            'first_name' => array_get($user, 'data.profile.name.firstName'),
            'last_name' => array_get($user, 'data.profile.name.lastName'),
            'middle_name' => array_get($user, 'data.profile.name.middleName'),
            'emails' => array_get($user, 'data.profile.emails'),
            'phones' => array_get($user, 'data.profile.phones') ,
            'birthday' => $birthday ?
                Carbon::parse($birthday)->format('Y-m-d') : null,
            'inn' => array_get($user, 'data.profile.itin.itin'),
            'passport' => trim(strtoupper(array_get($user, 'data.profile.passportInternal.series').
                array_get($user, 'data.profile.passportInternal.number'))) ?: null,
            'cities' => [],
            'city' => null,
            'address_living' => null,
            'address_registration' => null,
            'gender' => null,
        ];

        switch (array_get($user, 'data.profile.gender.gender')) {
            case 'MALE':
                $data['gender'] = \App\User::GENDER_MALE;
                break;
            case 'FEMALE':
                $data['gender'] = \App\User::GENDER_FEMALE;
                break;
            default:
                $data['gender'] = rand(0, 1) ? \App\User::GENDER_MALE : \App\User::GENDER_FEMALE;
        }

        $addresses = array_get($user, 'data.profile.addresses', []);
        if ($addresses) {
            $addressLiving = $this->getByType($addresses, self::ADDRESS_TYPE_LIVING);
            $addressRegistration = $this->getByType($addresses, self::ADDRESS_TYPE_REGISTRATION);

            $data['address_living'] = $this->convertAddress($addressLiving);
            $data['address_registration'] = $this->convertAddress($addressRegistration);
            if ($data['address_living'] && !$data['address_registration']) {
                $data['address_registration'] = $data['address_living'];
            }

            if ($data['address_registration'] && !$data['address_living']) {
                $data['address_living'] = $data['address_registration'];
            }
        }

        return (new User())->map($data);
    }

    function getByType(array $data, string $type)
    {
        return collect($data)->first(function($item) use ($type) {
            return $item['type'] === $type;
        });
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
     * {@inheritdoc}
     */
    public function user()
    {
        if (!$this->user) {
            if ($this->hasInvalidState()) {
                throw new InvalidStateException();
            }
            $token = $this->getAccessTokenResponse($this->getCode());
            $this->user = $this->mapUserToObject($this->getUserByToken(array_get($token, 'access_token')));
//            $this->user = $user->setToken(array_get($token, 'access_token'));
        }

        return $this->user;
    }

    /**
     * Set user from external source. Used for user provider api auth protocol
     *
     * @param User $user
     * @return $this
     */
    public function setUser(User $user) {
        $this->user = $user;
        return $this;
    }

    /**
     * Set user from external source with raw data. Used for user provider api auth protocol
     *
     * @param array $userData
     * @return $this
     */
    public function setUserData(array $userData) {
        $this->user = $this->mapUserToObject($userData);
        return $this;
    }

    function getId()
    {
        return md5($this->user()->id);
    }

    public function basicValidation() {
        $data = (array) $this->user();
        $rules = [
            'inn' => ['inn'],
            'passport' => ['passport'],
        ];
        $messages = [
            'inn.required' => trans('auth.external_user_no_inn'),
            'inn.inn' => trans('auth.external_user_invalid_inn'),
            'passport.required' => trans('auth.external_user_cant_no_passport'),
            'passport.passport' => trans('auth.external_user_invalid_passport'),
        ];

        $validator = \Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new \UnexpectedValueException( implode('; ', $validator->errors()->all()));
        }
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

    public function __toString()
    {
        return strtolower(static::IDENTIFIER);
    }


}
