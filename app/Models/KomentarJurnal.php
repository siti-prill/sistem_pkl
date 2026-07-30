<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomentarJurnal extends Model
{
    use HasFactory;

    protected $table = 'komentar_jurnal';
    protected $fillable = [
        'jurnal_id',
        'guru_id',
        'komentar'
    ];

    // Relasi ke Jurnal Harian
    public function jurnalHarian()
    {
        return $this->belongsTo(JurnalHarian::class);
    }

    // Relasi ke Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
