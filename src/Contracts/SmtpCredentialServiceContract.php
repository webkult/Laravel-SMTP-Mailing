<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Contracts;

use Webkult\LaravelSmtpMailing\Data\SmtpCredentialData;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential as SmtpCredentialModelContract;

interface SmtpCredentialServiceContract
{
    public function create(SmtpCredentialData $data);

    public function update(SmtpCredentialModelContract $smtpCredential, SmtpCredentialData $data);

    public function delete(SmtpCredentialModelContract $smtpCredential): bool;
}
