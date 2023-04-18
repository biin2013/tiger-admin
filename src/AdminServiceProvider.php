<?php

namespace Biin2013\Tiger\Admin;

use Biin2013\Tiger\Admin\Admin as AdminConcrete;
use Biin2013\Tiger\Admin\Console\Commands\InitUser;
use Biin2013\Tiger\Admin\Console\Commands\Install;
use Biin2013\Tiger\Admin\Console\Commands\Menu;
use Biin2013\Tiger\Admin\Facades\Admin;
use Biin2013\Tiger\Middleware\ErrorScope;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * @var array|string[]
     */
    private array $commands = [
        Install::class,
        Menu::class
    ];

    /**
     * @var array|string[]
     */
    private array $testCommands = [
        InitUser::class
    ];

    public function register(): void
    {
        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/tiger/admin.php', 'tiger.admin');
        }
        $this->registerFacades();
    }

    public function boot(): void
    {
        $this->registerMiddleware();
        $this->registerRoutes();
        $this->registerPublish();
        $this->loadMigrations();
        $this->registerCommands();
        $this->registerMenu();
        $this->registerSuperAdmin();
    }

    private function registerMiddleware(): void
    {
        $this->app->make(Router::class)->aliasMiddleware('error-scope', ErrorScope::class);
    }

    private function registerFacades(): void
    {
        $this->app->bind('admin', fn() => new AdminConcrete());
    }

    private function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
    }

    private function registerPublish(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/tiger/admin.php' => config_path('tiger/admin.php')
            ], 'admin-config');
        }
    }

    private function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
        if ($this->app->runningUnitTests()) {
            $this->commands($this->testCommands);
        }
    }

    private function registerMenu(): void
    {
        Admin::registerMenu(
            __DIR__ . '/../lang/zh_CN/console/menu/admin.php'
        );
        Admin::registerMenu(
            __DIR__ . '/../lang/en/console/menu/admin.php',
            'en'
        );
    }

    private function registerSuperAdmin(): void
    {
        Gate::before(fn($user) => $user->isSuperAdmin());
    }
}