<?php

return [
    /*
    | Mapping String Driver dari Database ke Class Implementasinya
    */
    'drivers' => [
        'all' => \App\DataScopes\Drivers\AllScopeDriver::class,
        'hierarchy' => \App\DataScopes\Drivers\HierarchyScopeDriver::class,
        'own_kbo' => \App\DataScopes\Drivers\OwnKboScopeDriver::class,
    ],

    /*
    | Nama tabel yang akan digunakan oleh package ini
    */
    'table_names' => [
        'data_scopes' => 'data_scopes',
        'model_has_data_scopes' => 'model_has_data_scopes',
    ],
];