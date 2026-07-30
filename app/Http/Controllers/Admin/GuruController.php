<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuruRequest;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::with('user');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_guru', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('email', 'like', "%{$search}%");
                    });
            });
        }

        $gurus = $query->paginate(10);
        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(GuruRequest $request)
    {
        // Create User
        $user = User::create([
            'name' => $request->nama_guru,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru',
        ]);

        // Create Guru
        Guru::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'nama_guru' => $request->nama_guru,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function show(Guru $guru)
    {
        return view('admin.guru.show', compact('guru'));
    }

    public function edit(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(GuruRequest $request, Guru $guru)
    {
        // Ambil data yang sudah divalidasi
        $data = $request->validated();

        // Update User (email & password)
        $userData = [
            'name' => $data['nama_guru'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        $guru->user()->update($userData);

        // Update Guru
        $guru->update([
            'nip' => $data['nip'],
            'nama_guru' => $data['nama_guru'],
            'no_telepon' => $data['no_telepon'] ?? null,
            'alamat' => $data['alamat'] ?? null,
        ]);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diupdate.');
    }

    public function destroy(Guru $guru)
    {
        $guru->user->delete(); // Cascade akan menghapus guru juga
        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
