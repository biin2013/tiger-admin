<?php

namespace Biin2013\Tiger\Admin\Models\Log;

use Biin2013\Tiger\Admin\Models\AdminModel;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Login extends AdminModel
{
    protected $table = 'admin_user_login_logs';
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'username',
        'ip',
        'agent',
        'status'
    ];

    public function ip(): Attribute
    {
        return Attribute::make(fn($value) => long2ip($value), fn($value) => ip2long($value));
    }
}