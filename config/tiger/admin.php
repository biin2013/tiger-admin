<?php

use Biin2013\Tiger\Admin\Http\Controllers\Foundations\EmptyController;
use Biin2013\Tiger\Admin\Models\System\Menu;
use Biin2013\Tiger\Admin\Models\System\User;

return [
    'user' => [
        // change users config before executing admin:install
        'system_id' => 1,
        'system_username' => 'system',
        'admin_id' => 2,
        'admin_username' => 'admin',
        'admin_password' => 'admin',
        'other_user_id_start' => 10000,
        // end
        'init_password' => '123456',
        'update_password_interval_days' => 30,
        'password_unique_days' => 365
    ],
    'database' => [
        'user_table' => 'admin_users',
        'user_model' => User::class,
        'menu_table' => 'admin_menus',
        'menu_model' => Menu::class
    ],
    'login' => [
        'controller_class' => EmptyController::class
    ]
];