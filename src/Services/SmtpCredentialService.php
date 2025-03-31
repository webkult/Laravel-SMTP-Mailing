<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Services;

use Webkult\LaravelSmtpMailing\Actions\SmtpCredential\CreateSmtpCredentialAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpCredential\DeleteSmtpCredentialAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpCredential\UpdateSmtpCredentialAction;
use Webkult\LaravelSmtpMailing\Contracts\SmtpCredentialModelContract;
use Webkult\LaravelSmtpMailing\Contracts\SmtpCredentialServiceContract;
use Webkult\LaravelSmtpMailing\Data\SmtpCredentialData;

class SmtpCredentialService implements SmtpCredentialServiceContract
{
    public function __construct(
        protected readonly CreateSmtpCredentialAction $createAction,
        protected readonly UpdateSmtpCredentialAction $updateAction,
        protected readonly DeleteSmtpCredentialAction $deleteAction
    ) {
    }

    public function create(SmtpCredentialData $data): SmtpCredentialModelContract
    {
        return $this->createAction->execute($data);
    }

    public function update(SmtpCredentialModelContract $smtpCredential, SmtpCredentialData $data): SmtpCredentialModelContract
    {
        return $this->updateAction->execute($smtpCredential, $data);
    }

    public function delete(SmtpCredentialModelContract $smtpCredential): bool
    {
        return $this->deleteAction->execute($smtpCredential);
    }
}
