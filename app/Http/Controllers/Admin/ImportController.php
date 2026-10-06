<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\GuruImport;
use App\Imports\IndustriImport;
use App\Imports\KompetensiImport;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    protected array $templates = [
        'guru' => ['nip', 'nama_guru', 'no_telepon', 'alamat', 'email', 'password'],
        'siswa' => ['nis', 'nama_siswa', 'jurusan', 'no_telepon', 'alamat', 'email', 'password'],
        'kompetensi' => ['kode_kompetensi', 'nama_kompetensi', 'kategori', 'deskripsi'],
        'industri' => ['kode_perusahaan', 'nama_perusahaan', 'lokasi', 'alamat', 'no_telepon', 'email', 'bidang_usaha', 'kategori', 'penanggung_jawab', 'kuota', 'status', 'email_login', 'password'],
    ];

    public function template(string $type)
    {
        abort_unless(isset($this->templates[$type]), 404);
        return Excel::download(new TemplateExport($this->templates[$type]), "template_{$type}.xlsx");
    }

    public function importGuru(Request $request)
    {
        return $this->handleImport($request, new GuruImport(), 'admin.guru.index');
    }

    public function importSiswa(Request $request)
    {
        return $this->handleImport($request, new SiswaImport(), 'admin.siswa.index');
    }

    public function importKompetensi(Request $request)
    {
        return $this->handleImport($request, new KompetensiImport(), 'admin.kompetensi.index');
    }

    public function importIndustri(Request $request)
    {
        return $this->handleImport($request, new IndustriImport(), 'admin.industri.index');
    }

    protected function handleImport(Request $request, $import, string $redirect)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        Excel::import($import, $request->file('file'));

        return redirect()->route($redirect)
            ->with('success', "Import selesai. Berhasil: {$import->success}, Dilewati: {$import->skipped}.");
    }
}
