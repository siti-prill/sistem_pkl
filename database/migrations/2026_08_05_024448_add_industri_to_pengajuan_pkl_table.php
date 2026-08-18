<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengajuan_pkl', function (Blueprint $table) {
            $table->string('industri_1', 255)->nullable()->after('pilihan_1');
            $table->string('industri_2', 255)->nullable()->after('pilihan_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_pkl', function (Blueprint $table) {
            $table->dropColumn(['industri_1', 'industri_2']);
        });
    }
};
