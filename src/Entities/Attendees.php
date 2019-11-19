<?php

namespace Yurich84\JobReadyApi\Entities;

use Yurich84\JobReadyApi\EntityInterface;
use Yurich84\JobReadyApi\JobReadyService;

/**
 * Class Attendees
 * @package Yurich84\JobReadyApi\Entities
 */
class Attendees extends JobReadyService implements EntityInterface
{
    const ENTITY = 'attendees';

    /*
    |--------------------------------------------------------------------------
    | Request Parameters
    |--------------------------------------------------------------------------
    */
    const PARAMETER_EVENT_ID = 'event_id';
    const PARAMETER_ID = 'id';
    const PARAMETER_CREATED_SINCE = 'created_since';
    const PARAMETER_UPDATED_SINCE = 'updated_since';
    const PARAMETER_COURSE_NUMBER = 'course_number';
    const PARAMETER_PARTY_IDENTIFIER = 'party_identifier';

    /*
    |--------------------------------------------------------------------------
    | Response Fields
    |--------------------------------------------------------------------------
    */
    const FIELD_ID = 'id';
    const FIELD_PARTY_ID = 'party-identifier';
    const FIELD_ATTENDED = 'attended';
    const FIELD_ARRIVED_AT = 'arrived-at';
    const FIELD_LEFT_AT = 'left-at';
    const FIELD_DURATION = 'duration';

    const FIELD_START_DATE = 'notes';
    const FIELD_END_DATE = 'absence-reason';

    /**
     * @return string
     */
    public function entity(): string
    {
        return self::ENTITY;
    }

    /**
     * @param array $requestParameters
     * @return array
     * @throws \Exception
     */
    public function getAttendee(array $requestParameters)
    {
        return $this->getResponse($this->generateUri($requestParameters));
    }

    /**
     * @param $requestParameters
     * @return bool
     */
    public function exists($requestParameters): bool
    {
        try {
            $this->getAttendee($requestParameters);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param array $attendee
     * @param array $requestParameters
     * @return array
     * @throws \Exception
     */
    public function makeAttended(array $attendee, array $requestParameters)
    {
        $data = $attendee['data'];
        $data[self::FIELD_ATTENDED] = true;

        return $this->updateAttendee($requestParameters, $this->generatePayload($data));
    }

    /**
     * @param array $attendee
     * @param array $requestParameters
     * @return array
     * @throws \Exception
     */
    public function makeUnattended(array $attendee, array $requestParameters)
    {
        $data = $attendee['data'];
        $data[self::FIELD_ATTENDED] = false;

        return $this->updateAttendee($requestParameters, $this->generatePayload($data));
    }

    /**
     * @param array $requestParameters
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createAttendee(array $requestParameters, array $data = [])
    {
        if (empty($data)) {
            $data[self::FIELD_PARTY_ID] = $requestParameters[self::PARAMETER_PARTY_IDENTIFIER];
            $data[self::FIELD_ATTENDED] = false;
        }

        $uri = $this->generateCreatedUri($requestParameters);

        return $this->postResponse($uri, $this->generatePayload($data));
    }

    /**
     * @param array $requestParameters
     * @param array $payload
     * @return array
     * @throws \Exception
     */
    public function updateAttendee(array $requestParameters, array $payload)
    {
        $uri = $this->generateUri($requestParameters);

        return $this->postResponse($uri, $payload);
    }

    /**
     * @param array $requestParameters
     * @return array
     * @throws \Exception
     */
    public function removeAttendee(array $requestParameters)
    {
        $uri = $this->generateUri($requestParameters) . '/delete';
        $payload = ['attendee' => []];

        return $this->postResponse($uri, $payload);
    }


    /**
     * @param array $requestParameters
     * @return string
     */
    protected function generateCreatedUri(array $requestParameters)
    {
        $courseNumber = $requestParameters[self::PARAMETER_COURSE_NUMBER];
        $eventId = $requestParameters[self::PARAMETER_EVENT_ID];

        return "courses/{$courseNumber}/events/{$eventId}/" . self::ENTITY;
    }

    /**
     * @param array $requestParameters
     * @return string
     */
    protected function generateUri(array $requestParameters)
    {
        $partyId = $requestParameters[self::PARAMETER_PARTY_IDENTIFIER];

        return $this->generateCreatedUri($requestParameters) . "/{$partyId}";
    }

    /**
     * @param array $data
     * @return array
     */
    protected function generatePayload(array $data): array
    {
        return [
            'attendee' => [
                self::FIELD_PARTY_ID => $data[self::FIELD_PARTY_ID],
                self::FIELD_ATTENDED => $data[self::FIELD_ATTENDED]
            ]
        ];
    }
}