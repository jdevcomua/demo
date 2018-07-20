<?php

namespace App\Auth;

//use App\Auth\Contracts\HashInn;
//use App\Auth\Contracts\HasInn;
//use App\Conventions\UserProvidersConvention;
//use App\Model\BankIDProviderUserResponse;
use App\Services\Geocoding\Address;
use Carbon\Carbon;
use Illuminate\Encryption\Encrypter;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

abstract class BankIdProvider extends AbstractProvider implements ProviderInterface//, HasInn
{
//    use HashInn;

    const ADDRESS_TYPE_LIVING = 'living';
    const ADDRESS_TYPE_REGISTRATION = 'registration';
    const ADDRESS_TYPE_BIRTH = 'birth';
    const IDENTIFIER = '';
    /**
     * {@inheritdoc}
     */
    protected $scopes = [];

    protected $stateless = true;

    protected $user;

    protected $authUrl = '';
    protected $tokenUrl = '';
    protected $dataUrl = '';

    protected $userSchema = [
        "type" => "physical",
        'fields' => [
            'clId',
            'inn',
            'firstName',
            'middleName',
            'lastName',
            'phone',
            'birthDay',
            'sex',
            'email',
            'resident'
        ],
        'addresses' => [
            [
                "type" => "factual",
                "fields" => ["country", "state", "area", "city", "street", "houseNo", "flatNo", "dateModification"]
            ],
            [
                "type" => "birth",
                "fields" => ["country", "state", "area", "city", "street", "houseNo", "flatNo", "dateModification"]
            ]
        ],
        'documents' => [
            [
                "type" => "passport",
                "fields" => ["series", "number", "issue", "dateIssue", "dateExpiration", "issueCountryIso2", "dateModification"]
            ],
            [
                "type" => "ident",
                "fields" => ["series", "number", "issue", "dateIssue", "dateExpiration", "issueCountryIso2", "dateModification"]
            ]
        ],
        'scans' => [
            [
                "type" => "passport",
                "fields" => ["link", "dateCreate", "extension"]
            ]
        ],
        'signature'
    ];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            $this->authUrl, $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->tokenUrl;
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->post(
            $this->dataUrl,
            [
                'headers' => [
                    'Content-Type' => "application/json",
                    'Authorization' => "Bearer $token, Id {$this->clientId}",
                    'Accept' => "application/json"
                ],
                'json' => $this->userSchema
            ]
        );

        $r = $response->getBody()->getContents();
        $response = json_decode($r, true);
        if ($response['state'] !== 'ok') {
            throw new \Exception($response['desc']);
        }
        $data =  $this->decryptResponse($response['customer']);

//        BankIDProviderUserResponse::create([
//            'token' => $token,
//            'provider' => mb_convert_case(static::IDENTIFIER, MB_CASE_LOWER),
//            'response' => $r,
//            'decrypted_data' => $data,
//            'ip' => $this->request->ip()
//        ]);

        return $data;
    }

    protected function decryptResponse($data) {
        $rsa_enc = file_get_contents(resource_path('keys/rsa_key.pem.encrypted'));
        $encrypter = new Encrypter(env('RSA_DECRYPT_KEY'), 'AES-256-CBC');
        $rsa = $encrypter->decrypt($rsa_enc);
        array_walk_recursive($data, function (&$entry, $key) use ($rsa) {
            if (is_string($entry)) {
                $decrypted = '';
                $try = openssl_private_decrypt(base64_decode($entry), $decrypted, $rsa);
                if ($try) {
                    $entry = $decrypted;
                }
            }
        });
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function mapUserToObject(array $user)
    {
        $getType = function ($src, $type) use ($user) {
            return !empty($user[$src]) ? array_first(array_where($user[$src],
                function ($value, $key) use ($type) {
                    return $value['type'] === $type;
                })) : null;
        };

        $passport = $getType('documents', 'passport');

        $ident = $getType('documents', 'ident');

        $addressLiving = $getType('addresses', 'factual');
        $addressRegistration = $getType('addresses', 'juridical');
        $addressBirth = $getType('addresses', 'birth');

        $scan = $getType('scans', 'passport');

        $firstName = mb_convert_case(trim(array_get($user, 'firstName')), MB_CASE_TITLE);
        $lastName = mb_convert_case(trim(array_get($user, 'lastName')), MB_CASE_TITLE);
        $middleName = mb_convert_case(trim(array_get($user, 'middleName'), ''), MB_CASE_TITLE);

        $passportString = $passport && !empty($passport['series']) && !empty($passport['number']) // && check_passport($passport['series'] . $passport['number'])
            ? $passport['series'] . $passport['number']
            : null;

        if (!$passportString) {
            $passportString = $ident && !empty($ident['number']) ? $ident['number'] : null;
        }

        return (new User())->setRaw($user)->map([
//            'provider' => UserProvidersConvention::getNameByProvider(get_class($this)),
            'id' => array_get($user, 'clId'),
            'inn' => array_get($user, 'inn'),
            'name' => $firstName . " " . $middleName . " " . $lastName,
            'first_name' => $firstName ?: null,
            'surname' => $lastName ?: null,
            'patronymic' => $middleName ?: null,
            'email' => trim(mb_convert_case(array_get($user, 'email'), MB_CASE_LOWER)) ?: null,
            'phone' => array_get($user, 'phone') ?: null,
            'birth' => array_key_exists('birthDay', $user) ? Carbon::parse($user['birthDay']) : null,
            'gender' => array_key_exists('sex', $user) ? ($user['sex'] === 'F' ? 0 : 1) : null,
            'address_living' => $this->convertAddress($addressLiving),
            'address_registration' => ($addressRegistration || $addressBirth) ? $this->convertAddress($addressRegistration ?: $addressBirth) : null,
            'passport' => $passportString,
            'scan' => $scan ? $scan['link'] : null
        ]);
    }

    protected function convertAddress(array $addr = null) :? Address {
        if (!$addr) return null;

        $sourceKeys = ['flatNo', 'street', 'houseNo', 'area', 'city', 'state', 'country'];
        $sourceData = array_fill_keys($sourceKeys, null);
        $sourceData = array_merge($sourceData, array_only($addr, $sourceKeys));
        $sourceData['country'] = $sourceData['country'] ? mb_convert_case($sourceData['country'], MB_CASE_LOWER) : null;

        $sourceData['city'] = preg_replace(['/^[^a-zA-Zа-яА-ЯіїєІЇЄ0-9]+/u', '/[^a-zA-Zа-яА-ЯіїєІЇЄ0-9]+$/u'], ['', ''], $sourceData['city']);

        return new Address(array_combine(
            ['apartment', 'street', 'building', 'district', 'city', 'state', 'country_code'],
            $sourceData
        ));
    }

    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->get($this->getTokenUrl(), [
            'headers' => ['Accept' => 'application/json'],
            'query' => $this->getTokenFields($code),
        ]);

        $token = json_decode($response->getBody(), true);
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
            'client_secret' => sha1($this->clientId . $this->clientSecret . $code),
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
            $user = $this->mapUserToObject($this->getUserByToken(array_get($token, 'access_token')));
            $this->user = $user->setToken(array_get($token, 'access_token'));
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
     * @param User $user
     * @return $this
     */
    public function setUserData(array $userData) {
        $this->user = $this->mapUserToObject($userData);
        return $this;
    }

    /**
     * Returns unique user identifier
     * @return string
     */
    public function getId()
    {
        return md5(sha1($this->user()->inn));
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
        $this->basicValidation();

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
        $this->basicValidation();

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

    public function __toString()
    {
        return strtolower(static::IDENTIFIER);
    }


}