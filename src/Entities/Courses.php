<?php

namespace Yurich84\JobReadyApi\Entities;

use Yurich84\JobReadyApi\EntityInterface;
use Yurich84\JobReadyApi\JobReadyService;


/**
 * Class Courses
 * @package Yurich84\JobReadyApi\Entities
 */
class Courses extends JobReadyService implements EntityInterface
{

    const ENTITY = 'courses';
    const INDEX_UNDEFINED = 'zzz_no_course';

    /*
    |--------------------------------------------------------------------------
    | Request PARAMETERS
    |--------------------------------------------------------------------------
    */
    const PARAMETER_NUMBER           = 'course_number';
    const PARAMETER_SCOPE_CODE       = 'course_scope_code';
    const PARAMETER_STATUS           = 'course_status';
    const PARAMETER_TYPE             = 'course_type';
    const PARAMETER_ONLINE           = 'online'; // Boolean
    const PARAMETER_START_DATE_FROM  = 'start_date_from'; // 2017-11-20
    const PARAMETER_START_DATE_TO    = 'start_date_to';
    const PARAMETER_END_DATE_FROM    = 'end_date_from';
    const PARAMETER_END_DATE_TO      = 'end_date_to';


    /*
    |--------------------------------------------------------------------------
    | Response Fields
    |--------------------------------------------------------------------------
    */
    const FIELD_ID = 'id';
    const FIELD_NUMBER = 'course-number';
    const FIELD_NAME = 'course-name';
    const FIELD_RTO_NAME = 'rto-name';
    const FIELD_TAGS = 'tags';
    const FIELD_STATUS = 'course-status';
    const FIELD_LOCATION = 'location';

    const FIELD_START_DATE = 'start-date';
    const FIELD_END_DATE = 'end-date';

    const FIELD_TRAINER = 'trainer';
    const FIELD_COURSE_TYPE = 'course-type';
    const FIELD_COMPLETE = 'complete';


    public function entity() : string
    {
        return self::ENTITY;
    }

}