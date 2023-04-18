<?php

namespace Biin2013\Tiger\Admin\Http\Logics\Login;

use Biin2013\Tiger\Admin\Enums\LoginStatus;
use Biin2013\Tiger\Admin\Http\Logics\AdminLogic;
use Biin2013\Tiger\Admin\Models\Log\Login;
use Biin2013\Tiger\Admin\Models\System\User;
use Biin2013\Tiger\Exceptions\ValidationException;
use Biin2013\Tiger\Support\Response;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginLogic extends AdminLogic
{
    /**
     * @param array<mixed>|null $routeParams
     * @param array<string, mixed> $data
     * @return array<mixed>|JsonResponse|Arrayable<int|string, mixed>
     * @throws ValidationException
     */
    protected function run(?array $routeParams, array $data): array|JsonResponse|Arrayable
    {
        $user = $this->attempt($data);
        $token = $user->createToken($data['device']);
        $accessToken = $token->accessToken->fresh();
        $response = [
            'user_info' => $user->toArray(),
            'access_token' => [
                'status' => $accessToken->getAttribute('status'),
                'token' => $token->plainTextToken
            ]
        ];

        if ($this->needUpdatePassword($user)) {
            return $this->updatePasswordResponse($user, $response);
        }

        return $response;
    }

    /**
     * @param array<string, mixed> $data
     * @return User
     * @throws ValidationException
     */
    private function attempt(array $data): User
    {
        $model = config('tiger.admin.database.user_model');
        $user = (new $model)->where('username', $data['username'])->where('status', 1)->first([
            'id',
            'username',
            'nickname',
            'password',
            'login_times',
            'last_login_at',
            'update_password_at',
            'brief'
        ]);
        if (!$user) {
            $this->addLoginLogs($data, LoginStatus::USERNAME);
            throw new ValidationException(10107);
        }
        if (!Hash::check($data['password'], $user->password)) {
            $this->addLoginLogs($data, LoginStatus::PASSWORD, $user);
            throw new ValidationException(10108);
        }

        $this->addLoginLogs($data, LoginStatus::SUCCESS, $user);
        return $user;
    }

    /**
     * @param User $user
     * @return bool
     */
    private function needUpdatePassword(User $user): bool
    {
        $intervalDays = config('tiger.admin.user.update_password_interval_days');
        if (!$intervalDays) return false;
        if (!$user->getAttribute('update_password_at')) return true;

        return Carbon::now()->subDays($intervalDays)->isBefore($user->getAttribute('update_password_at'));
    }

    /**
     * @param User $user
     * @param array<string, mixed> $data
     * @return JsonResponse
     */
    private function updatePasswordResponse(User $user, array $data): JsonResponse
    {
        $key = Str::uuid()->toString();
        Cache::put($key, $data, 60);

        return Response::error(10109, '', compact('key'));
    }

    /**
     * @param array<string, mixed> $data
     * @param LoginStatus $status
     * @param User|null $user
     */
    private function addLoginLogs(array $data, LoginStatus $status, ?User $user = null): void
    {
        Login::query()->create([
            'username' => $data['username'],
            'user_id' => $user && $user['id'],
            'ip' => request()->ip(),
            'agent' => request()->userAgent(),
            'status' => $status
        ]);
    }
}