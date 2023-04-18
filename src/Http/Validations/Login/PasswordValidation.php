<?php

namespace Biin2013\Tiger\Admin\Http\Validations\Login;

use Biin2013\Tiger\Admin\Http\Validations\AdminValidation;

class PasswordValidation extends AdminValidation
{
    protected bool $appendDefaultRules = false;

    protected function rules(): array
    {
        return [
            'key' => ['required'],
            'password' => ['bail', 'required', 'max:255', 'confirmed']
        ];
    }

    protected function codes(): array
    {
        return [
            'key.required' => 10200,
            'password.required' => 10201,
            'password.max' => 10202,
            'password.confirmed' => 10203
        ];
    }
}