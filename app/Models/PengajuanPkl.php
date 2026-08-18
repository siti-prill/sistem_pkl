<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPkl extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_pkl';

    protected $fillable = [
        'siswa_id',
        'pilihan_1',
        'industri_1',
        'pilihan_2',
        'industri_2',
        'jurusan',
        'pekerjaan_orang_tua',
        'penghasilan_ortu',
        'alamat',
        'status',
        'tempat_diterima',
        'catatan_admin',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
    public function penempatan()
    {
        return $this->belongsTo(PenempatanPkl::class, 'penempatan_id');
    }
}
