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
        'pilihan_2',
        'jurusan',
        'penghasilan_ortu',
        'status',
        'tempat_diterima',
        'catatan_admin'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
