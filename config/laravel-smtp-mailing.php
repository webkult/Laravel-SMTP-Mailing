<?php

declare(strict_types=1);
return [

    'models' => [
        'smtp_credential' => \Webkult\LaravelSmtpMailing\Models\SmtpCredential::class,
    ],

    'services' => [
        'smtp_credential_service' => \Webkult\LaravelSmtpMailing\Services\SmtpCredentialService::class,
        'smtp_account_alias_service' => \Webkult\LaravelSmtpMailing\Services\SmtpAccountAliasService::class,
    ],

];
