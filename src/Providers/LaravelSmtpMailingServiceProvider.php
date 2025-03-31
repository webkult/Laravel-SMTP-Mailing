<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Providers;

use Illuminate\Support\ServiceProvider;
use Webkult\LaravelSmtpMailing\Contracts\SmtpCredentialModelContract;
use Webkult\LaravelSmtpMailing\Contracts\SmtpAccountAliasModelContract;

class LaravelSmtpMailingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/laravel-smtp-mailing.php',
            'laravel-smtp-mailing'
        );

        $this->app->bind(
            SmtpCredentialModelContract::class,
            fn ($app) => $app['config']['laravel-smtp-mailing.models.smtp_credential']
        );

        $this->app->bind(
            SmtpAccountAliasModelContract::class,
            fn ($app) => $app['config']['laravel-smtp-mailing.models.smtp_account_alias']
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/laravel-smtp-mailing.php' => config_path('laravel-smtp-mailing.php'),
            ], 'config');
        }
    }
} 