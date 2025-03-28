<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Actions\SmtpCredential;

use Webkult\LaravelSmtpMailing\Data\SmtpCredentialData;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential;

class CreateSmtpCredentialAction
{
    public function execute(SmtpCredentialData $data): SmtpCredential
    {
        return SmtpCredential::create($data->toArray());
    }
}
