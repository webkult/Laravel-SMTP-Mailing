<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias;

use Webkult\LaravelSmtpMailing\Data\SmtpAccountAliasData;
use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;

class UpdateSmtpAccountAliasAction
{
    public function execute(SmtpAccountAlias $alias, SmtpAccountAliasData $data): SmtpAccountAlias
    {
        $alias->update([
            'from_email' => $data->from_email ? strtolower($data->from_email) : $alias->from_email,
            'smtp_credential_id' => $data->smtp_credential_id ?? $alias->smtp_credential_id,
        ]);

        return $alias;
    }
}
