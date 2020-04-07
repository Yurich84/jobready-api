<?php

namespace Yurich84\JobReadyApi\Entities;

use Yurich84\JobReadyApi\EntityInterface;
use Yurich84\JobReadyApi\JobReadyService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class Parties
 * @package Yurich84\JobReadyApi\Entities
 */
class Parties extends JobReadyService implements EntityInterface
{

    const ENTITY = 'parties';

    const PARTY_TYPE_PERSON = 'Person';
    const PARTY_TYPE_TRAINER = 'Trainer';
    const PARTY_TYPE_EMPLOYER = 'Employer';

    /*
    |--------------------------------------------------------------------------
    | Request Parameters
    |--------------------------------------------------------------------------
    */
    const PARAMETER_TYPE = 'party_type';
    const PARAMETER_EMAIL = 'email';
    const PARAMETER_FIRST_NAME = 'first_name';

    const PARAMETER_LOGIN = 'login';
    const PARAMETER_PASSWORD = 'password';


    /*
    |--------------------------------------------------------------------------
    | Response Fields
    |--------------------------------------------------------------------------
    */
    const FIELD_ID = 'party-identifier';
    const FIELD_TYPE = 'party-type';
    const FIELD_CONTACT_METHOD = 'contact-method';
    const FIELD_CREATED_SINCE = 'created_since';
    const FIELD_FIRST_NAME = 'first-name';
    const FIELD_SURNAME = 'surname';
    const FIELD_GENDER = 'gender';
    const FIELD_BIRTH_DATE = 'birth-date';
    const FIELD_LOGIN = 'login';
    const FIELD_PASSWORD = 'password';
    const FIELD_PASSWORD_TEMPORARY = 'password-temporary';
    const FIELD_LOGON_ENABLED = 'logon-enabled';
    const FIELD_USI_NUMBER = 'usi-number';
    const FIELD_VALID_PASSWORD = 'valid_password';

    /**
     * @return array
     */
    public static function rules()
    {
        return [
            'first-name' => 'required',
            'party-identifier' => 'required',
            'email' => 'required',
            'mobile' => 'required',
        ];
    }

    /**
     * @return string
     */
    public function entity(): string
    {
        return self::ENTITY;
    }


    /**
     * @param $login
     * @return mixed
     */
    public function findByLogin($login)
    {
        $response = $this->where(self::PARAMETER_LOGIN, $login)->get(1);
        return $response->data[0];
    }


    /**
     * @param null $limit
     * @return object
     * @throws \Exception
     */
    public function getPersonList($limit = null)
    {
        $limit = $limit ?: $this->perPage;
        $response = $this->where(self::PARAMETER_TYPE, 'Person')
            ->getResponse($this->entity, $limit);
        return (object)[
            'data' => collect($response['data'][Str::singular($this->entity)]),
            'total' => $response['total']
        ];
    }

    /**
     * @param Collection $partyCollection
     * @return Collection
     */
    public function getLatestPersonContacts(Collection $partyCollection): Collection
    {
        if (empty($partyCollection['contact-details'])) {
            return $this->generatePersonEmptyContacts($partyCollection);
        }

        $contactDetails = collect($partyCollection['contact-details']['contact-detail']);

        $mobile = $contactDetails
            ->sortByDesc('id')
            ->where(ContactDetails::FIELD_CONTACT_TYPE, ContactDetails::CONTACT_TYPE_MOBILE)->first();

        $partyCollection['mobile_id'] = $mobile['id'];
        $partyCollection['mobile'] = $mobile['value'];

        $email = $contactDetails
            ->sortByDesc('id')
            ->where(ContactDetails::FIELD_CONTACT_TYPE, ContactDetails::CONTACT_TYPE_EMAIL)->first();

        $partyCollection['email_id'] = $email['id'];
        $partyCollection['email'] = $email['value'];

        return $partyCollection;
    }

    /**
     * @param $login
     * @param $password
     * @return mixed
     * @throws \Exception
     */
    public function auth($login, $password)
    {
        $payload = [
            'party' => [
                self::PARAMETER_LOGIN => $login,
                self::PARAMETER_PASSWORD => $password,
            ]
        ];

        $response = $this->postResponse('party_authentication', $payload);
        return $response['data'];
    }

    /**
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function createPerson(array $data)
    {
        $payload = $this->generatePayload($data);

        return $this->postResponse('parties', $payload);
    }

    /**
     * @param string $partyId
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updatePerson(string $partyId, array $data)
    {
        $payload = $this->generatePayload($data);

        return $this->postResponse("parties/{$partyId}", $payload);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function generatePayload(array $data): array
    {
        //TODO: array_merge defaults against data, use models?
        $party = [
            self::FIELD_FIRST_NAME => $data['first-name'],
            self::FIELD_SURNAME => $data['surname'],
            self::FIELD_TYPE => self::PARTY_TYPE_PERSON,
            self::FIELD_CONTACT_METHOD => 'Email',
            self::FIELD_GENDER => $data['gender'],
            self::FIELD_BIRTH_DATE => '1993-01-01'
        ];
        if (array_key_exists(self::FIELD_ID, $data)) {
            $party[self::FIELD_ID] = $data[self::FIELD_ID];
        }
        return [
            'party' => $party
        ];
    }

    protected function generatePersonEmptyContacts(Collection &$partyCollection): Collection
    {
        $partyCollection['mobile_id'] = null;
        $partyCollection['mobile'] = null;
        $partyCollection['email_id'] = null;
        $partyCollection['email'] = null;

        return $partyCollection;
    }
}