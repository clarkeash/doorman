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

    'uuid' => [
        'version' => 4,
    ]

];

