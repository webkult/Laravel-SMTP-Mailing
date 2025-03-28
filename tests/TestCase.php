<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Webkult\LaravelSmtpMailing\LaravelSmtpMailingServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [LaravelSmtpMailingServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));

        $app['config']->set('mail.default', 'log');
        $app['config']->set('mail.mailers.log', ['transport' => 'log', 'channel' => env('MAIL_LOG_CHANNEL')]);

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
