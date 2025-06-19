<?php

namespace Webkult\LaravelSmtpMailing\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Webkult\LaravelSmtpMailing\Exceptions\SmtpAliasNotFoundException;
use Webkult\LaravelSmtpMailing\Models\SmtpAccountAlias;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential;
use Webkult\LaravelSmtpMailing\Services\MailDispatchService;
use Webkult\LaravelSmtpMailing\Tests\TestCase;

uses(RefreshDatabase::class);

class GetSmtpCredentialsTest extends TestCase
{
    public function test_throws_exception_when_no_alias_exists(): void
    {
        $this->expectException(SmtpAliasNotFoundException::class);

        app(MailDispatchService::class)->getSmtpCredentials('noreply@example.com');
    }

    public function test_throws_exception_when_alias_not_found_and_no_default_set(): void
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

        $this->expectException(SmtpAliasNotFoundException::class);

        app(MailDispatchService::class)->getSmtpCredentials('test@example.com');
    }

    public function test_returns_correct_credentials_if_alias_is_found(): void
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

        $result = app(MailDispatchService::class)->getSmtpCredentials('noreply@example.com');

        $this->assertInstanceOf(SmtpCredential::class, $result);
        $this->assertEquals($smtp->id, $result->id);
        $this->assertEquals('smtp.example.com', $result->host);
    }

    public function test_returns_default_credentials_if_alias_is_not_found(): void
    {
        $defaultSmtp = SmtpCredential::create([
            'host' => 'smtp.example.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'default@example.com',
            'password' => encrypt('secret'),
        ]);

        SmtpAccountAlias::create([
            'from_email' => 'default@example.com',
            'smtp_credential_id' => $defaultSmtp->id,
        ]);

        config(['laravel-smtp-mailing.default_from' => 'default@example.com']);

        $result = app(MailDispatchService::class)->getSmtpCredentials('default@example.com');

        $this->assertInstanceOf(SmtpCredential::class, $result);
        $this->assertEquals($defaultSmtp->id, $result->id);
        $this->assertEquals('smtp.example.com', $result->host);
    }

    public function test_dont_returns_default_credentials_if_alias_is_found(): void
    {
        $defaultSmtp = SmtpCredential::create([
            'host' => 'smtp.default.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'default@example.com',
            'password' => encrypt('secret'),
        ]);

        SmtpAccountAlias::create([
            'from_email' => 'default@example.com',
            'smtp_credential_id' => $defaultSmtp->id,
        ]);

        config(['laravel-smtp-mailing.default_from' => 'default@example.com']);

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

        $result = app(MailDispatchService::class)->getSmtpCredentials('noreply@example.com');

        $this->assertInstanceOf(SmtpCredential::class, $result);
        $this->assertEquals($smtp->id, $result->id);
        $this->assertEquals('smtp.example.com', $result->host);
        $this->assertNotEquals($defaultSmtp->id, $result->id);
        $this->assertNotEquals('smtp.default.com', $result->host);
    }

    public function test_dont_find_alias_by_like_if_like_query_config_disabled(): void
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

        config(['laravel-smtp-mailing.enable_like_query_for_alias' => false]);

        $this->expectException(SmtpAliasNotFoundException::class);

        app(MailDispatchService::class)->getSmtpCredentials('@example.com');
    }

    public function test_returns_alias_found_by_like_if_like_query_config_enabled(): void
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

        config(['laravel-smtp-mailing.enable_like_query_for_alias' => true]);

        $result = app(MailDispatchService::class)->getSmtpCredentials('@example.com');

        $this->assertInstanceOf(SmtpCredential::class, $result);
        $this->assertEquals($smtp->id, $result->id);
        $this->assertEquals('smtp.example.com', $result->host);
    }
}
