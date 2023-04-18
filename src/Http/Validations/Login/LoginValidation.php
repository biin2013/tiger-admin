<?php

namespace Biin2013\Tiger\Admin\Http\Validations\Login;

use Biin2013\Tiger\Admin\Http\Validations\AdminValidation;

class LoginValidation extends AdminValidation
{
    protected bool $appendDefaultRules = false;

    protected function rules(): array
    {
        return [
            'username' => ['bail', 'required', 'min:4', 'max:45'],
            'password' => ['bail', 'required', 'max:255'],
            'device' => ['bail', 'required', 'max:100']
        ];
    }

    protected function codes(): array
    {
        return [
            'username.required' => 10100,
            'username.min' => 10101,
            'username.max' => 10102,
            'password.required' => 10103,
            'password.max' => 10104,
            'device.required' => 10105,
            'device.max' => 10106
        ];
    }
}