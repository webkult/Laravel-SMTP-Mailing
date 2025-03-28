<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Webkult\LaravelSmtpMailing\Data\SendMailData;
use Webkult\LaravelSmtpMailing\Exceptions\SmtpAliasNotFoundException;
use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential;

class MailDispatchService
{
    /**
     * @throws SmtpAliasNotFoundException
     */
    public function send(SendMailData $data): void
    {
        $fromEmail = strtolower($data->from);

        /** @var SmtpAccountAlias|null $alias */
        $alias = SmtpAccountAlias::where('from_email', $fromEmail)->first();

        if (!$alias) {
            throw new SmtpAliasNotFoundException("No SMTP alias found for {$fromEmail}");
        }

        $smtp = $alias->smtpCredential;

        $this->configureMailer($smtp);

        Mail::send([], [], static function (Message $message) use ($data, $fromEmail) {
            $message->from($fromEmail);
            $message->to($data->to);
            $message->subject($data->subject);
            $message->setBody($data->message, 'text/html');

            if ($data->cc !== null) {
                $message->cc($data->cc);
            }

            if ($data->bcc !== null) {
                $message->bcc($data->bcc);
            }

            if (!empty($data->reply_to)) {
                $message->replyTo($data->reply_to);
            }

            if (!empty($data->headers)) {
                foreach ($data->headers as $header) {
                    $message->getHeaders()->addTextHeader($header->key, $header->value);
                }
            }

            if (!empty($data->attachments)) {
                foreach ($data->attachments as $attachment) {
                    if ($attachment instanceof UploadedFile) {
                        $message->attach($attachment->getRealPath(), [
                            'as' => $attachment->getClientOriginalName(),
                            'mime' => $attachment->getMimeType(),
                        ]);
                    } elseif (is_string($attachment) && file_exists($attachment)) {
                        $message->attach($attachment);
                    }
                }
            }
        });
    }

    protected function configureMailer(SmtpCredential $smtp): void
    {
        $customMailerName = 'smtp_mailer_' . Str::uuid();

        Config::set("mail.mailers.{$customMailerName}", [
            'transport' => 'smtp',
            'host' => $smtp->host,
            'port' => $smtp->port,
            'encryption' => $smtp->encryption,
            'username' => $smtp->username,
            'password' => decrypt($smtp->password),
            'timeout' => null,
            'auth_mode' => null,
        ]);

        Config::set('mail.default', $customMailerName);
    }
}
