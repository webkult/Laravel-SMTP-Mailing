<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Services;

use Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\CreateSmtpAccountAliasAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\DeleteSmtpAccountAliasAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\UpdateSmtpAccountAliasAction;
use Webkult\LaravelSmtpMailing\Contracts\SmtpAccountAliasModelContract;
use Webkult\LaravelSmtpMailing\Contracts\SmtpAccountAliasServiceContract;
use Webkult\LaravelSmtpMailing\Data\SmtpAccountAliasData;
use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;
use Illuminate\Support\Facades\Config;

class SmtpAccountAliasService implements SmtpAccountAliasServiceContract
{
    public function __construct(
        protected readonly CreateSmtpAccountAliasAction $createAction,
        protected readonly UpdateSmtpAccountAliasAction $updateAction,
        protected readonly DeleteSmtpAccountAliasAction $deleteAction
    ) {
    }

    public function create(SmtpAccountAliasData $data): SmtpAccountAliasModelContract
    {
        return $this->createAction->execute($data);
    }

    public function update(SmtpAccountAliasModelContract $alias, SmtpAccountAliasData $data): SmtpAccountAliasModelContract
    {
        return $this->updateAction->execute($alias, $data);
    }

    public function delete(SmtpAccountAliasModelContract $alias): bool
    {
        return $this->deleteAction->execute($alias);
    }
}
