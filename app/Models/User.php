<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'password_copy',
        'role',
    ];

    protected $hidden = [
        'password',
        'password_copy',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke Guru
    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    // Relasi ke Industri
    public function industri()
    {
        return $this->hasOne(Industri::class);
    }

    /**
     * Simpan salinan password dalam bentuk terenkripsi (bisa didekripsi admin).
     * Panggil setiap kali password diubah.
     */
    public function setPasswordCopy(?string $plainPassword): void
    {
        $this->forceFill([
            'password_copy' => $plainPassword
                ? \Illuminate\Support\Facades\Crypt::encryptString($plainPassword)
                : null,
        ])->saveQuietly();
    }

    /**
     * Ambil password asli dari salinan terenkripsi (null jika tidak tersedia).
     */
    public function getReadablePassword(): ?string
    {
        if (!$this->password_copy) {
            return null;
        }

        try {
            return \Illuminate\Support\Facades\Crypt::decryptString($this->password_copy);
        } catch (\Throwable $e) {
            return null;
        }
    }

    // Cek Role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    public function isIndustri()
    {
        return $this->role === 'industri';
    }
}
