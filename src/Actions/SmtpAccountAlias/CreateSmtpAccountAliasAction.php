<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Actions\SmtpAccountAlias;

use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;

class CreateSmtpAccountAliasAction
{
    public function execute(array $data): SmtpAccountAlias
    {
        return SmtpAccountAlias::create([
            'from_email' => strtolower((string)$data['from_email']),
            'smtp_credential_id' => $data['smtp_credential_id'],
        ]);
    }
}
