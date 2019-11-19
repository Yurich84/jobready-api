<?php

namespace Yurich84\JobReadyApi\Entities;

use Yurich84\JobReadyApi\EntityInterface;
use Yurich84\JobReadyApi\JobReadyService;

/**
 * Class ContactDetails
 * @package Yurich84\JobReadyApi\Entities
 */
class ContactDetails extends JobReadyService implements EntityInterface
{
    const ENTITY = 'contact_details';

    const CONTACT_TYPE_EMAIL = 'Email';
    const CONTACT_TYPE_MOBILE = 'Mobile';

    /*
   |--------------------------------------------------------------------------
   | Request Parameters
   |--------------------------------------------------------------------------
   */
    const PARAMETER_PARTY_IDENTIFIER = 'party_identifier';

    /*
    |--------------------------------------------------------------------------
    | Response Fields
    |--------------------------------PAR------------------------------------------
    */
    const FIELD_PARTY_PRIMARY = 'primary'; //boolean
    const FIELD_VALUE = 'value';
    const FIELD_CONTACT_TYPE = 'contact-type';
    const FIELD_CONTACT_LOCATION = 'location';

    /**
     * @return string
     */
    public function entity(): string
    {
        return self::ENTITY;
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public function createContacts(array $data)
    {
        $partyIdentifier = $data['party-identifier'];

        $this->postResponse("parties/{$partyIdentifier}/contact_detail", $this->getPayload($data, self::CONTACT_TYPE_EMAIL));
        $this->postResponse("parties/{$partyIdentifier}/contact_detail", $this->getPayload($data, self::CONTACT_TYPE_MOBILE));
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public function updateContacts(array $data)
    {
        $partyIdentifier = $data['party-identifier'];
        $mobileId = $data['mobile_id'];
        $emailId = $data['email_id'];

        $this->postResponse("parties/{$partyIdentifier}/contact_detail/{$emailId}", $this->getPayload($data, self::CONTACT_TYPE_EMAIL));
        $this->postResponse("parties/{$partyIdentifier}/contact_detail/{$mobileId}", $this->getPayload($data, self::CONTACT_TYPE_MOBILE));
    }

    /**
     * @param array $data
     * @param string $type
     * @return array
     */
    protected function getPayload(array $data, string $type)
    {
        return [
            'contact-detail' => [
                self::FIELD_PARTY_PRIMARY => true,
                self::FIELD_VALUE => $data[strtolower($type)],
                self::FIELD_CONTACT_TYPE => $type,
                self::FIELD_CONTACT_LOCATION => 'Home'
            ],
        ];
    }
}