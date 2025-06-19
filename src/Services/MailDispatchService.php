<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Webkult\LaravelSmtpMailing\Data\SendMailData;
use Webkult\LaravelSmtpMailing\Exceptions\SmtpAliasNotFoundException;
use Webkult\LaravelSmtpMailing\Mail\OutboundMail;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential;

class MailDispatchService
{
    public const MAILER_NAME = 'smtp_mailer';

    protected string $smtpAccountAliasModel;
    protected string $smtpCredentialModel;

    public function __construct()
    {
        $this->smtpAccountAliasModel = Config::get('laravel-smtp-mailing.models.smtp_account_alias');
        $this->smtpCredentialModel = Config::get('laravel-smtp-mailing.models.smtp_credential');
    }

    /**
     * @throws SmtpAliasNotFoundException
     */
    public function send(SendMailData $data): void
    {
        $fromEmail = strtolower($data->from);
        $smtp = $this->getSmtpCredentials($fromEmail);
        $configName = $this->getMailerName($fromEmail);

        if (!app()->environment('testing')) {
            $this->configureMailer($smtp, $configName);
            $mailer = Mail::mailer($configName);
        } else {
            $mailer = Mail::fake();
        }

        $mailer->to($data->to)
            ->cc($data->cc)
            ->bcc($data->bcc)
            ->send(new OutboundMail($data));
    }

    /**
     * @throws SmtpAliasNotFoundException
     */
    public function getSmtpCredentials(string $fromEmail)
    {
        $alias = $this->smtpAccountAliasModel::where('from_email', $fromEmail)
            ->when(
                config('laravel-smtp-mailing.enable_like_query_for_alias'),
                fn($query) => $query->orWhere('from_email', 'like', "%{$fromEmail}%")
            )->first();

        if (!$alias) {
            $alias = $this->smtpAccountAliasModel::where('from_email', config('laravel-smtp-mailing.default_from'))
                ->first();
        }

        if (!$alias) {
            throw new SmtpAliasNotFoundException("No SMTP alias found for {$fromEmail}");
        }
        return $alias->smtpCredential;
    }

    public function getMailerName(string $fromEmail): string
    {
        return self::MAILER_NAME . '__' . $fromEmail;
    }

    public function configureMailer(SmtpCredential $smtp, string $configName): void
    {
        Config::set("mail.mailers.{$configName}", [
            'transport' => 'smtp',
            'host' => $smtp->host,
            'port' => $smtp->port,
            'encryption' => $smtp->encryption,
            'username' => $smtp->username,
            'password' => $smtp->password,
            'timeout' => null,
            'auth_mode' => null,
        ]);
    }

    public function configureMailerByMailerName($mailerName): void
    {
        $names = explode(MailDispatchService::MAILER_NAME . '__', $mailerName);
        $fromEmail = last($names);
        $smtp = $this->getSmtpCredentials($fromEmail);
        $configName = $this->getMailerName($fromEmail);
        $this->configureMailer($smtp, $configName);
    }
}
