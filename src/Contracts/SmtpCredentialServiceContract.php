<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Contracts;

use Webkult\LaravelSmtpMailing\Data\SmtpCredentialData;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential;

interface SmtpCredentialServiceContract
{
    public function create(SmtpCredentialData $data): SmtpCredential;

    public function update(SmtpCredential $smtpCredential, SmtpCredentialData $data): SmtpCredential;

    public function delete(SmtpCredential $smtpCredential): bool;
}
