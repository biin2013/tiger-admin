<?php

namespace Biin2013\Tiger\Admin\Tests\Feature;

use Biin2013\Tiger\Admin\Tests\TestCase;
use Illuminate\Support\Str;

/**
 * @group feature
 */
class LoginTest extends TestCase
{
    protected string $url = '/login';

    /**
     * @test
     */
    public function login_username_required(): void
    {
        $this->assertEquals(10100, $this->httpRequest()['code']);
    }

    /**
     * @test
     */
    public function login_username_min(): void
    {
        $this->assertEquals(10101, $this->httpRequest(['username' => 'abc'])['code']);
    }

    /**
     * @test
     */
    public function login_username_max(): void
    {
        $this->assertEquals(10102, $this->httpRequest(['username' => Str::random(46)])['code']);
    }

    /**
     * @test
     */
    public function login_password_required(): void
    {
        $this->assertEquals(10103, $this->httpRequest(['username' => config('tiger.admin.user.admin_username')])['code']);
    }

    /**
     * @test
     */
    public function login_password_max(): void
    {
        $this->assertEquals(
            10104,
            $this->httpRequest([
                'username' => config('tiger.admin.user.admin_username'),
                'password' => Str::random(256)
            ])['code']
        );
    }

    /**
     * @test
     */
    public function login_device_required(): void
    {
        $this->assertEquals(
            10105,
            $this->httpRequest([
                'username' => config('tiger.admin.user.admin_username'),
                'password' => Str::random(255)
            ])['code']
        );
    }

    /**
     * @test
     */
    public function login_device_max(): void
    {
        $this->assertEquals(
            10106,
            $this->httpRequest([
                'username' => config('tiger.admin.user.admin_username'),
                'password' => '123456',
                'device' => Str::random(101)
            ])['code']
        );
    }

    /**
     * @test
     */
    public function login_success(): void
    {
        $response = $this->httpRequest([
            'username' => config('tiger.admin.user.admin_username'),
            'password' => config('tiger.admin.user.admin_password'),
            'device' => 'chrome'
        ]);

        if ($response['code'] === 0) {
            $this->assertArrayHasKey('userInfo', $response['data']);
            $this->assertArrayHasKey('username', $response['data']['userInfo']);
            $this->assertArrayHasKey('accessToken', $response['data']);
            $this->assertArrayHasKey('token', $response['data']['accessToken']);
        } else {
            $this->assertEquals(10109, $response['code']);
            $this->assertArrayHasKey('key', $response['data']);

            $this->httpRequest([
                'key' => $response['data']['key'],
                'password' => config('tiger.admin.user.admin_password'),
                'password_confirmation' => config('tiger.admin.user.admin_password')
            ], 'PUT', '/login/password');
        }
    }
}