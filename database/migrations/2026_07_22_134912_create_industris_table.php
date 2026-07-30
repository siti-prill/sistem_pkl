<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('industris', function (Blueprint $table) {
            $table->id();
            $table->string('kode_perusahaan', 50)->unique();
            $table->string('nama_perusahaan', 255);
            $table->text('alamat');
            $table->string('no_telepon', 20);
            $table->string('email', 255)->nullable();
            $table->string('bidang_usaha', 255);
            $table->string('penanggung_jawab', 255);
            $table->integer('kuota');
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('industris');
    }
};
