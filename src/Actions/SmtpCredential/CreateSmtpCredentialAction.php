<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Actions\SmtpCredential;

use Webkult\LaravelSmtpMailing\Models\SmtpCredential;

class CreateSmtpCredentialAction
{
    public function execute(array $data): SmtpCredential
    {
        return SmtpCredential::create([
            'host' => $data['host'],
            'port' => $data['port'],
            'encryption' => $data['encryption'] ?? null,
            'username' => $data['username'],
            'password' => $data['password'], // encrypted via model mutator
        ]);
    }
}
