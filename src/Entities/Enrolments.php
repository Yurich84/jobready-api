<?php

namespace Yurich84\JobReadyApi\Entities;

use Yurich84\JobReadyApi\EntityInterface;
use Yurich84\JobReadyApi\JobReadyService;

/**
 * Class Enrolments
 * @package Yurich84\JobReadyApi\Entities
 */
class Enrolments extends JobReadyService implements EntityInterface
{

    const ENTITY = 'enrolments';

    /*
    |--------------------------------------------------------------------------
    | Request Params
    |--------------------------------------------------------------------------
    */
    const PARAMETER_PARTY_IDENTIFIER             = 'party_identifier';
    const PARAMETER_RTO_IDENTIFIER               = 'rto_identifier';
    const PARAMETER_EMPLOYER_IDENTIFIER          = 'employer_identifier';

    const PARAMETER_UNIT_ID                      = 'unit_id';
    const PARAMETER_COURSE_NUMBER                = 'course_number';
    const PARAMETER_ENROLMENT_IDENTIFIER         = 'enrolment_identifier';




    /*
    |--------------------------------------------------------------------------
    | Response Fields
    |--------------------------------------------------------------------------
    */
    const FIELD_ENROLMENT_ID              = 'enrolment-identifier';
    const FIELD_PARTY_ID                  = 'party-identifier';
    const FIELD_COURSE_NUMBER             = 'course-number';


    public function entity() : string
    {
        return self::ENTITY;
    }


    /**
     * @param $id
     * @param int $limit
     * @return object
     */
    public function getAttendanceSummary($id, $limit = 10)
    {
        $response = $this->getResponse($this->entity . '/' . $id . '/event_attendance_summary', $limit);
        return (object) [
            'data' => collect($response['data']['role-client']),
            'total' => $response['total'],
            'uri' => $response['uri']
        ];
    }

}