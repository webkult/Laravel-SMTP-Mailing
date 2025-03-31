<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Services;

use Webkult\LaravelSmtpMailing\Contracts\SmtpCredentialModelContract;
use Webkult\LaravelSmtpMailing\Contracts\SmtpCredentialServiceContract;
use Webkult\LaravelSmtpMailing\Data\SmtpCredentialData;

class SmtpCredentialService implements SmtpCredentialServiceContract
{
    public function __construct(
        protected readonly SmtpCredentialModelContract $model
    ) {
    }

    public function create(SmtpCredentialData $data): SmtpCredentialModelContract
    {
        return $this->model::create($data->toArray());
    }

    public function update(SmtpCredentialModelContract $smtpCredential, SmtpCredentialData $data): SmtpCredentialModelContract
    {
        $smtpCredential->update($data->toArray());
        return $smtpCredential;
    }

    public function delete(SmtpCredentialModelContract $smtpCredential): bool
    {
        return $smtpCredential->delete();
    }
}
