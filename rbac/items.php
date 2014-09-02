<?php
return [
    'user' => [
        'type' => 1,
        'description' => 'User',
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Admin',
        'children' => [
            'user',
        ],
    ],
    'superadmin' => [
        'type' => 1,
        'description' => 'Superadmin',
        'children' => [
            'admin',
        ],
    ],
];
