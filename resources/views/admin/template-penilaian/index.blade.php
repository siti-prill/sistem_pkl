@extends('layouts.app')

@section('title', 'Template Penilaian')

@section('content')
<div class="animate-fadeIn">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-clipboard-list mr-2 text-indigo-500"></i> Template Penilaian
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Klik nama aspek untuk mengubahnya secara langsung</p>
        </div>
        <div class="flex gap-2 mt-3 sm:mt-0">
            <a href="{{ route('admin.template-penilaian.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i> Tambah Aspek
            </a>
        </div>
    </div>

    <!-- Filter Jurusan -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
        <div class="flex items-center gap-3 flex-wrap">
            <span class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                <i class="fas fa-filter mr-1"></i> Filter Jurusan:
            </span>
            <a href="{{ route('admin.template-penilaian.index') }}"
               class="text-xs px-3 py-1.5 rounded-full font-medium transition {{ !$jurusan ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/30' }}">
                Semua
            </a>
            @foreach($jurusanList as $j)
                <a href="{{ route('admin.template-penilaian.index', ['jurusan' => $j]) }}"
                   class="text-xs px-3 py-1.5 rounded-full font-medium transition {{ $jurusan === $j ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/30' }}">
                    {{ $j }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Info -->
    @php
        $totalKomponen = $templates->where('tipe', 'komponen')->count();
        $totalItem = $templates->where('tipe', 'item')->count();
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center shrink-0">
                <i class="fas fa-layer-group text-indigo-600 dark:text-indigo-300"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Komponen</p>
                <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $totalKomponen }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900 flex items-center justify-center shrink-0">
                <i class="fas fa-list-check text-blue-600 dark:text-blue-300"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Item</p>
                <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $totalItem }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900 flex items-center justify-center shrink-0">
                <i class="fas fa-check-circle text-green-600 dark:text-green-300"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Aktif</p>
                <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $aspekAktif }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900 flex items-center justify-center shrink-0">
                <i class="fas fa-cubes text-amber-600 dark:text-amber-300"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Kejuruan + Sikap</p>
                <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $kejuruanRoot->count() + $sikapItems->count() }}</p>
            </div>
        </div>
    </div>

    <!-- A. Aspek Kejuruan -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4 border-b pb-2">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">A. Aspek Kejuruan</h3>
            <button onclick="showAddModal('kejuruan')" class="btn-success btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Item
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-12 text-center">No</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-left">Komponen Kompetensi Kejuruan</th>
                        @if(!$jurusan)
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-32 text-center">Jurusan</th>
                        @endif
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-16 text-center">Status</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-20 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse($kejuruanRoot as $komponen)
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center font-semibold">{{ $no++ }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 font-semibold">
                                <span class="inline-edit cursor-pointer hover:bg-yellow-100 dark:hover:bg-yellow-900/30 px-2 py-1 rounded"
                                      data-id="{{ $komponen->id }}" data-field="nama_aspek"
                                      onclick="startEdit(this)">{{ $komponen->nama_aspek }}</span>
                            </td>
                            @if(!$jurusan)
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                    <span class="text-xs px-2 py-1 rounded {{ $komponen->jurusan ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-600 dark:text-gray-400' }}">
                                        {{ $komponen->jurusan ?? 'Umum' }}
                                    </span>
                                </td>
                            @endif
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                <form action="{{ route('admin.template-penilaian.toggle-active', $komponen) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs px-2 py-1 rounded {{ $komponen->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $komponen->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                <div class="flex justify-center gap-1">
                                    <button onclick="showAddSubModal({{ $komponen->id }}, '{{ addslashes($komponen->nama_aspek) }}')" class="text-blue-500 hover:text-blue-700 text-xs" title="Tambah Sub-Item">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <a href="{{ route('admin.template-penilaian.edit', $komponen) }}" class="text-yellow-500 hover:text-yellow-700 text-xs" title="Edit Detail">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @foreach($komponen->children->sortBy('urutan') as $child)
                            <tr>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center"></td>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 pl-8">
                                    <span class="inline-edit cursor-pointer hover:bg-yellow-100 dark:hover:bg-yellow-900/30 px-2 py-1 rounded"
                                          data-id="{{ $child->id }}" data-field="nama_aspek"
                                          onclick="startEdit(this)">{{ $child->nama_aspek }}</span>
                                </td>
                                @if(!$jurusan)
                                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                        <span class="text-xs px-2 py-1 rounded {{ $child->jurusan ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-600 dark:text-gray-400' }}">
                                            {{ $child->jurusan ?? 'Umum' }}
                                        </span>
                                    </td>
                                @endif
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                    <form action="{{ route('admin.template-penilaian.toggle-active', $child) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs px-2 py-1 rounded {{ $child->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $child->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                    <div class="flex justify-center gap-1">
                                        <a href="{{ route('admin.template-penilaian.edit', $child) }}" class="text-yellow-500 hover:text-yellow-700 text-xs" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.template-penilaian.destroy', $child) }}" method="POST" class="inline" onsubmit="return confirm('Hapus aspek ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="{{ !$jurusan ? 5 : 4 }}" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                Belum ada komponen kejuruan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- B. Aspek Sikap -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4 border-b pb-2">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">B. Aspek Sikap</h3>
            <button onclick="showAddModal('sikap')" class="btn-success btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Item
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-12 text-center">No</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-left">Komponen Sikap</th>
                        @if(!$jurusan)
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-32 text-center">Jurusan</th>
                        @endif
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-16 text-center">Status</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-20 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse($sikapItems as $item)
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">{{ $no++ }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2">
                                <span class="inline-edit cursor-pointer hover:bg-yellow-100 dark:hover:bg-yellow-900/30 px-2 py-1 rounded"
                                      data-id="{{ $item->id }}" data-field="nama_aspek"
                                      onclick="startEdit(this)">{{ $item->nama_aspek }}</span>
                            </td>
                            @if(!$jurusan)
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                    <span class="text-xs px-2 py-1 rounded {{ $item->jurusan ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-600 dark:text-gray-400' }}">
                                        {{ $item->jurusan ?? 'Umum' }}
                                    </span>
                                </td>
                            @endif
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                <form action="{{ route('admin.template-penilaian.toggle-active', $item) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs px-2 py-1 rounded {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                <div class="flex justify-center gap-1">
                                    <a href="{{ route('admin.template-penilaian.edit', $item) }}" class="text-yellow-500 hover:text-yellow-700 text-xs" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.template-penilaian.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus aspek ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ !$jurusan ? 5 : 4 }}" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                Belum ada aspek sikap
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-2">Keterangan Nilai Angka dan Huruf (Otomatis)</h3>
        <table class="text-sm border border-gray-300 dark:border-gray-600">
            <tr>
                <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">90 – 100</td>
                <td class="border border-gray-300 dark:border-gray-600 px-3 py-1 font-semibold">A</td>
                <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">( Sangat Kompeten )</td>
            </tr>
            <tr>
                <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">80 – 89</td>
                <td class="border border-gray-300 dark:border-gray-600 px-3 py-1 font-semibold">B</td>
                <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">( Kompeten )</td>
            </tr>
            <tr>
                <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">70 – 79</td>
                <td class="border border-gray-300 dark:border-gray-600 px-3 py-1 font-semibold">C</td>
                <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">( Cukup Kompeten )</td>
            </tr>
            <tr>
                <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">&lt; 70</td>
                <td class="border border-gray-300 dark:border-gray-600 px-3 py-1 font-semibold">D</td>
                <td class="border border-gray-300 dark:border-gray-600 px-3 py-1">( Kurang Kompeten )</td>
            </tr>
        </table>
    </div>
