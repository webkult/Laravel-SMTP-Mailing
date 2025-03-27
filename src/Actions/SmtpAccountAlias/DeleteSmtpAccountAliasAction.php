<?php
declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias;

use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;

class DeleteSmtpAccountAliasAction
{
    public function execute(SmtpAccountAlias $alias): bool
    {
        return $alias->delete();
    }
}
