<?php

namespace Biin2013\Tiger\Admin\Console\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
    protected $signature = 'admin:install';
    protected $description = 'install tiger-admin';

    public function handle(): void
    {
        $this->info('start install');

        $this->call('vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"');
        $this->call('migrate');
        $this->call('admin:init:user');

        $this->info('install succeed!');
    }
}