<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Mail;

use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Mailable;
use Symfony\Component\Mime\Email as SymfonyEmail;
use Webkult\LaravelSmtpMailing\Data\HeaderData;
use Webkult\LaravelSmtpMailing\Data\SendMailData;

use Illuminate\Contracts\Queue\ShouldQueue;

class OutboundMail extends Mailable implements ShouldQueue

{
    public function __construct(public SendMailData $data)
    {
    }

    public function build(): static
    {
        $mail = $this
            ->from($this->data->from)
            ->subject($this->data->subject)
            ->html($this->data->message);

        if ($this->data->reply_to) {
            $mail->replyTo($this->data->reply_to);
        }

        if (!empty($this->data->attachments)) {
            foreach ($this->data->attachments as $attachment) {
                if ($attachment instanceof UploadedFile) {
                    $mail->attach($attachment->getRealPath(), [
                        'as' => $attachment->getClientOriginalName(),
                        'mime' => $attachment->getMimeType(),
                    ]);
                } elseif (is_string($attachment) && file_exists($attachment)) {
                    $mail->attach($attachment);
                }
            }
        }

        // Headers via Symfony Message
        if (!empty($this->data->headers)) {
            $mail->withSymfonyMessage(function (SymfonyEmail $message) {
                foreach ($this->data->headers as $header) {
                    if ($header instanceof HeaderData) {
                        $message->getHeaders()->addTextHeader($header->key, $header->value);
                    }
                }
            });
        }

        return $mail;
    }
}
