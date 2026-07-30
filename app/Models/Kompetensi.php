<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kompetensi extends Model
{
    use HasFactory;

    protected $table = 'kompetensis';
    protected $fillable = [
        'kode_kompetensi',
        'nama_kompetensi',
        'deskripsi'
    ];

    // Relasi ke Penempatan PKL
    public function penempatan()
    {
        return $this->hasMany(PenempatanPkl::class);
    }
}
