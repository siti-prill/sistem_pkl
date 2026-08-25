<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_nilai', function (Blueprint $table) {
            $table->enum('role_penilai', ['guru', 'industri'])->default('guru')->after('guru_id');
            $table->boolean('is_hidden_from_siswa')->default(false)->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_nilai', function (Blueprint $table) {
            $table->dropColumn(['role_penilai', 'is_hidden_from_siswa']);
        });
    }
};
