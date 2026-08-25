<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('industris', function (Blueprint $table) {
            // Jurusan yang dituju industri ini (mis. "XII RPL", "Semua Jurusan").
            $table->string('jurusan', 100)->nullable()->after('bidang_usaha');
        });
    }

    public function down(): void
    {
        Schema::table('industris', function (Blueprint $table) {
            $table->dropColumn('jurusan');
        });
    }
};
