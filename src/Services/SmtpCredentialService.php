<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Services;

use Webkult\LaravelSmtpMailing\Actions\SmtpCredential\CreateSmtpCredentialAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpCredential\DeleteSmtpCredentialAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpCredential\UpdateSmtpCredentialAction;
use Webkult\LaravelSmtpMailing\Contracts\SmtpCredentialServiceContract;
use Webkult\LaravelSmtpMailing\Data\SmtpCredentialData;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential;
use Illuminate\Support\Facades\Config;

class SmtpCredentialService implements SmtpCredentialServiceContract
{
    protected string $modelClass;

    public function __construct(
        protected CreateSmtpCredentialAction $createAction,
        protected UpdateSmtpCredentialAction $updateAction,
        protected DeleteSmtpCredentialAction $deleteAction
    ) {
        $this->modelClass = Config::get('laravel-smtp-mailing.models.smtp_credential');
    }

    public function create(SmtpCredentialData $data): SmtpCredential
    {
        return $this->createAction->execute($data);
    }

    public function update(SmtpCredential $smtpCredential, SmtpCredentialData $data): SmtpCredential
    {
        return $this->updateAction->execute($smtpCredential, $data);
    }


    public function delete(SmtpCredential $smtpCredential): bool
    {
        return $this->deleteAction->execute($smtpCredential);
    }
}
