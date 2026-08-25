<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplatePenilaian extends Model
{
    use HasFactory;

    protected $table = 'template_penilaian';

    protected $fillable = [
        'nama_aspek',
        'kategori',
        'parent_id',
        'tipe',
        'deskripsi',
        'instruksi',
        'rentang_nilai_min',
        'rentang_nilai_max',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(TemplatePenilaian::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(TemplatePenilaian::class, 'parent_id')->orderBy('urutan');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function getHurufAttribute(): string
    {
        return match(true) {
            $this->rentang_nilai_max >= 90 => 'A',
            $this->rentang_nilai_max >= 80 => 'B',
            $this->rentang_nilai_max >= 70 => 'C',
            default => 'D',
        };
    }

    public static function nilaiToHuruf(int $nilai): string
    {
        return match(true) {
            $nilai >= 90 => 'A',
            $nilai >= 80 => 'B',
            $nilai >= 70 => 'C',
            default => 'D',
        };
    }
}
