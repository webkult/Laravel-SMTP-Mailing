<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias;

use Webkult\LaravelSmtpMailing\Data\SmtpAccountAliasData;
use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;

class CreateSmtpAccountAliasAction
{
    public function execute(SmtpAccountAliasData $data): SmtpAccountAlias
    {
        return SmtpAccountAlias::create($data->toArray());
    }

}
