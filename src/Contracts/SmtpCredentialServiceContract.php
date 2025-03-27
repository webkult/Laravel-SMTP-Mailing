<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Contracts;

use Webkult\LaravelSmtpMailing\Models\SmtpCredential;

interface SmtpCredentialServiceContract
{
    public function create(array $data): SmtpCredential;

    public function update(SmtpCredential $smtpCredential, array $data): SmtpCredential;

    public function delete(SmtpCredential $smtpCredential): bool;
}
