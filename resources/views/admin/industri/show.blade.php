@extends('layouts.app')

@section('title', 'Detail Industri')

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.industri.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-building mr-2 text-indigo-500"></i> Detail Industri
        </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Perusahaan</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $industri->kode_perusahaan }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Perusahaan</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $industri->nama_perusahaan }}</p>
                </div>
                <div class="md:col-span-2">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</h3>
                    <p class="text-gray-700 dark:text-gray-300">{{ $industri->alamat }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">No Telepon</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $industri->no_telepon }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $industri->email ?? '-' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Bidang Usaha</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $industri->bidang_usaha }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Jurusan yang Dituju</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $industri->jurusan ?? '-' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Penanggung Jawab</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $industri->penanggung_jawab }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Kuota</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $industri->kuota }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                    @if($industri->status == 'aktif')
                        <span class="px-3 py-1 rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                            <i class="fas fa-circle text-xs mr-1"></i> Aktif
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300">
                            <i class="fas fa-circle text-xs mr-1"></i> Tidak Aktif
                        </span>
                    @endif
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $industri->created_at->format('d F Y H:i') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Diupdate</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $industri->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex gap-3">
            <a href="{{ route('admin.industri.edit', $industri) }}" class="btn-warning">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <form action="{{ route('admin.industri.destroy', $industri) }}" method="POST" onsubmit="return confirmDelete(event)">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <i class="fas fa-trash mr-2"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection