<?php

namespace Biin2013\Tiger\Admin\Http\Logics\Login;

use Biin2013\Tiger\Admin\Http\Concerns\UpdatePassword;
use Biin2013\Tiger\Admin\Http\Logics\AdminLogic;
use Biin2013\Tiger\Exceptions\ValidationException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class PasswordLogic extends AdminLogic
{
    use UpdatePassword;

    /**
     * @param array<mixed>|null $routeParams
     * @param array<string, mixed> $data
     * @return array<int|string, mixed>|JsonResponse|Arrayable<int|string, mixed>
     * @throws ValidationException
     */
    protected function run(?array $routeParams, array $data): array|JsonResponse|Arrayable
    {
        $info = Cache::pull($data['key']);
        if (!$info) {
            throw new ValidationException(10204);
        }

        $now = now();
        $model = app(config('tiger.admin.database.user_model'));
        $user = $model->find($info['user_info']['id']);

        $this->checkPasswordRepeat($user, $data['password']);

        $user->login_times += 1;
        $user->last_login_at = $now;
        $user->update_password_at = $now;
        $user->password = Hash::make($data['password']);
        $user->save();

        $this->addPasswordLog($user, $data['password']);

        return $info;
    }
}