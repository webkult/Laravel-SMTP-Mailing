<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Services;

use Webkult\LaravelSmtpMailing\Actions\SmtpCredential\CreateSmtpCredentialAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpCredential\DeleteSmtpCredentialAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpCredential\UpdateSmtpCredentialAction;
use Webkult\LaravelSmtpMailing\Contracts\SmtpCredentialServiceContract;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential;

class SmtpCredentialService implements SmtpCredentialServiceContract
{
    public function __construct(
        protected CreateSmtpCredentialAction $createAction,
        protected UpdateSmtpCredentialAction $updateAction,
        protected DeleteSmtpCredentialAction $deleteAction
    ) {
    }

    public function create(array $data): SmtpCredential
    {
        return $this->createAction->execute($data);
    }

    public function update(SmtpCredential $smtpCredential, array $data): SmtpCredential
    {
        return $this->updateAction->execute($smtpCredential, $data);
    }

    public function delete(SmtpCredential $smtpCredential): bool
    {
        return $this->deleteAction->execute($smtpCredential);
    }
}