</div>

<!-- Modal Tambah Item Baru -->
<div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4" id="addModalTitle">Tambah Aspek Baru</h3>
        <input type="hidden" id="addModalKategori">
        <input type="hidden" id="addModalParentId">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Aspek</label>
            <input type="text" id="addModalInput" class="form-input w-full" placeholder="Contoh: Kompetensi Dasar 1">
        </div>
        <div class="mb-4" id="addModalJurusanWrapper">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jurusan</label>
            <select id="addModalJurusan" class="form-input w-full">
                <option value="">Umum (Semua Jurusan)</option>
                @foreach($jurusanList as $j)
                    <option value="{{ $j }}">{{ $j }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-3">
            <button onclick="submitAdd()" class="btn-primary flex-1">Simpan</button>
            <button onclick="closeAddModal()" class="btn-danger flex-1">Batal</button>
        </div>
    </div>
</div>

<script>
function startEdit(el) {
    const id = el.dataset.id;
    const currentText = el.textContent.trim();
    const input = document.createElement('input');
    input.type = 'text';
    input.value = currentText;
    input.className = 'w-full border border-indigo-400 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-indigo-500';
    input.onblur = function() { saveEdit(el, id, this); };
    input.onkeydown = function(e) {
        if (e.key === 'Enter') this.blur();
        if (e.key === 'Escape') { el.textContent = currentText; el.style.display = ''; }
    };
    el.style.display = 'none';
    el.parentNode.insertBefore(input, el);
    input.focus();
    input.select();
}

function saveEdit(el, id, input) {
    const newVal = input.value.trim();
    input.remove();
    if (newVal === '' || newVal === el.textContent.trim()) {
        el.style.display = '';
        return;
    }
    fetch(`/admin/template-penilaian/${id}/inline`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ nama_aspek: newVal })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            el.textContent = data.nama_aspek;
            showToast('Berhasil diupdate!');
        }
    })
    .catch(() => { showToast('Gagal update', 'error'); });
    el.style.display = '';
}

