@extends('layouts.app')

@section('title', 'Edit Template Penilaian')

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.template-penilaian.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-edit mr-2 text-indigo-500"></i> Edit Aspek Penilaian
        </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 max-w-2xl">
        <form action="{{ route('admin.template-penilaian.update', $template) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori" class="form-input" required>
                            <option value="kejuruan" {{ old('kategori', $template->kategori) == 'kejuruan' ? 'selected' : '' }}>A. Aspek Kejuruan</option>
                            <option value="sikap" {{ old('kategori', $template->kategori) == 'sikap' ? 'selected' : '' }}>B. Aspek Sikap</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tipe <span class="text-red-500">*</span>
                        </label>
                        <select name="tipe" id="tipeSelect" class="form-input" required>
                            <option value="komponen" {{ old('tipe', $template->tipe) == 'komponen' ? 'selected' : '' }}>Komponen (Header)</option>
                            <option value="item" {{ old('tipe', $template->tipe) == 'item' ? 'selected' : '' }}>Item (Diisi Nilai)</option>
                        </select>
                    </div>
                </div>

                <div id="parentField">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Komponen Induk
                    </label>
                    <select name="parent_id" class="form-input">
                        <option value="">-- Tidak ada induk (root) --</option>
                        @foreach($komponens as $k)
                            <option value="{{ $k->id }}" {{ old('parent_id', $template->parent_id) == $k->id ? 'selected' : '' }}>
                                [{{ strtoupper($k->kategori) }}] {{ $k->nama_aspek }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nama Aspek <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_aspek" value="{{ old('nama_aspek', $template->nama_aspek) }}"
                        class="form-input" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="2" class="form-input">{{ old('deskripsi', $template->deskripsi) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Instruksi Pengisian</label>
                    <textarea name="instruksi" rows="2" class="form-input">{{ old('instruksi', $template->instruksi) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Rentang Nilai Min <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="rentang_nilai_min" value="{{ old('rentang_nilai_min', $template->rentang_nilai_min) }}"
                            class="form-input" min="0" max="100" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Rentang Nilai Max <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="rentang_nilai_max" value="{{ old('rentang_nilai_max', $template->rentang_nilai_max) }}"
                            class="form-input" min="0" max="100" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Urutan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="urutan" value="{{ old('urutan', $template->urutan) }}"
                            class="form-input" min="0" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="is_active" class="form-input">
                            <option value="1" {{ old('is_active', $template->is_active) ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active', $template->is_active) ? '' : 'selected' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i> Update
                    </button>
                    <a href="{{ route('admin.template-penilaian.index') }}" class="btn-danger flex-1 text-center">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('tipeSelect').addEventListener('change', function() {
    document.getElementById('parentField').style.display = this.value === 'item' ? 'block' : 'none';
});
document.getElementById('parentField').style.display = document.getElementById('tipeSelect').value === 'item' ? 'block' : 'none';
</script>
@endsection
