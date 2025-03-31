<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Actions\SmtpCredential;

use Webkult\LaravelSmtpMailing\Contracts\SmtpCredentialModelContract;
use Webkult\LaravelSmtpMailing\Data\SmtpCredentialData;

class CreateSmtpCredentialAction
{
    public function __construct(
        private readonly SmtpCredentialModelContract $model
    ) {
    }

    public function execute(SmtpCredentialData $data): SmtpCredentialModelContract
    {
        return $this->model::create($data->toArray());
    }
}
