<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Actions\SmtpCredential;

use Webkult\LaravelSmtpMailing\Models\SmtpCredential;

class DeleteSmtpCredentialAction
{
    public function execute(SmtpCredential $smtpCredential): bool
    {
        return $smtpCredential->delete();
    }
}
