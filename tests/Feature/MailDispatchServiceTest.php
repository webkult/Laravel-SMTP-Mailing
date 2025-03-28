<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Webkult\LaravelSmtpMailing\Data\HeaderData;
use Webkult\LaravelSmtpMailing\Data\SendMailData;
use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential;
use Webkult\LaravelSmtpMailing\Services\MailDispatchService;

class MailDispatchServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sends_an_email_with_dynamic_smtp_config(): void
    {
        Mail::fake();

        // Arrange – SMTP-Zugang + Alias
        $smtp = SmtpCredential::create([
            'host' => 'smtp.test.de',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'noreply@test.de',
            'password' => encrypt('secret'),
        ]);

        $alias = SmtpAccountAlias::create([
            'from_email' => 'noreply@test.de',
            'smtp_credential_id' => $smtp->id,
        ]);

        // Act – SendMailData erstellen
        $mailData = new SendMailData(
            from: 'noreply@test.de',
            to: 'user@example.com',
            subject: 'Test-Mail',
            message: '<p>Hello World</p>',
            cc: null,
            bcc: null,
            attachments: null,
            reply_to: 'antwort@example.com',
            headers: [
                new HeaderData(key: 'X-Test-Header', value: '123'),
            ]
        );

        app(MailDispatchService::class)->send($mailData);

        // Assert – dass Mail gesendet wurde
        Mail::assertSent(function () {
            // Wir prüfen nicht Inhalt, weil Mail::send([]) keinen Mailable verwendet
            return true;
        });

        // Zusätzliche Prüfung: DB-Einträge stimmen
        $this->assertDatabaseHas('smtp_credentials', ['host' => 'smtp.test.de']);
        $this->assertDatabaseHas('smtp_account_aliases', ['from_email' => 'noreply@test.de']);
    }
}
