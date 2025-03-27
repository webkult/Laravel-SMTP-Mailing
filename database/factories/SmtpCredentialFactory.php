<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SmtpCredentialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'host' => $this->faker->domainName,
            'port' => $this->faker->numberBetween(1025, 65535),
            'encryption' => $this->faker->randomElement(['ssl', 'tls']),
            'username' => $this->faker->userName,
            'password' => $this->faker->password,
        ];
    }
}
