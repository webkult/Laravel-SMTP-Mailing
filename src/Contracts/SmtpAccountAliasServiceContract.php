<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Contracts;

use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;

interface SmtpAccountAliasServiceContract
{
    public function create(array $data): SmtpAccountAlias;

    public function update(SmtpAccountAlias $alias, array $data): SmtpAccountAlias;

    public function delete(SmtpAccountAlias $alias): bool;
}
