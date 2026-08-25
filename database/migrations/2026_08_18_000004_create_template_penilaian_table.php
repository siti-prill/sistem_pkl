<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_penilaian', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aspek', 100);
            $table->text('deskripsi')->nullable();
            $table->text('instruksi')->nullable();
            $table->integer('rentang_nilai_min')->default(0);
            $table->integer('rentang_nilai_max')->default(100);
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_penilaian');
    }
};
