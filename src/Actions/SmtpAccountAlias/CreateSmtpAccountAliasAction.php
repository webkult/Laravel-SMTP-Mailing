<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias;

use Webkult\LaravelSmtpMailing\Contracts\SmtpAccountAliasModelContract;
use Webkult\LaravelSmtpMailing\Data\SmtpAccountAliasData;

class CreateSmtpAccountAliasAction
{
    public function __construct(
        private readonly SmtpAccountAliasModelContract $model
    ) {
    }

    public function execute(SmtpAccountAliasData $data): SmtpAccountAliasModelContract
    {
        return $this->model::create($data->toArray());
    }
}
