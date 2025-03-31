<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Contracts;

interface SmtpCredentialModelContract
{
    public static function create(array $attributes): self;
    public function update(array $attributes): bool;
    public function delete(): bool;
}
