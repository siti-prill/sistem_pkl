<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('industris', function (Blueprint $table) {
            $table->string('kategori', 50)->nullable()->after('bidang_usaha');
        });
    }

    public function down()
    {
        Schema::table('industris', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};