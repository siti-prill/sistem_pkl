<?php

namespace App\Models;

use App\Models\PenempatanPkl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    public const KATEGORI_JURUSAN = [
        'XII RPL'     => 'RPL',
        'XII TKJ 1'   => 'TKJ',
        'XII TKJ 2'   => 'TKJ',
        'XII DKV 1'   => 'DKV',
        'XII DKV 2'   => 'DKV',
        'XII PSPT'    => 'PSPT',
    ];

    public function getKategoriJurusanAttribute()
    {
        return self::KATEGORI_JURUSAN[$this->jurusan] ?? $this->jurusan;
    }
    use HasFactory;

    protected $table = 'siswas';
    protected $fillable = [
        'user_id',
        'nis',
        'nama_siswa',
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
