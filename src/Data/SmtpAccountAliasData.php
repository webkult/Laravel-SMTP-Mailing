<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Data;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class SmtpAccountAliasData extends Data
{
    public function __construct(
        #[Required, Email]
        public string $from_email,

        #[Required]
        public int $smtp_credential_id,
    ) {
    }
}
