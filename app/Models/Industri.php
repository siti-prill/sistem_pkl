<?php

namespace App\Models;

use App\Models\PenempatanPkl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industri extends Model
{
    use HasFactory;

    protected $table = 'industris';
    protected $fillable = [
        'kode_perusahaan',
        'nama_perusahaan',
        'alamat',
        'no_telepon',
        'email',
        'bidang_usaha',
        'penanggung_jawab',
        'kuota',
        'status'
    ];

    // Relasi ke Penempatan PKL
    public function penempatan()
    {
        return $this->hasMany(PenempatanPkl::class);
    }
}
