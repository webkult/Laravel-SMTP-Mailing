<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('smtp_account_aliases', static function (Blueprint $table) {
            $table->id();

            $table->string('from_email')->unique();
            $table->foreignId('smtp_credential_id')
                ->constrained('smtp_credentials')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smtp_account_aliases');
    }
};
