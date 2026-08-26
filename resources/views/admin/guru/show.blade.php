@extends('layouts.app')

@section('title', 'Detail Guru')

@section('content')
    <div class="animate-fadeIn">
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.guru.index') }}"
                class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-user-circle mr-2 text-indigo-500"></i> Detail Guru
            </h2>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="space-y-5">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">NIP</h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $guru->nip }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $guru->nama_guru }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $guru->user->email }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Password</h3>
                        <div class="flex items-center gap-2">
                            <p class="text-lg font-semibold text-gray-900 dark:text-white" id="password-display">
                                <span id="password-bullets">&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;</span>
                                <span id="password-text" style="display:none;">{{ $guru->user->getReadablePassword() ?? 'Tidak tersedia' }}</span>
                            </p>
                            <button type="button" onclick="togglePassword()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <i class="fas fa-eye" id="eye-icon"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">No Telepon</h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $guru->no_telepon ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $guru->alamat ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $guru->created_at->format('d F Y H:i') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Diupdate</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $guru->updated_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex gap-3">
                <a href="{{ route('admin.guru.edit', $guru) }}" class="btn-warning">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <form action="{{ route('admin.guru.destroy', $guru) }}" method="POST" onsubmit="return confirmDelete(event)">
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

@push('scripts')
<script>
function togglePassword() {
    const bullets = document.getElementById('password-bullets');
    const text = document.getElementById('password-text');
    const icon = document.getElementById('eye-icon');
    if (text.style.display === 'none') {
        text.style.display = 'inline';
        bullets.style.display = 'none';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        text.style.display = 'none';
        bullets.style.display = 'inline';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
