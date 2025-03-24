<?php

return [

    'default' => env('BROADCAST_DRIVER', 'redis'),


    'connections' => [

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],
    ],

];