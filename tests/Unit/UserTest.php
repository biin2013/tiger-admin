<?php

namespace Biin2013\Tiger\Admin\Tests\Unit;

use Biin2013\Tiger\Admin\Database\Factories\UserFactory;
use Biin2013\Tiger\Admin\Tests\TestCase;

/**
 * @group unit
 */
class UserTest extends TestCase
{
    /**
     * @test
     */
    public function create_user(): void
    {
        $user = UserFactory::new()->create();
        dd($user->toArray());
    }
}