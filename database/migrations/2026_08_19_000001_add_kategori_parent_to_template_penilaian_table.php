<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('template_penilaian', function (Blueprint $table) {
            $table->string('kategori', 20)->default('sikap')->after('nama_aspek');
            $table->foreignId('parent_id')->nullable()->after('kategori')->constrained('template_penilaian')->nullOnDelete();
            $table->string('tipe', 20)->default('item')->after('parent_id');
        });
    }

    public function down(): void
    {
        Schema::table('template_penilaian', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['kategori', 'parent_id', 'tipe']);
        });
    }
};
