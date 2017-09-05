<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Invite Table Name
    |--------------------------------------------------------------------------
    |
    */
    'invite_table_name' => 'invites',

    /*
    |--------------------------------------------------------------------------
    | Default Code Generator
    |--------------------------------------------------------------------------
    |
    | This option controls how the invite codes are generated.
    | You should adjust this based on your needs.
    |
    | Supported: "basic", "uuid"
    |
    */
    'driver' => env('DOORMAN_DRIVER', 'basic'),

    /*
    |--------------------------------------------------------------------------
    | Driver Configurations
    |--------------------------------------------------------------------------
    |
    | Here are each of the driver configurations for your application.
    | You can customize should your application require it.
    |
    */
    'basic' => [
        'length' => 5
    ],

    /*
    |--------------------------------------------------------------------------
    | UUID
    |--------------------------------------------------------------------------
    |
    | supported versions: 1,3,4,5
    |
    | Versions 3 & 5, require 'namespace' and 'name' to be set
    |
    */
    'uuid' => [
        'version' => 4,
    ]

];

