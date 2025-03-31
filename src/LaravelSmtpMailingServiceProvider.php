<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\CreateSmtpAccountAliasAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\DeleteSmtpAccountAliasAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\UpdateSmtpAccountAliasAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpCredential\CreateSmtpCredentialAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpCredential\DeleteSmtpCredentialAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpCredential\UpdateSmtpCredentialAction;
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

        // Bind Actions
        $this->app->bind(
            CreateSmtpCredentialAction::class,
            config('laravel-smtp-mailing.actions.smtp_credential.create', CreateSmtpCredentialAction::class)
        );

        $this->app->bind(
            UpdateSmtpCredentialAction::class,
            config('laravel-smtp-mailing.actions.smtp_credential.update', UpdateSmtpCredentialAction::class)
        );

        $this->app->bind(
            DeleteSmtpCredentialAction::class,
            config('laravel-smtp-mailing.actions.smtp_credential.delete', DeleteSmtpCredentialAction::class)
        );

        $this->app->bind(
            CreateSmtpAccountAliasAction::class,
            config('laravel-smtp-mailing.actions.smtp_account_alias.create', CreateSmtpAccountAliasAction::class)
        );

        $this->app->bind(
            UpdateSmtpAccountAliasAction::class,
            config('laravel-smtp-mailing.actions.smtp_account_alias.update', UpdateSmtpAccountAliasAction::class)
        );

        $this->app->bind(
            DeleteSmtpAccountAliasAction::class,
            config('laravel-smtp-mailing.actions.smtp_account_alias.delete', DeleteSmtpAccountAliasAction::class)
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
