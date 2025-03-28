<?php

declare(strict_types=1);

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

uses(RefreshDatabase::class);

it('sends an email with correct dynamic smtp configuration', function () {
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
        headers: [new HeaderData(key: 'X-Test', value: 'value123')],
        attachments: null
    );

    app(MailDispatchService::class)->send($data);

    Mail::assertSent(OutboundMail::class, fn (OutboundMail $mail) =>
        $mail->data->to === $data->to &&
        $mail->data->from === $data->from &&
        $mail->data->subject === $data->subject
    );
});

it('throws exception when alias is missing', function () {
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
})->throws(SmtpAliasNotFoundException::class);

it('sends email with attachment', function () {
    Mail::fake();
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

    Mail::assertSent(OutboundMail::class);
});

it('queues the email when should queue is enabled', function () {
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
});
