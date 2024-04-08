<?php

return [
    'api_key' => 'xqEA_lzNOJ4S0_JOwGL8xzJJ_VJDIo_ZBtJWQGGgmLk=',
    'api_url' => 'https://api2.ippanel.com/api/v1',
    'from_number' => '+983000505',
    'patterns' => [
        'verify_otp' => [
            'template' => 'xxown165whviw47',
            'keys' => [
                'code',
            ],
        ],
        'welcome' => [
            'template' => 'welcome',
            'keys' => [
                'name',
            ],
        ],
        'verify_forgot_password_code' => [
            'template' => 'forgot_password_code',
            'keys' => [
                'code',
            ],
        ],
    ],
];
