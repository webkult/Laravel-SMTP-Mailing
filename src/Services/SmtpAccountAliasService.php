<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Services;

use Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\CreateSmtpAccountAliasAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\DeleteSmtpAccountAliasAction;
use Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias\UpdateSmtpAccountAliasAction;
use Webkult\LaravelSmtpMailing\Contracts\SmtpAccountAliasServiceContract;
use Webkult\LaravelSmtpMailing\Data\SmtpAccountAliasData;
use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;
use Illuminate\Support\Facades\Config;

class SmtpAccountAliasService implements SmtpAccountAliasServiceContract
{
    protected string $modelClass;

    public function __construct(
        protected CreateSmtpAccountAliasAction $createAction,
        protected UpdateSmtpAccountAliasAction $updateAction,
        protected DeleteSmtpAccountAliasAction $deleteAction
    ) {
        $this->modelClass = Config::get('laravel-smtp-mailing.models.smtp_account_alias');
    }

    public function create(SmtpAccountAliasData $data): SmtpAccountAlias
    {
        return $this->createAction->execute($data);
    }

    public function update(SmtpAccountAlias $alias, SmtpAccountAliasData $data): SmtpAccountAlias
    {
        return $this->updateAction->execute($alias, $data);
    }

    public function delete(SmtpAccountAlias $alias): bool
    {
        return $this->deleteAction->execute($alias);
    }
}
