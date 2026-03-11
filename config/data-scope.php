<?php

return [
    /*
    | String keys representing the available drivers for data scoping. You can add your own drivers here and implement them in the package.
    */
    'drivers' => [
        'unrestricted' => \App\DataScopes\Drivers\UnrestrictedDriver::class,
        // Add more drivers as needed, e.g. 'subordinate' => \App\DataScopes\Drivers\SubordinateDriver::class,
    ],

    /*
    | Table names that will be used by the package. You can change these if you want to customize the table names.
    */
    'table_names' => [
        'data_scopes' => 'data_scopes',
        'model_has_data_scopes' => 'model_has_data_scopes',
    ],
];