<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industri extends Model
{
    use HasFactory;

    protected $table = 'industris';

    /**
     * Daftar jurusan untuk pengelompokan industri.
     */
    public const JURUSAN_LIST = [
        'Semua Jurusan',
        'XII RPL',
        'XII TKJ 1',
        'XII TKJ 2',
        'XII DKV 1',
        'XII DKV 2',
        'XII PSPT',
    ];

    protected $fillable = [
        'user_id',
        'kode_perusahaan',
        'nama_perusahaan',
        'lokasi',
        'alamat',
        'no_telepon',
        'email',
        'bidang_usaha',
        'jurusan',
        'penanggung_jawab',
        'kuota',
        'status',
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
}
