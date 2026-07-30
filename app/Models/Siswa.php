<?php

namespace App\Models;

use App\Models\PenempatanPkl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';
    protected $fillable = [
        'user_id',
        'nis',
        'nama_siswa',
        'kelas',
        'jurusan',
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
    public function pengajuanPkl()
    {
        return $this->hasOne(PengajuanPkl::class);
    }
}
