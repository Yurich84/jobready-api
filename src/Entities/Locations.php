<?php

namespace Yurich84\JobReadyApi\Entities;

use Yurich84\JobReadyApi\JobReadyService;
use Yurich84\JobReadyApi\EntityInterface;

/**
 * Class Locations
 * @package Yurich84\JobReadyApi\Entities
 */
class Locations extends JobReadyService implements EntityInterface
{
    const ENTITY = 'location';

    const INDEX_OTHER = 'zzz_other';

    /*
    |--------------------------------------------------------------------------
    | Response Fields
    |--------------------------------------------------------------------------
    */
    const FIELD_ID = 'id';
    const FIELD_NAME = 'name';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_ADDRESS = 'address';
    const FIELD_CONTACT_NUMBER = 'contact-number';
    const FIELD_ENABLED = 'enabled';
    const FIELD_INFORMATION = 'information';

    /**
     * @return string
     */
    public function entity() : string
    {
        return self::ENTITY;
    }

    public $perPage = 100;
}