<?php

return [

    'key' => env('JOBREADY_KEY'),
    'user' => env('JOBREADY_USER'),

    'header' => [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json'
    ],

    'base_url' => env('JOBREADY_URL')
];