<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Actions\SmtpCredential;

use Webkult\LaravelSmtpMailing\Data\SmtpCredentialData;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential;

class UpdateSmtpCredentialAction
{
    public function execute(SmtpCredential $smtpCredential, SmtpCredentialData $data): SmtpCredential
    {
        $smtpCredential->update([
            'host' => $data->host ?? $smtpCredential->host,
            'port' => $data->port ?? $smtpCredential->port,
            'encryption' => $data->encryption ?? $smtpCredential->encryption,
            'username' => $data->username ?? $smtpCredential->username,
            'password' => $data->password ?? $smtpCredential->password,
        ]);

        return $smtpCredential;
    }
}
