<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Data;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class SmtpCredentialData extends Data
{
    public function __construct(
        #[Required]
        public string $host,

        #[Required]
        public int $port,

        public ?string $encryption = null,

        #[Required]
        public string $username,

        #[Required]
        public string $password,
    ) {}
}
