<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Contracts;

use Webkult\LaravelSmtpMailing\Data\SmtpAccountAliasData;
use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;

interface SmtpAccountAliasServiceContract
{
    public function create(SmtpAccountAliasData $data): SmtpAccountAlias;

    public function update(SmtpAccountAlias $alias, SmtpAccountAliasData $data): SmtpAccountAlias;

    public function delete(SmtpAccountAlias $alias): bool;
}
