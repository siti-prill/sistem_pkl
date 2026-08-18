<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    protected array $footerFields = [
        'footer_deskripsi' => 'nullable|string|max:1000',
        'footer_email' => 'nullable|email|max:255',
        'footer_telepon' => 'nullable|string|max:50',
        'footer_alamat' => 'nullable|string|max:255',
        'footer_instagram' => 'nullable|string|max:255',
        'footer_youtube' => 'nullable|string|max:255',
        'footer_github' => 'nullable|string|max:255',
        'footer_copyright' => 'nullable|string|max:255',
    ];

    public function index()
    {
        $settings = [
            'logo_path' => setting('logo_path'),
            'footer_deskripsi' => setting('footer_deskripsi', 'Sistem Manajemen Praktik Kerja Lapangan terintegrasi untuk sekolah, siswa, dan industri.'),
            'footer_email' => setting('footer_email', 'support@sistem-pkl.com'),
            'footer_telepon' => setting('footer_telepon', '+62 812 3456 7890'),
            'footer_alamat' => setting('footer_alamat', ''),
            'footer_instagram' => setting('footer_instagram', ''),
            'footer_youtube' => setting('footer_youtube', ''),
            'footer_github' => setting('footer_github', ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        if ($request->hasFile('logo')) {
            $request->validate([
                'logo' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            $oldLogo = setting('logo_path');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('logo')->store('logos', 'public');
            Setting::set('logo_path', $path);
        }

        $request->validate($this->footerFields);

        foreach (array_keys($this->footerFields) as $field) {
            Setting::set($field, $request->input($field, ''));
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
