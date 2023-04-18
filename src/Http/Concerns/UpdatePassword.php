<?php

namespace Biin2013\Tiger\Admin\Http\Concerns;

use Biin2013\Tiger\Admin\Models\System\User;
use Biin2013\Tiger\Exceptions\ValidationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

trait UpdatePassword
{
    /**
     * @param User $user
     * @param string $password
     * @throws ValidationException
     */
    protected function checkPasswordRepeat(User $user, string $password): void
    {
        $user->load([
            'passwords' => fn($query) => $query->where(
                'created_at',
                '>',
                Carbon::now()->subDays(config('tiger.admin.user.password_unique_days'))
            )
        ]);

        // @phpstan-ignore-next-line
        $user->passwords->each(fn($v) => Hash::check($password, $v['password']) && throw new ValidationException(10205));
    }

    /**
     * @param User $user
     * @param string $password
     */
    protected function addPasswordLog(User $user, string $password): void
    {
        $user->passwords()->create(['password' => Hash::make($password)]);
    }
}