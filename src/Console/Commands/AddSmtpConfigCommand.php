<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Console\Commands;

use Illuminate\Console\Command;
use Webkult\LaravelSmtpMailing\Data\SmtpCredentialData;
use Webkult\LaravelSmtpMailing\Services\SmtpCredentialService;

class AddSmtpConfigCommand extends Command
{
    protected $signature = 'smtp:add-config
                          {--host= : SMTP Host}
                          {--port= : SMTP Port}
                          {--username= : SMTP Username}
                          {--password= : SMTP Password}
                          {--encryption=tls : SMTP Encryption (tls/ssl)}';

    protected $description = 'Fügt eine neue SMTP-Konfiguration hinzu';

    public function handle(SmtpCredentialService $smtpService): int
    {
        $this->info('SMTP-Konfiguration hinzufügen...');

        $data = new SmtpCredentialData(
            host: $this->option('host') ?? $this->ask('SMTP Host'),
            port: (int) ($this->option('port') ?? $this->ask('SMTP Port')),
            username: $this->option('username') ?? $this->ask('SMTP Username'),
            password: $this->option('password') ?? $this->secret('SMTP Password'),
            encryption: $this->option('encryption') ?? $this->choice('SMTP Encryption', ['tls', 'ssl'], 'tls'),
        );

        try {
            $smtpService->create($data);
            $this->info('SMTP-Konfiguration wurde erfolgreich hinzugefügt!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Fehler beim Hinzufügen der SMTP-Konfiguration: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
} 