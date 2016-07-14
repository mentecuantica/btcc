<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Packages Settings
     |--------------------------------------------------------------------------
     |
     | Debugbar is enabled by default, when debug is set to true in app.php.
     | You can override the value by setting enable to true or false instead of null.
     |
     */

    'enabled' => TRUE,


    'calculations'=>[
        'ternary'=>[
            'payment'=>'fixed',
            'value'=>10,
            'currency'=>'$'
        ]
    ],



    'packages' => [
        [
            'name'         => 'Starter',
            'id'           => 'SRT1',
            'price'        => 100,
            'features'     => [
                'Feature 1',
                'Feature 2',
                'Feature 3',

            ],
            'calculations' => [
                'linear' => [
                    'levels' => [
                        1,
                        2
                    ]
                ],
                'ternary'=>[
                    'levels'=>range(1,5)
                ],
            ]
        ],
        [
            'name'         => 'Advanced',
            'id'           => 'ADV2',
            'price'        => 300,
            'features'     => [
                'Feature 1',
                'Feature 2',
                'Feature 3',

            ],
            'calculations' => [
                'linear' => [
                    'levels' => [
                        1,
                        4
                    ]
                ],
                'ternary'=>[
                    'levels'=>range(1,12)
                ],
            ]
        ],
        [
            'name'         => 'Proficient',
            'id'           => 'PRF3',
            'price'        => 600,
            'features'     => [
                'Feature 1',
                'Feature 2',
                'Feature 3',

            ],
            'calculations' => [
                'linear' => [
                    'levels' => range(1,25)
                    ]
                ,
                'ternary'=>[
                    'levels'=>range(1,25)
                ],
            ]
        ],

    ],

];