function showAddModal(kategori) {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('addModal').classList.add('flex');
    document.getElementById('addModalKategori').value = kategori;
    document.getElementById('addModalParentId').value = '';
    document.getElementById('addModalTitle').textContent = kategori === 'kejuruan' ? 'Tambah Komponen Kejuruan Baru' : 'Tambah Aspek Sikap Baru';
    document.getElementById('addModalInput').value = '';
    document.getElementById('addModalJurusan').value = '';
    document.getElementById('addModalJurusanWrapper').classList.remove('hidden');
    document.getElementById('addModalInput').focus();
}

function showAddSubModal(parentId, parentName) {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('addModal').classList.add('flex');
    document.getElementById('addModalParentId').value = parentId;
    document.getElementById('addModalTitle').textContent = 'Tambah Sub-Item: ' + parentName;
    document.getElementById('addModalInput').value = '';
    document.getElementById('addModalJurusanWrapper').classList.add('hidden');
    document.getElementById('addModalInput').focus();
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.getElementById('addModal').classList.remove('flex');
}

function submitAdd() {
    const name = document.getElementById('addModalInput').value.trim();
    if (!name) return;
    const parentId = document.getElementById('addModalParentId').value;
    const kategori = document.getElementById('addModalKategori').value;
    const jurusan = document.getElementById('addModalJurusan').value;

    let url, body;
    if (parentId) {
        url = '/admin/template-penilaian/add-sub-item';
        body = { parent_id: parentId, nama_aspek: name };
    } else {
        url = '/admin/template-penilaian/add-item';
        body = { kategori: kategori, nama_aspek: name, jurusan: jurusan };
    }

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify(body)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { location.reload(); }
    })
    .catch(() => showToast('Gagal menambahkan', 'error'));
}

function showToast(msg, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg text-white text-sm font-medium shadow-lg ${type === 'error' ? 'bg-red-500' : 'bg-green-500'}`;
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}
</script>
@endsection
