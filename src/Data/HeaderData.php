<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Data;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class HeaderData extends Data
{
    #[Required]
    public string $key;

    #[Required]
    public string $value;
}
