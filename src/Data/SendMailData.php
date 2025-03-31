<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class SendMailData extends Data
{
    public function __construct(
        #[Required, Email]
        public string $from,
        #[Required, Email]
        public string $to,
        #[Required]
        public string $subject,
        #[Required]
        public string $message,
        #[Email]
        public ?string $cc = null,
        #[Email]
        public ?string $bcc = null,
        #[Email]
        public ?string $reply_to = null,

        /** @var null|HeaderData[] */
        public ?array $headers = null,

        /** @var null|array<string|UploadedFile> */
        public ?array $attachments = null
    ) {
    }
}
