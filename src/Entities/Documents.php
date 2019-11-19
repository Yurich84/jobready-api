<?php

namespace Yurich84\JobReadyApi\Entities;

use Yurich84\JobReadyApi\EntityInterface;
use Yurich84\JobReadyApi\JobReadyService;

/**
 * Class Documents
 * @package Yurich84\JobReadyApi\Entities
 */
class Documents extends JobReadyService implements EntityInterface
{
    const ENTITY = 'documents';

    /*
   |--------------------------------------------------------------------------
   | Request Parameters
   |--------------------------------------------------------------------------
   */
    const PARAMETER_PARTY_IDENTIFIER = 'documents';


    /**
     * @return string
     */
    public function entity(): string
    {
        return self::ENTITY;
    }

    public function createSignatureDocument($party_identifier, $url)
    {
        $params = [
                'document' => [
                    'name' => $party_identifier . 'signature',
                    'document_category' => 'General',
                    'description' => 'Signature',
                    'document_type' => 'Multimedia',
                    'url' => $url
                ]];

        $this->postResponse("parties/{$party_identifier}/documents/", $params);
    }

}