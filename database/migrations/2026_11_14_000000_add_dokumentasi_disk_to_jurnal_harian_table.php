<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jurnal_harian', function (Blueprint $table) {
            $table->string('dokumentasi_disk', 50)->nullable()->after('dokumentasi');
        });
    }

    public function down(): void
    {
        Schema::table('jurnal_harian', function (Blueprint $table) {
            $table->dropColumn('dokumentasi_disk');
        });
    }
};
