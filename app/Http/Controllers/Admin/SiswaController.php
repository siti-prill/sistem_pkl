<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiswaRequest;
use App\Models\Kompetensi;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('user');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('jurusan', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter Jurusan
        if ($request->has('jurusan') && $request->jurusan != '') {
            $query->where('jurusan', $request->jurusan);
        }

        $siswas = $query->paginate(10);
        $jurusanList = Siswa::select('jurusan')->distinct()->pluck('jurusan');

        return view('admin.siswa.index', compact('siswas', 'jurusanList'));
    }

    public function create()
    {
        $jurusanList = [
            'XII RPL',
            'XII TKJ 1',
            'XII TKJ 2',
            'XII DKV 1',
            'XII DKV 2',
            'XII PSPT',
        ];
        return view('admin.siswa.create', compact('jurusanList'));
    }

    public function store(SiswaRequest $request)
    {
        // Create User
        $user = User::create([
            'name' => $request->nama_siswa,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);

        // Create Siswa
        Siswa::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'nama_siswa' => $request->nama_siswa,
            'jurusan' => $request->jurusan,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $jurusanList = [
            'XII RPL',
            'XII TKJ 1',
            'XII TKJ 2',
            'XII DKV 1',
            'XII DKV 2',
            'XII PSPT',
        ];
        return view('admin.siswa.edit', compact('siswa', 'jurusanList'));
    }

    public function update(SiswaRequest $request, Siswa $siswa)
    {
        $data = $request->validated();

        // Update User
        $userData = [
            'name' => $data['nama_siswa'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        $siswa->user()->update($userData);

        // Update Siswa
        $siswa->update([
            'nis' => $data['nis'],
            'nama_siswa' => $data['nama_siswa'],
            'jurusan' => $data['jurusan'],
            'no_telepon' => $data['no_telepon'] ?? null,
            'alamat' => $data['alamat'] ?? null,
        ]);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diupdate.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->user->delete();
        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}
