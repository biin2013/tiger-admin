<?php

namespace Biin2013\Tiger\Admin;

use Exception;

class Admin
{
    /**
     * @var array<string, mixed>
     */
    private array $menu = [];

    public function registerMenu(string $path, string $locale = 'zh_CN'): void
    {
        $realPath = realpath($path);
        if (!$realPath) {
            throw new Exception("menu file[$path] don't exist");
        }
        if (!in_array($realPath, $this->menu[$locale] ?? [])) {
            $this->menu[$locale][] = $realPath;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getMenu(string $locale = 'zh_CN'): array
    {
        return $this->menu[$locale] ?? [];
    }
}