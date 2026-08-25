<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringNilai extends Model
{
    use HasFactory;

    protected $table = 'monitoring_nilai';
    protected $fillable = [
        'penempatan_id',
        'guru_id',
        'role_penilai',
        'aspek_penilaian',
        'nilai',
        'catatan',
        'is_hidden_from_siswa',
        'tanggal_penilaian',
    ];

    protected $casts = [
        'is_hidden_from_siswa' => 'boolean',
        'tanggal_penilaian' => 'date',
    ];
    // Relasi ke Penempatan PKL
    public function penempatan()
    {
        return $this->belongsTo(PenempatanPkl::class);
    }

    // Relasi ke Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
