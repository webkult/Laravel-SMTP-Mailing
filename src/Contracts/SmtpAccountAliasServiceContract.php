<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Contracts;

use Webkult\LaravelSmtpMailing\Data\SmtpAccountAliasData;

interface SmtpAccountAliasServiceContract
{
    public function create(SmtpAccountAliasData $data);

    public function update(SmtpAccountAliasModelContract $alias, SmtpAccountAliasData $data);

    public function delete(SmtpAccountAliasModelContract $alias): bool;
}
