@extends('layouts.app')

@section('title', 'Detail Template Penilaian')

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.template-penilaian.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-info-circle mr-2 text-indigo-500"></i> Detail Aspek Penilaian
        </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 max-w-2xl">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</label>
                <p>
                    @if($template->kategori === 'kejuruan')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300">A. Aspek Kejuruan</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300">B. Aspek Sikap</span>
                    @endif
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tipe</label>
                <p>
                    @if($template->tipe === 'komponen')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">Komponen</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Item</span>
                    @endif
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                <p>
                    @if($template->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Aktif</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Nonaktif</span>
                    @endif
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Aspek</label>
                <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $template->nama_aspek }}</p>
            </div>

            @if($template->parent)
            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Induk</label>
                <p class="text-gray-800 dark:text-white">{{ $template->parent->nama_aspek }}</p>
            </div>
            @endif

            @if($template->deskripsi)
            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</label>
                <p class="text-gray-800 dark:text-white">{{ $template->deskripsi }}</p>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jurusan</label>
                <p class="text-gray-800 dark:text-white">
                    <span class="text-xs px-2 py-1 rounded {{ $template->jurusan ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-600 dark:text-gray-400' }}">
                        {{ $template->jurusan ?? 'Umum (Semua Jurusan)' }}
                    </span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Rentang Nilai</label>
                <p class="text-gray-800 dark:text-white font-semibold">{{ $template->rentang_nilai_min }} - {{ $template->rentang_nilai_max }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Urutan</label>
                <p class="text-gray-800 dark:text-white font-semibold">{{ $template->urutan }}</p>
            </div>

            @if($template->tipe === 'komponen' && $template->children->count())
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Sub-Items</label>
                <ul class="list-disc list-inside text-gray-800 dark:text-white space-y-1">
                    @foreach($template->children as $child)
                        <li>{{ $child->nama_aspek }} <span class="text-gray-400 text-sm">({{ $child->rentang_nilai_min }}-{{ $child->rentang_nilai_max }})</span></li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.template-penilaian.edit', $template) }}" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 px-4 rounded-lg transition text-center">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <a href="{{ route('admin.template-penilaian.index') }}" class="flex-1 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 rounded-lg transition text-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
