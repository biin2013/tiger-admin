<?php

namespace Biin2013\Tiger\Admin\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function modelName(): string
    {
        return config('tiger.admin.database.user_model');
    }

    /**
     * @return array<string,mixed>
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->userName,
            'password' => password_hash('123456', PASSWORD_BCRYPT),
            'nickname' => $this->faker->name
        ];
    }
}