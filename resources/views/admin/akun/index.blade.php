@extends('layouts.app')

@section('title', 'Data Akun')

@section('content')
<div class="animate-fadeIn">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-users-gear mr-2 text-indigo-500"></i> Data Akun
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Lihat password semua akun (semua role). Password disimpan dalam salinan terenkripsi.
            </p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('admin.akun.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama / email..." class="form-input pl-10">
            </div>
            <div>
                <select name="role" class="form-input">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="industri" {{ request('role') == 'industri' ? 'selected' : '' }}>Industri</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1">
                    <i class="fas fa-search"></i>
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.akun.index') }}" class="btn-danger">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
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
                        <th class="table-header">Nama</th>
                        <th class="table-header">Email</th>
                        <th class="table-header">Role</th>
                        <th class="table-header">Password</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $index => $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="table-cell">{{ $index + 1 }}</td>
                            <td class="table-cell font-medium">{{ $user->name }}</td>
                            <td class="table-cell">{{ $user->email }}</td>
                            <td class="table-cell">
                                @php
                                    $badges = [
                                        'admin' => 'bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300',
                                        'guru' => 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300',
                                        'siswa' => 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300',
                                        'industri' => 'bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300',
                                    ];
                                    $icons = [
                                        'admin' => 'fa-user-shield',
                                        'guru' => 'fa-chalkboard-teacher',
                                        'siswa' => 'fa-user-graduate',
                                        'industri' => 'fa-building',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $badges[$user->role] ?? 'bg-gray-100 text-gray-600' }}">
                                    <i class="fas {{ $icons[$user->role] ?? 'fa-user' }} mr-1"></i> {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="table-cell">
                                <div class="flex items-center gap-2">
                                    <span id="pw-display-{{ $user->id }}"
                                          class="font-mono text-sm tracking-wider select-all"
                                          data-shown="0">{{ str_repeat('&bull;', 8) }}</span>
                                    <button type="button"
                                            class="btn-info btn-sm pw-toggle"
                                            data-user-id="{{ $user->id }}"
                                            title="Lihat / sembunyikan password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                Tidak ada data akun
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400">
            <i class="fas fa-circle-info mr-1"></i>
            Akun dengan password lama yang dibuat sebelum fitur ini ada akan tampil <b>"Tidak tersedia"</b>.
            Password baru otomatis tersimpan salinannya.
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.pw-toggle').forEach(function (btn) {
        let loaded = false;
        let visible = false;

        btn.addEventListener('click', async function () {
            const userId = btn.dataset.userId;
            const display = document.getElementById('pw-display-' + userId);
            const icon = btn.querySelector('i');

            if (!loaded) {
                btn.disabled = true;
                try {
                    const res = await fetch('{{ url("admin/akun") }}/' + userId + '/password');
                    const data = await res.json();
                    display.dataset.password = data.password || '';
                    loaded = true;
                } catch (e) {
                    display.textContent = 'Gagal memuat';
                    btn.disabled = false;
                    return;
                }
                btn.disabled = false;
            }

            visible = !visible;
            if (visible) {
                if (!display.dataset.password) {
                    display.textContent = 'Tidak tersedia';
                } else {
                    display.textContent = display.dataset.password;
                }
                display.classList.remove('tracking-wider');
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                if (display.dataset.password) {
                    display.innerHTML = '\u2022\u2022\u2022\u2022\u2022\u2022\u2022\u2022';
                }
                display.classList.add('tracking-wider');
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
</script>
@endpush
@endsection
