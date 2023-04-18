<?php

return [
    'personal' => [
        'name' => 'personal',
        'icon' => '',
        'permission' => false,
        'children' => [
            'permissions' => [
                'name' => 'my permissions',
                'icon' => '',
                'pages' => [
                    'index' => [
                        'permissions' => [
                            'show'
                        ]
                    ],
                    'authorization' => [
                        'name' => 'my authorizations',
                        'icon' => '',
                        'permissions' => [
                            'show',
                            'store' => 'create authorization',
                            'update' => 'update authorization',
                            'destroy' => 'cancel authorization'
                        ]
                    ]
                ]
            ],
            'settings' => [
                'name' => 'preferences',
                'icon' => '',
                'pages' => [
                    'index' => [
                        'name' => 'list',
                        'icon' => '',
                        'permissions' => [
                            'show',
                            'update'
                        ]
                    ]
                ]
            ],
            'downloads' => [
                'name' => 'download',
                'pages' => [
                    'index' => [
                        'permissions' => [
                            'show'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'system' => [
        'name' => 'system',
        'icon' => '',
        'children' => [
            'departments' => [
                'name' => 'department'
            ],
            'users' => [
                'name' => 'user'
            ]
        ]
    ],
    'log' => [
        'name' => 'log',
        'icon' => '',
        'children' => [
            'logins' => [
                'name' => 'login',
                'icon' => '',
                'pages' => [
                    'index' => [
                        'name' => 'list',
                        'icon' => '',
                        'permissions' => [
                            'show'
                        ]
                    ]
                ]
            ],
            'operates' => [
                'name' => 'operate',
                'icon' => '',
                'pages' => [
                    'index' => [
                        'permissions' => [
                            'show'
                        ]
                    ]
                ]
            ]
        ]
    ]
];