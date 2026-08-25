<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Salinan password terenkripsi (AES-256 via Crypt) agar admin bisa melihatnya kembali.
            // Tidak menggantikan kolom `password` yang tetap di-hash bcrypt.
            $table->text('password_copy')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_copy');
        });
    }
};
