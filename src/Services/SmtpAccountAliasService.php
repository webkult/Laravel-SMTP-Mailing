<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Services;

use Webkult\LaravelSmtpMailing\Contracts\SmtpAccountAliasServiceContract;
use Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\CreateSmtpAccountAliasAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\UpdateSmtpAccountAliasAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\DeleteSmtpAccountAliasAction;
use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;

class SmtpAccountAliasService implements SmtpAccountAliasServiceContract
{
    public function __construct(
        protected CreateSmtpAccountAliasAction $createAction,
        protected UpdateSmtpAccountAliasAction $updateAction,
        protected DeleteSmtpAccountAliasAction $deleteAction
    ) {}

    public function create(array $data): SmtpAccountAlias
    {
        return $this->createAction->execute($data);
    }

    public function update(SmtpAccountAlias $alias, array $data): SmtpAccountAlias
    {
        return $this->updateAction->execute($alias, $data);
    }

    public function delete(SmtpAccountAlias $alias): bool
    {
        return $this->deleteAction->execute($alias);
    }
}
