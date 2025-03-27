<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('smtp_credentials', static function (Blueprint $table) {
            $table->id();

            $table->string('host');
            $table->unsignedInteger('port');
            $table->string('encryption')->nullable(); // z. B. null, ssl, tls
            $table->string('username');
            $table->text('password'); // verschlüsselt gespeichert

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smtp_credentials');
    }
};
