<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_pkl', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuan_pkl', 'pekerjaan_orang_tua')) {
                $table->string('pekerjaan_orang_tua')->after('jurusan');
            }
            if (!Schema::hasColumn('pengajuan_pkl', 'penempatan_id')) {
                $table->foreignId('penempatan_id')->nullable()->constrained('penempatan_pkl')->nullOnDelete()->after('catatan_admin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_pkl', function (Blueprint $table) {
            $table->dropForeign(['penempatan_id']);
            $table->dropColumn(['pekerjaan_orang_tua', 'penempatan_id']);
        });
    }
};
