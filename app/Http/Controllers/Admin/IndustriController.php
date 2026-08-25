<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndustriRequest;
use App\Models\Industri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class IndustriController extends Controller
{
    public function index(Request $request)
    {
        $query = Industri::with('user');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_perusahaan', 'like', "%{$search}%")
                    ->orWhere('nama_perusahaan', 'like', "%{$search}%")
                    ->orWhere('bidang_usaha', 'like', "%{$search}%")
                    ->orWhere('penanggung_jawab', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter Jurusan
        if ($request->has('jurusan') && $request->jurusan != '') {
            $query->where('jurusan', $request->jurusan);
        }

        $industris = $query->orderBy('nama_perusahaan')->get();

        $urutan = array_flip(Industri::JURUSAN_LIST);
        $grupIndustri = $industris
            ->groupBy(fn ($i) => $i->jurusan ?: 'Lainnya')
            ->sortBy(fn ($group, $key) => $urutan[$key] ?? 999);

        return view('admin.industri.index', compact('industris', 'grupIndustri'));
    }

    public function create()
    {
        return view('admin.industri.create');
    }

    public function store(IndustriRequest $request)
    {
        // Create User
        $user = User::create([
            'name' => $request->nama_perusahaan,
            'email' => $request->email_login,
            'password' => Hash::make($request->password),
            'role' => 'industri',
        ]);

        $user->setPasswordCopy($request->password);

        // Create Industri
        Industri::create([
            'user_id' => $user->id,
            'kode_perusahaan' => $request->kode_perusahaan,
            'nama_perusahaan' => $request->nama_perusahaan,
            'lokasi' => $request->lokasi,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
            'bidang_usaha' => $request->bidang_usaha,
            'jurusan' => $request->jurusan,
            'penanggung_jawab' => $request->penanggung_jawab,
            'kuota' => $request->kuota,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.industri.index')
            ->with('success', 'Data industri berhasil ditambahkan.');
    }

    public function show(Industri $industri)
    {
        $industri->load('user');
        return view('admin.industri.show', compact('industri'));
    }

    public function edit(Industri $industri)
    {
        return view('admin.industri.edit', compact('industri'));
    }

    public function update(IndustriRequest $request, Industri $industri)
    {
        $data = $request->validated();

        // Update User (email login & password)
        $userData = [
            'name' => $data['nama_perusahaan'],
            'email' => $data['email_login'],
        ];

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        $industri->user()->update($userData);

        if (!empty($data['password'])) {
            $industri->user->setPasswordCopy($data['password']);
        }

        // Update Industri
        $industri->update([
            'kode_perusahaan' => $data['kode_perusahaan'],
            'nama_perusahaan' => $data['nama_perusahaan'],
            'lokasi' => $data['lokasi'],
            'alamat' => $data['alamat'],
            'no_telepon' => $data['no_telepon'],
            'email' => $data['email'] ?? null,
            'bidang_usaha' => $data['bidang_usaha'],
            'jurusan' => $data['jurusan'] ?? null,
            'penanggung_jawab' => $data['penanggung_jawab'],
            'kuota' => $data['kuota'],
            'status' => $data['status'],
        ]);

        return redirect()->route('admin.industri.index')
            ->with('success', 'Data industri berhasil diupdate.');
    }

    public function destroy(Industri $industri)
    {
        $industri->user->delete();
        return redirect()->route('admin.industri.index')
            ->with('success', 'Data industri berhasil dihapus.');
    }
}
