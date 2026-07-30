<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kompetensis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kompetensi', 50)->unique();
            $table->string('nama_kompetensi', 255);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kompetensis');
    }
};
