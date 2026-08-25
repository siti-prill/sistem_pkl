<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiKesimpulan extends Model
{
    use HasFactory;

    protected $table = 'nilai_kesimpulan';

    protected $fillable = [
        'penempatan_id',
        'guru_id',
        'nilai_kesimpulan',
        'catatan_kesimpulan',
    ];

    protected $casts = [
        'nilai_kesimpulan' => 'float',
    ];

    public function penempatan()
    {
        return $this->belongsTo(PenempatanPkl::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
