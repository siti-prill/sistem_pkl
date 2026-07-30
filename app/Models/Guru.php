<?php

namespace App\Models;

use App\Models\KomentarJurnal;
use App\Models\MonitoringNilai;
use App\Models\PenempatanPkl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus';
    protected $fillable = [
        'user_id',
        'nip',
        'nama_guru',
        'no_telepon',
        'alamat'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Penempatan PKL
    public function penempatan()
    {
        return $this->hasMany(PenempatanPkl::class);
    }

    // Relasi ke Komentar Jurnal
    public function komentarJurnal()
    {
        return $this->hasMany(KomentarJurnal::class);
    }

    // Relasi ke Monitoring Nilai
    public function monitoringNilai()
    {
        return $this->hasMany(MonitoringNilai::class);
    }
}
