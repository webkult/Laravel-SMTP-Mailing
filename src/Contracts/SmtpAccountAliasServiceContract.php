<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Contracts;

use Webkult\LaravelSmtpMailing\Data\SmtpAccountAliasData;
use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;

interface SmtpAccountAliasServiceContract
{
    public function create(SmtpAccountAliasData $data): SmtpAccountAliasModelContract;

    public function update(SmtpAccountAliasModelContract $alias, SmtpAccountAliasData $data): SmtpAccountAliasModelContract;

    public function delete(SmtpAccountAliasModelContract $alias): bool;
}
