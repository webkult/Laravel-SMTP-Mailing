<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Throwable;
use Webkult\LaravelSmtpMailing\Data\SendMailData;
use Webkult\LaravelSmtpMailing\Exceptions\SmtpAliasNotFoundException;

class MailController
{
    public function __invoke(SendMailData $data): JsonResponse
    {
        try {
            $mailService = app(Config::get('laravel-smtp-mailing.services.mail_dispatch_service'));
            $mailService->send($data);

            return response()->json([
                'success' => true,
                'message' => 'E-Mail wurde erfolgreich versendet.',
            ], 200);
        } catch (SmtpAliasNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Versand fehlgeschlagen: ' . $e->getMessage(),
            ], 500);
        }
    }
}
