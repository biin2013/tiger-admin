<?php

namespace Biin2013\Tiger\Admin\Models\System;

use Biin2013\Tiger\Admin\Models\AdminModel;
use Biin2013\Tiger\Admin\Models\Log\Login;
use Biin2013\Tiger\Admin\Models\Log\Password;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizeContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticateContract;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Laravel\Sanctum\HasApiTokens;

class User extends AdminModel implements AuthorizeContract, AuthenticateContract
{
    use SoftDeletes;
    use HasApiTokens;
    use Authorizable;
    use Authenticatable;

    protected $fillable = [
        'id',
        'username',
        'password',
        'nickname',
        'status',
        'brief'
    ];
    protected $hidden = ['password'];
    /**
     * @var string[]
     */
    protected $casts = [
        'last_login_at' => 'datetime',
        'update_password_at' => 'datetime'
    ];

    /**
     * @param array<mixed> $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('tiger.admin.database.user_table'));
        parent::__construct($attributes);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($model) => $model->nickname = $model->nickname ?? $model->username);
    }

    public function isSuperAdmin(): bool
    {
        return $this->getKey() == config('tiger.admin.user.admin_id');
    }

    public function passwords(): HasMany
    {
        return $this->hasMany(Password::class);
    }

    public function logins(): HasMany
    {
        return $this->hasMany(Login::class);
    }
}