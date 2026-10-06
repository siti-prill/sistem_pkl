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
        Schema::table('kompetensis', function (Blueprint $table) {
            $table->string('kategori', 50)->nullable()->after('nama_kompetensi');
        });

        // Backfill kategori dari nama jurusan yang sudah ada
        $map = ['RPL' => 'RPL', 'TKJ' => 'TKJ', 'DKV' => 'DKV', 'PSPT' => 'PSPT'];
        foreach (\App\Models\Kompetensi::all() as $k) {
            foreach ($map as $needle => $kategori) {
                if (stripos($k->nama_kompetensi, $needle) !== false) {
                    $k->kategori = $kategori;
                    $k->save();
                    break;
                }
            }
        }

        // Backfill kategori industri dari jurusan
        foreach (\App\Models\Industri::all() as $i) {
            if (empty($i->kategori)) {
                foreach ($map as $needle => $kategori) {
                    if (stripos($i->jurusan ?? '', $needle) !== false) {
                        $i->kategori = $kategori;
                        break;
                    }
                }
                if (empty($i->kategori)) {
                    $i->kategori = 'Semua';
                }
                $i->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kompetensis', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
