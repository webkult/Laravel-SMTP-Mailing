<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Webkult\LaravelSmtpMailing\Data\SendMailData;
use Webkult\LaravelSmtpMailing\Exceptions\SmtpAliasNotFoundException;
use Webkult\LaravelSmtpMailing\Mail\OutboundMail;
use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential;

class MailDispatchService
{
    const MAILER_NAME = 'smtp_mailer';

    /**
     * @throws SmtpAliasNotFoundException
     */
    public function send(SendMailData $data): void
    {
        $fromEmail = strtolower($data->from);

        $alias = SmtpAccountAlias::where('from_email', $fromEmail)->first();

        if (! $alias) {
            throw new SmtpAliasNotFoundException("No SMTP alias found for {$fromEmail}");
        }

        $smtp = $alias->smtpCredential;
        $configName = $this->getMailerName($fromEmail);

        $this->configureMailer($smtp, $configName);

        Mail::mailer($configName)
            ->to($data->to)
            ->cc($data->cc)
            ->bcc($data->bcc)
            ->send(new OutboundMail($data));
    }

    protected function getMailerName(string $fromEmail): string
    {
        return self::MAILER_NAME . '_' . md5($fromEmail);
    }

    protected function configureMailer(SmtpCredential $smtp, string $configName): void
    {
        Config::set("mail.mailers.{$configName}", [
            'transport'  => 'smtp',
            'host'       => $smtp->host,
            'port'       => $smtp->port,
            'encryption' => $smtp->encryption,
            'username'   => $smtp->username,
            'password'   => decrypt($smtp->password),
            'timeout'    => null,
            'auth_mode'  => null,
        ]);
    }
}
