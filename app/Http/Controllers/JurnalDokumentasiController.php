<?php

namespace App\Http\Controllers;

use App\Models\JurnalHarian;
use Illuminate\Support\Facades\Storage;

class JurnalDokumentasiController extends Controller
{
    public function __invoke(JurnalHarian $jurnal)
    {
        $user = auth()->user();

        $allowed = false;
        if ($user->role === 'admin') {
            $allowed = true;
        } elseif ($user->role === 'siswa' && $user->siswa) {
            $penempatan = $jurnal->penempatan;
            $allowed = $penempatan && $penempatan->siswa_id === $user->siswa->id;
        } elseif ($user->role === 'guru' && $user->guru) {
            $penempatan = $jurnal->penempatan;
            $allowed = $penempatan && $penempatan->guru_id === $user->guru->id;
        }

        if (!$allowed) {
            abort(403, 'Anda tidak memiliki akses ke dokumentasi ini.');
        }

        if (!$jurnal->dokumentasi) {
            abort(404);
        }

        $diskName = $jurnal->dokumentasi_disk ?: 'public';
        $disk = Storage::disk($diskName);

        if (!$disk->exists($jurnal->dokumentasi)) {
            abort(404);
        }

        return $disk->response($jurnal->dokumentasi);
    }
}
