<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Webkult\LaravelSmtpMailing\Data\SendMailData;
use Webkult\LaravelSmtpMailing\Exceptions\SmtpAliasNotFoundException;
use Webkult\LaravelSmtpMailing\Services\MailDispatchService;

class MailController
{
    public function __invoke(SendMailData $data, MailDispatchService $mailService): JsonResponse
    {
        try {
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
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Versand fehlgeschlagen: ' . $e->getMessage(),
            ], 500);
        }
    }
}
