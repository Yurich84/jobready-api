<?php

namespace Yurich84\JobReadyApi\Entities;

use Yurich84\JobReadyApi\JobReadyService;
use Yurich84\JobReadyApi\EntityInterface;

/**
 * Class Invitees
 * @package Yurich84\JobReadyApi\Entities
 */
class Invitees extends JobReadyService implements EntityInterface
{
    const ENTITY = 'invitees';

    /*
    |--------------------------------------------------------------------------
    | Request Parameters
    |--------------------------------------------------------------------------
    */
    const PARAMETER_EVENT_ID = 'event_id';
    const PARAMETER_ID = 'id';
    const PARAMETER_COURSE_NUMBER = 'course_number';

    /*
    |--------------------------------------------------------------------------
    | Response Fields
    |--------------------------------------------------------------------------
    */
    const FIELD_PARTY_IDENTIFIER = 'party-identifier';
    const FIELD_EXCLUSION = 'exclusion';

    /**
     * @return string
     */
    public function entity(): string
    {
        return self::ENTITY;
    }

    /**
     * @param string $partyId
     * @param string $courseCode
     * @param string $eventId
     * @return array
     * @throws \Exception
     */
    public function getInvitee(string $partyId, string $courseCode, string $eventId): array
    {
        return $this->getResponse("courses/{$courseCode}/events/{$eventId}/invitees/$partyId");
    }

    /**
     * @param string $partyId
     * @param string $courseCode
     * @param string $eventId
     * @return array
     * @throws \Exception
     */
    public function createInvitee(string $partyId, string $courseCode, string $eventId)
    {
        $payload = $this->generatePayload($partyId);

        return $this->postResponse('courses/events/invitees', $payload);
    }

    /**
     * @param string $partyId
     * @param string $courseCode
     * @param string $eventId
     * @return array
     * @throws \Exception
     */
    public function updateInvitee(string $partyId, string $courseCode, string $eventId)
    {
        $payload = $this->generatePayload($partyId);

        return $this->postResponse("courses/{$courseCode}/events/{$eventId}/invitees/{$partyId}", $payload);
    }

    /**
     * @param $partyId
     * @return array
     */
    protected function generatePayload(string $partyId): array
    {
        return [
            'invitee' => [
                Invitees::FIELD_PARTY_IDENTIFIER => $partyId,
                Invitees::FIELD_EXCLUSION => true
            ]
        ];
    }
}