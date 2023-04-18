<?php

namespace Biin2013\Tiger\Admin\Tests;

use Biin2013\Tiger\Admin\AdminServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Testing\TestResponse;
use Orchestra\Testbench\TestCase as BaseCase;

class TestCase extends BaseCase
{
    protected string $url = '';

    /**
     * @param array<mixed> $data
     * @param string $method
     * @param string|null $url
     * @return TestResponse
     */
    protected function httpRequest(array $data = [], string $method = 'POST', ?string $url = null): TestResponse
    {
        return $this->json($method, $url ?? $this->url, $data);
    }

    /**
     * @param Application $app
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [AdminServiceProvider::class];
    }
}