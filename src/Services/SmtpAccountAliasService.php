<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Services;

use Webkult\LaravelSmtpMailing\Contracts\SmtpAccountAliasModelContract;
use Webkult\LaravelSmtpMailing\Contracts\SmtpAccountAliasServiceContract;
use Webkult\LaravelSmtpMailing\Data\SmtpAccountAliasData;

class SmtpAccountAliasService implements SmtpAccountAliasServiceContract
{
    public function __construct(
        protected readonly SmtpAccountAliasModelContract $model
    ) {
    }

    public function create(SmtpAccountAliasData $data): SmtpAccountAliasModelContract
    {
        return $this->model::create($data->toArray());
    }

    public function update(SmtpAccountAliasModelContract $alias, SmtpAccountAliasData $data): SmtpAccountAliasModelContract
    {
        $alias->update($data->toArray());
        return $alias;
    }

    public function delete(SmtpAccountAliasModelContract $alias): bool
    {
        return $alias->delete();
    }
}
