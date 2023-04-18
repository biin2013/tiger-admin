<?php

namespace Biin2013\Tiger\Admin\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitUser extends Command
{
    protected $hidden = true;
    protected $signature = 'admin:init:user';
    protected $description = 'init user';

    public function handle(): void
    {
        $this->info('start init user');

        $this->init();

        $this->info('init user succeed!');
    }

    private function init(): void
    {
        $model = new (config('tiger.admin.database.user_model'));
        $model->updateOrCreate([
            'id' => config('tiger.admin.user.system_id')
        ], [
            'username' => config('tiger.admin.user.system_username'),
            'password' => '',
            'status' => 0
        ]);
        $model->updateOrCreate([
            'id' => config('tiger.admin.user.admin_id')
        ], [
            'username' => config('tiger.admin.user.admin_username'),
            'password' => Hash::make(config('tiger.admin.user.admin_password'))
        ]);
        DB::statement('alter table ' . config('tiger.admin.database.user_table') . ' AUTO_INCREMENT=' .
            config('tiger.admin.user.other_user_id_start'));
    }
}