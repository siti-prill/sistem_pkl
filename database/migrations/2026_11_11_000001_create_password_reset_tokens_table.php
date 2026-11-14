<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel password_reset_tokens hilang karena migrasi bawaan
     * create_users_table dimodifikasi (role enum) dan bagian pembuatan
     * tabel ini ikut terhapus. Tanpa tabel ini, fitur "Lupa Password"
     * (POST /forgot-password) gagal dengan SQLSTATE 42S02.
     */
    public function up(): void
    {
        if (Schema::hasTable('password_reset_tokens')) {
            return;
        }

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
