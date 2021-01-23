<?php

return [

    // ENABLE IP ACCESS
    'enabled' => env('IPACCESS_ENABLED', true),

    // ENV TO CHECK IP ADDRESS FOR
    'whitelist_env' => env('IPACCESS_ENV', 'production'),

    // WHITELIST IPs GET ACCESS TO ENV
    'whitelist' => [
        'range' => [
            '127.0.0.*',
        ],
        'list' => [
            '127.0.0.1',
        ]
    ],

    // ACCESS DENIED RESPONSE SETTINGS
    'redirect_to'      => env('IPACCESS_DENIED_URL', null),
    'response_status'  => env('IPACCESS_DENIED_STATUS', 403),
    'response_message' => env('IPACCESS_DENIED_MESSAGE', 'Access not Allowed'),

    /**
     * Keep track of the ip address using Nova
     */
    'use_nova' => true,
];

