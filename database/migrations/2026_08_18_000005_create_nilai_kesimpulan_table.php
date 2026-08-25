<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_kesimpulan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penempatan_id')->constrained('penempatan_pkl')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->decimal('nilai_kesimpulan', 5, 2)->nullable();
            $table->text('catatan_kesimpulan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_kesimpulan');
    }
};
