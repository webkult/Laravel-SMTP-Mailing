<?php

declare(strict_types=1);
return [

    'models' => [
        'smtp_credential' => \Webkult\LaravelSmtpMailing\Models\SmtpCredential::class,
        'smtp_account_alias' => \Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias::class,
    ],

    'services' => [
        'smtp_credential_service' => \Webkult\LaravelSmtpMailing\Services\SmtpCredentialService::class,
        'smtp_account_alias_service' => \Webkult\LaravelSmtpMailing\Services\SmtpAccountAliasService::class,
        'mail_dispatch_service' => \Webkult\LaravelSmtpMailing\Services\MailDispatchService::class,
    ],

    'actions' => [
        'smtp_credential' => [
            'create' => \Webkult\LaravelSmtpMailing\Actions\SmtpCredential\CreateSmtpCredentialAction::class,
            'update' => \Webkult\LaravelSmtpMailing\Actions\SmtpCredential\UpdateSmtpCredentialAction::class,
            'delete' => \Webkult\LaravelSmtpMailing\Actions\SmtpCredential\DeleteSmtpCredentialAction::class,
        ],
        'smtp_account_alias' => [
            'create' => \Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\CreateSmtpAccountAliasAction::class,
            'update' => \Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\UpdateSmtpAccountAliasAction::class,
            'delete' => \Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\DeleteSmtpAccountAliasAction::class,
        ],
    ],

    'default_from' => 'my@smtp-mail-config-in.database'
];
