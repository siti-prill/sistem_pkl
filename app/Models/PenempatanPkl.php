<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenempatanPkl extends Model
{
    use HasFactory;

    protected $table = 'penempatan_pkl';
    protected $fillable = [
        'siswa_id',
        'industri_id',
        'guru_id',
        'kompetensi_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status'
    ];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke Industri
    public function industri()
    {
        return $this->belongsTo(Industri::class);
    }

    // Relasi ke Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // Relasi ke Kompetensi
    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class);
    }

    // Relasi ke Jurnal Harian
    public function jurnalHarian()
    {
        return $this->hasMany(JurnalHarian::class, 'penempatan_id');
    }

    // Relasi ke Monitoring Nilai
    public function monitoringNilai()
    {
        return $this->hasMany(MonitoringNilai::class, 'penempatan_id');
    }
    public function pengajuan()
    {
        return $this->hasOne(PengajuanPkl::class, 'penempatan_id');
    }
}
