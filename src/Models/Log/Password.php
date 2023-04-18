<?php

namespace Biin2013\Tiger\Admin\Models\Log;

use Biin2013\Tiger\Admin\Models\AdminModel;

class Password extends AdminModel
{
    protected $table = 'admin_user_password_logs';
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'password'
    ];
}