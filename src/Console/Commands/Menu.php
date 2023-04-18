<?php

namespace Biin2013\Tiger\Admin\Console\Commands;

use Biin2013\Tiger\Admin\Facades\Admin;
use Biin2013\Tiger\Admin\Models\System\Menu as MenuModel;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menu extends Command
{
    protected $signature = 'admin:menu 
                            { --L|locale=zh_CN : set locale, default zh_CN }
                            { --F|force : clear menu and generate new }';
    protected $description = 'system menu generator';
    private string $locale = 'zh_CN';
    private string $menuModel = '';
    private string $permissionModel = '';
    /**
     * @var array<mixed>
     */
    private array $lang = [];
    /**
     * @var array<mixed>
     */
    private array $menus = [];
    /**
     * @var array<mixed>
     */
    private array $updatedMenus = [];
    /**
     * @var array<mixed>
     */
    private array $updatedPermissions = [];

    public function handle(): void
    {
        $this->info('start generate menu');

        $this->locale = $this->option('locale');
        $this->lang = include __DIR__ . '/../../../lang/' . $this->locale . '/console/menu/common.php';
        $this->menuModel = config('tiger.admin.database.menu_model');
        $this->permissionModel = config('permission.models.permission');


        DB::transaction(function () {
            $menus = array_map(fn($v) => include $v, Admin::getMenu($this->locale));
            foreach ($menus as $menu) {
                $this->categoryMenu($menu);
            }

            $this->removeMenus();
            $this->removePermissions();
        });

        $this->info('generate menu succeed!');
    }

    /**
     * @param array<mixed> $menus
     * @param Model|null $parent
     * @param bool|null $permission
     */
    private function categoryMenu(array $menus, ?Model $parent = null, ?bool $permission = null): void
    {
        $menuSeq = 1;
        foreach ($menus as $key => $menu) {
            $url = ltrim(($parent->url ?? '') . '/' . $key, '/');
            $name = $menu['name'] ?? $this->menus[$url]['name'];
            $data = [
                'pid' => $parent->id ?? 0,
                'name' => $name,
                'full_name' => array_merge($parent->full_name ?? [], [$name]),
                'icon' => $menu['icon'] ?? $this->menus[$url]['icon'] ?? '',
                'permission' => $menu['permission'] ?? $this->menus[$url]['permission'] ?? $permission ?? false,
                'keep_alive' => $menu['keep_alive'] ?? false,
                'redirect' => $menu['redirect'] ?? '',
                'seq' => $menuSeq++
            ];
            $this->menus[$url] = $data;
            $menuParent = $this->createMenu(
                $url,
                $data['pid'],
                $name,
                $data['full_name'],
                $data['seq'],
                $data['icon'],
                $data['permission'],
                $data['redirect'],
                $data['keep_alive']
            );
            if (!empty($menu['children'])) {
                $this->categoryMenu(
                    $menu['children'],
                    $menuParent,
                    $menu['children']['permission'] ?? $permission
                );
            } else {
                if (empty($menu['pages'])) {
                    $menu['pages'] = ['index'];
                }

                $pageSeq = 1;
                foreach ($menu['pages'] as $k => $v) {
                    $pageUrl = $url . '/' . (is_int($k) ? $v : $k);
                    $pageData = $this->resolvePageMenu($k, $v, $menuParent);
                    $pageData['seq'] = $pageSeq++;
                    $this->menus[$pageUrl] = $pageData;
                    $pageParent = $this->createMenu(
                        $pageUrl,
                        $pageData['pid'],
                        $pageData['name'],
                        $pageData['full_name'],
                        $pageData['seq'],
                        $pageData['icon'],
                        $pageData['permission'],
                        $pageData['redirect'],
                        $pageData['keep_alive']
                    );
                    $permissions = (!is_array($v) || empty($v['permissions']))
                        ? ['show', 'store', 'update', 'destroy']
                        : $v['permissions'];
                    $permissionSeq = 1;
                    foreach ($permissions as $pk => $pv) {
                        $permissionUrl = $pageUrl . '/' . (is_int($pk) ? $pv : $pk);
                        $permissionData = $this->resolvePermissionMenu(
                            $pk,
                            $pv,
                            $pageParent,
                            $permission ?? true
                        );
                        $permissionData['seq'] = $permissionSeq++;
                        $this->menus[$permissionUrl] = $permissionData;
                        $this->createMenu(
                            $permissionUrl,
                            $permissionData['pid'],
                            $permissionData['name'],
                            $permissionData['full_name'],
                            $permissionData['seq'],
                            $permissionData['icon'],
                            $permissionData['permission']
                        );
                    }
                }
            }

        }
    }

    /**
     * @param string|int $key
     * @param string|array<mixed> $value
     * @param Model $parent
     * @return array<mixed>
     */
    private function resolvePageMenu(string|int $key, string|array $value, Model $parent): array
    {
        return is_string($value)
            ? [
                'pid' => $parent['id'],
                'name' => $this->lang['pages'][$value],
                'full_name' => array_merge($parent['full_name'], [$this->lang['pages'][$value]]),
                'icon' => '',
                'permission' => false,
                'keep_alive' => true,
                'redirect' => ''
            ]
            : [
                'pid' => $parent['id'],
                'name' => $value['name'] ?? $this->lang['pages'][$key],
                'full_name' => array_merge($parent['full_name'], [$value['name'] ?? $this->lang['pages'][$key]]),
                'icon' => $value['icon'] ?? '',
                'permission' => false,
                'keep_alive' => $value['keep_alive'] ?? true,
                'redirect' => $value['redirect'] ?? ''
            ];
    }

    /**
     * @param string|int $key
     * @param string $value
     * @param Model $parent
     * @param bool $permission
     * @return array<string,mixed>
     */
    private function resolvePermissionMenu(string|int $key, string $value, Model $parent, bool $permission = true): array
    {
        $name = is_int($key) ? $this->lang['permissions'][$value] : $value;
        return [
            'pid' => $parent['id'],
            'name' => $name,
            'full_name' => array_merge($parent['full_name'], [$name]),
            'icon' => '',
            'permission' => $permission
        ];
    }

    /**
     * @param string $url
     * @param int $pid
     * @param string $name
     * @param array<string[]> $fullName
     * @param int $seq
     * @param string $icon
     * @param bool $permission
     * @param string $redirect
     * @param bool $keepAlive
     * @return MenuModel
     */
    private function createMenu(
        string $url,
        int    $pid,
        string $name,
        array  $fullName,
        int    $seq,
        string $icon = '',
        bool   $permission = false,
        string $redirect = '',
        bool   $keepAlive = false
    ): MenuModel
    {
        $permissionModel = null;
        if ($permission) {
            $permissionModel = (new $this->permissionModel)->updateOrCreate([
                'name' => str_replace('/', '.', $url),
                'guard_name' => 'api'
            ]);
            array_push($this->updatedPermissions, $permissionModel->id);
        }
        $menu = (new $this->menuModel)->updateOrCreate(
            [
                'url' => $url
            ],
            [
                'pid' => $pid,
                'name' => $name,
                'full_name' => $fullName,
                'icon' => $icon,
                'permission_id' => $permissionModel->id ?? 0,
                'seq' => $seq,
                'locale' => $this->locale,
                'keep_alive' => $keepAlive,
                'redirect' => $redirect
            ]
        );
        array_push($this->updatedMenus, $menu->id);

        return $menu;
    }

    private function removeMenus(): void
    {
        (new $this->menuModel)->whereNotIn('id', $this->updatedMenus)->delete();
    }

    private function removePermissions(): void
    {
        $ids = (new $this->permissionModel)->whereNotIn('id', $this->updatedPermissions)->pluck('id')->toArray();
        if (empty($ids)) return;

        DB::table(config('permission.table_names.model_has_permissions'))->whereIn('permission_id', $ids)->delete();
        DB::table(config('permission.table_names.role_has_permissions'))->whereIn('permission_id', $ids)->delete();
        (new $this->permissionModel)->destroy($ids);

        $this->call('permission:cache-reset');
    }
}