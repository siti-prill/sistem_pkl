<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalHarian extends Model
{
    use HasFactory;

    protected $table = 'jurnal_harian';
    protected $fillable = [
        'penempatan_id',
        'tanggal',
        'aktivitas',
        'dokumentasi',
        'status'
    ];

    // Relasi ke Penempatan PKL
    public function penempatan()
    {
        return $this->belongsTo(PenempatanPkl::class, 'penempatan_id');
    }

    // Relasi ke Komentar Jurnal
    public function komentarJurnal()
    {
        return $this->hasMany(KomentarJurnal::class, 'jurnal_id');
    }
}
