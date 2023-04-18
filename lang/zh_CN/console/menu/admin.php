<?php

return [
    'personal' => [
        'name' => '个人中心',
        'icon' => '',
        'permission' => false,
        'children' => [
            'permissions' => [
                'name' => '我的权限',
                'icon' => '',
                'pages' => [
                    'index' => [
                        'permissions' => [
                            'show'
                        ]
                    ],
                    'authorization' => [
                        'name' => '我的授权',
                        'icon' => '',
                        'permissions' => [
                            'show',
                            'store' => '创建授权',
                            'update' => '更新授权',
                            'destroy' => '取消授权'
                        ]
                    ]
                ]
            ],
            'settings' => [
                'name' => '个性化设置',
                'icon' => '',
                'pages' => [
                    'index' => [
                        'name' => '列表',
                        'icon' => '',
                        'permissions' => [
                            'show',
                            'update'
                        ]
                    ]
                ]
            ],
            'downloads' => [
                'name' => '下载中心',
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
        'name' => '系统管理',
        'icon' => '',
        'children' => [
            'departments' => [
                'name' => '组织架构'
            ],
            'users' => [
                'name' => '用户管理'
            ]
        ]
    ],
    'log' => [
        'name' => '日志管理',
        'icon' => '',
        'children' => [
            'logins' => [
                'name' => '登录日志',
                'icon' => '',
                'pages' => [
                    'index' => [
                        'name' => '列表',
                        'icon' => '',
                        'permissions' => [
                            'show'
                        ]
                    ]
                ]
            ],
            'operates' => [
                'name' => '操作日志',
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