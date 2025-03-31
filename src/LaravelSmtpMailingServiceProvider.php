<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Webkult\LaravelSmtpMailing\Contracts\SmtpAccountAliasModelContract;
use Webkult\LaravelSmtpMailing\Contracts\SmtpAccountAliasServiceContract;
use Webkult\LaravelSmtpMailing\Contracts\SmtpCredentialModelContract;
use Webkult\LaravelSmtpMailing\Contracts\SmtpCredentialServiceContract;
use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential;
use Webkult\LaravelSmtpMailing\Services\SmtpAccountAliasService;
use Webkult\LaravelSmtpMailing\Services\SmtpCredentialService;

class LaravelSmtpMailingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-smtp-mailing.php', 'laravel-smtp-mailing');

        // Bind Models
        $this->app->bind(SmtpCredentialModelContract::class, function (Application $app) {
            return $app->make(config('laravel-smtp-mailing.models.smtp_credential', SmtpCredential::class));
        });

        $this->app->bind(
            SmtpAccountAliasModelContract::class,
            fn() => app(config('laravel-smtp-mailing.models.smtp_account_alias', SmtpAccountAlias::class))
        );

        // Bind Services
        $this->app->bind(
            SmtpAccountAliasServiceContract::class,
            function (Application $app) {
                $modelClass = config('laravel-smtp-mailing.models.smtp_account_alias_service', SmtpAccountAliasService::class);
                $model = $app->make($modelClass); // erstelle Modell-Instanz
                $serviceClass = config('laravel-smtp-mailing.services.smtp_credential_service', SmtpAccountAliasService::class);

                return new $serviceClass($model);
            }
        );

        $this->app->bind(
            SmtpCredentialServiceContract::class,
            function (Application $app) {
                $modelClass = config('laravel-smtp-mailing.models.smtp_credential', SmtpCredential::class);
                $model = $app->make($modelClass); // erstelle Modell-Instanz
                $serviceClass = config('laravel-smtp-mailing.services.smtp_credential_service', SmtpCredentialService::class);

                return new $serviceClass($model);
            }
        );

    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-smtp-mailing.php' => config_path('laravel-smtp-mailing.php'),
        ], 'laravel-smtp-mailing-config');

        $this->publishesMigrations([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'laravel-smtp-mailing-migrations');
    }
}
