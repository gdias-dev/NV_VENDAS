<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Guard padrão (painel administrativo / staff da Nova Varonil)
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Login separado das óticas, usado no portal público (/area-das-oticas).
        'otica' => [
            'driver' => 'session',
            'provider' => 'oticas',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'oticas' => [
            'driver' => 'eloquent',
            'model' => App\Models\Otica::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
