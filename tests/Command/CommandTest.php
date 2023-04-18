<?php

namespace Biin2013\Tiger\Admin\Tests\Command;

use Biin2013\Tiger\Admin\Tests\TestCase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

/**
 * @group command
 */
class CommandTest extends TestCase
{
    private function success(): void
    {
        $this->assertEquals(true, true);
    }

    /**
     * @test
     */
    public function migrate(): void
    {
        $migrator = App::make('migrator');
        if (!$migrator->repositoryExists()) {
            $migrator->getRepository()->createRepository();
        }
        $migrator->run($migrator->paths());
        $this->success();
    }

    /**
     * @test
     */
    public function init_user(): void
    {
        Artisan::call('admin:init:user');
        $this->success();
    }
}