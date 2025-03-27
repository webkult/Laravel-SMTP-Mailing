<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkult\LaravelSmtpMailing\Models\SmtpCredential;

class SmtpAccountAliasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'from_email' => $this->faker->unique()->safeEmail,
            'smtp_credential_id' => SmtpCredential::factory(),
        ];
    }
}
