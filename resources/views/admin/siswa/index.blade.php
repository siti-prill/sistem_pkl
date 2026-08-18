@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
    <div class="animate-fadeIn">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    <i class="fas fa-user-graduate mr-2 text-indigo-500"></i> Data Siswa
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data siswa PKL</p>
            </div>
            <a href="{{ route('admin.siswa.create') }}" class="btn-primary mt-3 sm:mt-0">
                <i class="fas fa-plus mr-2"></i> Tambah Siswa
            </a>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
            <form method="GET" action="{{ route('admin.siswa.index') }}"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari siswa..."
                        class="form-input pl-10">
                </div>
                <div>
                    <select name="jurusan" class="form-input">
                        <option value="">Semua Jurusan</option>
                        @foreach ($jurusanList as $jurusan)
                            <option value="{{ $jurusan }}" {{ request('jurusan') == $jurusan ? 'selected' : '' }}>
                                {{ $jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center">
                    <button type="submit" class="btn-primary w-full">
                        <i class="fas fa-search mr-2"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-container">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="table-header">No</th>
                            <th class="table-header">NIS</th>
                            <th class="table-header">Nama Siswa</th>
                            <th class="table-header">Jurusan</th>
                            <th class="table-header">Email</th>
                            <th class="table-header text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($siswas as $index => $siswa)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="table-cell">{{ $siswas->firstItem() + $index }}</td>
                                <td class="table-cell font-medium">{{ $siswa->nis }}</td>
                                <td class="table-cell">{{ $siswa->nama_siswa }}</td>
                                <td class="table-cell">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300">
                                        {{ $siswa->jurusan }}
                                    </span>
                                </td>
                                <td class="table-cell">{{ $siswa->user->email }}</td>
                                <td class="table-cell text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.siswa.show', $siswa) }}" class="btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.siswa.edit', $siswa) }}" class="btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.siswa.destroy', $siswa) }}" method="POST"
                                            class="inline" onsubmit="return confirmDelete(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                    Tidak ada data siswa
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $siswas->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
