<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing;

use Illuminate\Support\ServiceProvider;
use Webkult\LaravelSmtpMailing\Contracts\SmtpAccountAliasServiceContract;
use Webkult\LaravelSmtpMailing\Contracts\SmtpCredentialModelContract;
use Webkult\LaravelSmtpMailing\Contracts\SmtpCredentialServiceContract;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential;
use Webkult\LaravelSmtpMailing\Services\SmtpAccountAliasService;
use Webkult\LaravelSmtpMailing\Services\SmtpCredentialService;

class LaravelSmtpMailingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-smtp-mailing.php', 'laravel-smtp-mailing');

        $this->app->bind(
            SmtpCredentialModelContract::class,
            config('laravel-smtp-mailing.models.smtp_credential', SmtpCredential::class)
        );

        $this->app->bind(
            SmtpCredentialServiceContract::class,
            config('laravel-smtp-mailing.services.smtp_credential_service', SmtpCredentialService::class)
        );

        $this->app->bind(
            SmtpAccountAliasServiceContract::class,
            config('laravel-smtp-mailing.services.smtp_account_alias_service', SmtpAccountAliasService::class)
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-smtp-mailing.php' => config_path('laravel-smtp-mailing.php'),
        ], 'laravel-smtp-mailing-config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
}
