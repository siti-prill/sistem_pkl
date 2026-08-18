<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengajuan_pkl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('pilihan_1');
            $table->string('pilihan_2');
            $table->string('jurusan');
            $table->string('pekerjaan_orang_tua');
            $table->string('penghasilan_ortu');
            $table->string('alamat');
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->string('tempat_diterima')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
            $table->foreignId('penempatan_id')->nullable()->constrained('penempatan_pkl')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_pkl');
    }
};
