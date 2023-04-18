<?php

namespace Biin2013\Tiger\Admin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void registerMenu(string $path, string $locale = 'zh_CN')
 * @method static array getMenu(string $locale = 'zh_CN')
 *
 * @see \Biin2013\Tiger\Admin\Admin
 */
class Admin extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'admin';
    }
}