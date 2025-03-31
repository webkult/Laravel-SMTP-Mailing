<?php

declare(strict_types=1);


use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Webkult\LaravelSmtpMailing\Data\HeaderData;
use Webkult\LaravelSmtpMailing\Data\SendMailData;
use Webkult\LaravelSmtpMailing\Exceptions\SmtpAliasNotFoundException;
use Webkult\LaravelSmtpMailing\Mail\OutboundMail;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential;
use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;
use Webkult\LaravelSmtpMailing\Services\MailDispatchService;
use Webkult\LaravelSmtpMailing\Tests\TestCase;

uses(RefreshDatabase::class);

class MailDispatchServiceTest extends TestCase
{
    public function test_sends_an_email_with_correct_dynamic_smtp_configuration()
    {
        Mail::fake();

        $smtp = SmtpCredential::create([
            'host' => 'smtp.example.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'noreply@example.com',
            'password' => encrypt('secret'),
        ]);

        SmtpAccountAlias::create([
            'from_email' => 'noreply@example.com',
            'smtp_credential_id' => $smtp->id,
        ]);

        $data = new SendMailData(
            from: 'noreply@example.com',
            to: 'receiver@example.com',
            subject: 'Test Email',
            message: '<p>This is a test</p>',
            cc: null,
            bcc: null,
            reply_to: 'reply@example.com',
            headers: [new HeaderData('X-Test', 'value123')],
            attachments: null
        );

        app(MailDispatchService::class)->send($data);

        Mail::assertQueued(OutboundMail::class, fn (OutboundMail $mail) =>
            $mail->data->to === $data->to &&
            $mail->data->from === $data->from &&
            $mail->data->subject === $data->subject
        );
    }

    public function test_throws_exception_when_alias_is_missing()
    {
        $this->expectException(SmtpAliasNotFoundException::class);

        $data = new SendMailData(
            from: 'missing@example.com',
            to: 'receiver@example.com',
            subject: 'Fehlerfall',
            message: '<p>Kein Alias</p>',
            cc: null,
            bcc: null,
            reply_to: null,
            headers: null,
            attachments: null
        );

        app(MailDispatchService::class)->send($data);
    }

    public function test_sends_email_with_attachment()
    {
        Storage::fake('local');

        $smtp = SmtpCredential::create([
            'host' => 'smtp.example.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'noreply@example.com',
            'password' => encrypt('secret'),
        ]);

        SmtpAccountAlias::create([
            'from_email' => 'noreply@example.com',
            'smtp_credential_id' => $smtp->id,
        ]);

        $fakeFile = UploadedFile::fake()->create('test.pdf', 100, 'application/pdf');

        $data = new SendMailData(
            from: 'noreply@example.com',
            to: 'receiver@example.com',
            subject: 'Test mit Anhang',
            message: '<p>Datei</p>',
            cc: null,
            bcc: null,
            reply_to: null,
            headers: null,
            attachments: [$fakeFile]
        );

        app(MailDispatchService::class)->send($data);

        Mail::assertQueued(OutboundMail::class);
    }

    public function test_queues_the_email_when_should_queue_is_enabled()
    {
        $smtp = SmtpCredential::create([
            'host' => 'smtp.example.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'noreply@example.com',
            'password' => encrypt('secret'),
        ]);

        SmtpAccountAlias::create([
            'from_email' => 'noreply@example.com',
            'smtp_credential_id' => $smtp->id,
        ]);

        $data = new SendMailData(
            from: 'noreply@example.com',
            to: 'queued@example.com',
            subject: 'Async',
            message: 'Dies wird gequeued',
            cc: null,
            bcc: null,
            reply_to: null,
            headers: null,
            attachments: null
        );

        app(MailDispatchService::class)->send($data);

        Mail::assertQueued(OutboundMail::class);
    }
}
