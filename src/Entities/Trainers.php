<?php

namespace Yurich84\JobReadyApi\Entities;

use Yurich84\JobReadyApi\EntityInterface;
use Yurich84\JobReadyApi\JobReadyService;

/**
 * Class Trainer
 * @package Yurich84\JobReadyApi\Entities
 */
class Trainers extends JobReadyService implements EntityInterface
{

    const ENTITY = 'trainers';

    /*
    |--------------------------------------------------------------------------
    | Request PARAMETERS
    |--------------------------------------------------------------------------
    */
    const PARAMETER_ID          = 'id';
    const PARAMETER_PARTY_ID    = 'party_identifier';
    const PARAMETER_INTERNAL_ID = 'internal_identifier';


    /*
    |--------------------------------------------------------------------------
    | Response Fields
    |--------------------------------------------------------------------------
    */
    const FIELD_ID                  = 'id';
    const FIELD_PARTY_ID            = 'party_identifier';
    const FIELD_ENABLED             = 'enabled';
    const FIELD_INTERNAL_ID         = 'internal_identifier';
    const FIELD_PROFILE             = 'profile';
    const FIELD_EMPLOYMENT_BASIS    = 'employment-basis';
    const FIELD_IND_ASSESSOR        = 'ind-assessor';
    const FIELD_IND_COORDINATOR     = 'ind-coordinator';
    const FIELD_IND_TRAINER         = 'ind-trainer';


    public function entity() : string
    {
        return self::ENTITY;
    }

}