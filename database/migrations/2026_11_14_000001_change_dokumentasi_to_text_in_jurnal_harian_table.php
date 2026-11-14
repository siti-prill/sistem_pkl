<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jurnal_harian', function (Blueprint $table) {
            $table->text('dokumentasi')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('jurnal_harian', function (Blueprint $table) {
            $table->string('dokumentasi', 255)->nullable()->change();
        });
    }
};
