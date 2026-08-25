@extends('layouts.app')

@section('title', 'Detail Pengajuan PKL')
@section('page-title', 'Detail Pengajuan')
@section('page-subtitle', 'Verifikasi pengajuan tempat PKL')

@php
    $industriDataJs = [];
    foreach ($industris as $i) {
        $industriDataJs[] = [
            'id' => $i->id,
            'nama' => $i->nama_perusahaan,
            'kuota' => $i->kuota,
            'terpakai' => $i->penempatan->where('status', 'aktif')->count(),
        ];
    }
@endphp

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5>Detail Pengajuan - {{ $pengajuan->siswa->nama_siswa }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>NIS:</strong> {{ $pengajuan->siswa->nis }}</p>
                        <p><strong>Nama:</strong> {{ $pengajuan->siswa->nama_siswa }}</p>
                        <p><strong>Jurusan:</strong> {{ $pengajuan->jurusan }}</p>
                        <p><strong>Pilihan 1:</strong> {{ $pengajuan->pilihan_1 }}</p>
                        @if ($pengajuan->industri_1)
                            <p><strong>Industri Pilihan 1:</strong> {{ $pengajuan->industri_1 }}</p>
                        @endif
                        <p><strong>Pilihan 2:</strong> {{ $pengajuan->pilihan_2 }}</p>
                        @if ($pengajuan->industri_2)
                            <p><strong>Industri Pilihan 2:</strong> {{ $pengajuan->industri_2 }}</p>
                        @endif
                        @if ($pengajuan->pekerjaan_orang_tua)
                            <p><strong>Pekerjaan Orang Tua:</strong> {{ $pengajuan->pekerjaan_orang_tua }}</p>
                        @endif
                        <p><strong>Penghasilan Orang Tua:</strong> Rp
                            {{ number_format((int) str_replace('.', '', $pengajuan->penghasilan_ortu), 0, ',', '.') }}
                        </p>
                        @if ($pengajuan->alamat)
                            <p><strong>Alamat Rumah:</strong> {{ $pengajuan->alamat }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status Saat Ini:</strong>
                            @if ($pengajuan->status == 'pending')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @elseif($pengajuan->status == 'diterima')
                                <span class="badge bg-success">Diterima</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </p>
                        @if ($pengajuan->status == 'diterima')
                            <p><strong>Tempat Diterima:</strong> {{ $pengajuan->tempat_diterima }}</p>
                        @endif
                        @if ($pengajuan->catatan_admin)
                            <p><strong>Catatan Admin:</strong> {{ $pengajuan->catatan_admin }}</p>
                        @endif
                        <p><strong>Tanggal Pengajuan:</strong> {{ $pengajuan->created_at->format('d F Y H:i') }}</p>
                    </div>
                </div>

                <hr>

                <form action="{{ route('admin.pengajuan.update', $pengajuan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" id="statusSelect">
                                <option value="pending" {{ $pengajuan->status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="diterima" {{ $pengajuan->status == 'diterima' ? 'selected' : '' }}>Diterima
                                </option>
                                <option value="ditolak" {{ $pengajuan->status == 'ditolak' ? 'selected' : '' }}>Ditolak
                                </option>
                            </select>
                        </div>

                        <!-- TEMPAT DITERIMA (dinamis) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tempat Diterima <span class="text-danger">*</span></label>

                            <!-- Dropdown untuk status DITERIMA -->
                            <select name="tempat_diterima_select" class="form-select" id="tempatDiterimaSelect">
                                <option value="">Pilih Tempat</option>
                                <option value="{{ $pengajuan->pilihan_1 }}"
                                    {{ $pengajuan->tempat_diterima == $pengajuan->pilihan_1 ? 'selected' : '' }}>
                                    {{ $pengajuan->pilihan_1 }}
                                </option>
                                <option value="{{ $pengajuan->pilihan_2 }}"
                                    {{ $pengajuan->tempat_diterima == $pengajuan->pilihan_2 ? 'selected' : '' }}>
                                    {{ $pengajuan->pilihan_2 }}
                                </option>
                            </select>

                            <!-- Input text untuk status DITOLAK -->
                            <input type="text" name="tempat_diterima_input" class="form-control" id="tempatDiterimaInput"
                                value="{{ $pengajuan->tempat_diterima }}" placeholder="Masukkan tempat lain..."
                                style="display: none;">

                            <small class="text-muted" id="tempatDiterimaHint">
                                @if ($pengajuan->status == 'diterima')
                                    Harus sesuai dengan pilihan 1 atau pilihan 2
                                @elseif($pengajuan->status == 'ditolak')
                                    Bisa diketik manual
                                @else
                                    Pilih tempat setelah menentukan status
                                @endif
                            </small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Catatan Admin</label>
                            <textarea name="catatan_admin" class="form-control" rows="2" placeholder="Catatan untuk siswa">{{ $pengajuan->catatan_admin }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" {{ $pengajuan->penempatan_id ? 'disabled' : '' }}>
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>

                    @if ($pengajuan->penempatan_id)
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Status tidak dapat diubah karena sudah memiliki penempatan.
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- INFO PENEMPATAN - TAMPIL JIKA SUDAH ADA PENEMPATAN -->
        <!-- ============================================================ -->
        @if (!is_null($pengajuan->penempatan_id) && $pengajuan->penempatan)
            <hr>
            <div class="mt-4">
                <div class="alert alert-success">
                    <h5 class="alert-heading">
                        <i class="fas fa-check-circle me-2"></i> Sudah Ditempatkan
                    </h5>
                    <p class="mb-0">Siswa ini sudah memiliki penempatan PKL.</p>
                </div>

                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-building me-2"></i> Industri:</strong>
                                    {{ $pengajuan->penempatan->industri->nama_perusahaan ?? '-' }}
                                </p>
                                <p><strong><i class="fas fa-chalkboard-teacher me-2"></i> Guru Pembimbing:</strong>
                                    {{ $pengajuan->penempatan->guru->nama_guru ?? '-' }}
                                </p>
                                <p><strong><i class="fas fa-graduation-cap me-2"></i> Kompetensi:</strong>
                                    {{ $pengajuan->penempatan->kompetensi->nama_kompetensi ?? '-' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-calendar-alt me-2"></i> Tanggal Mulai:</strong>
                                    {{ \Carbon\Carbon::parse($pengajuan->penempatan->tanggal_mulai)->format('d/m/Y') }}
                                </p>
                                <p><strong><i class="fas fa-calendar-alt me-2"></i> Tanggal Selesai:</strong>
                                    {{ \Carbon\Carbon::parse($pengajuan->penempatan->tanggal_selesai)->format('d/m/Y') }}
                                </p>
                                <p><strong><i class="fas fa-info-circle me-2"></i> Status Penempatan:</strong>
                                    @if ($pengajuan->penempatan->status == 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($pengajuan->penempatan->status == 'selesai')
                                        <span class="badge bg-info">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Batal</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('admin.penempatan.show', $pengajuan->penempatan_id) }}"
                                class="btn btn-info btn-sm">
                                <i class="fas fa-eye me-1"></i> Lihat Detail Penempatan
                            </a>

                            <form action="{{ route('admin.penempatan.destroy', $pengajuan->penempatan_id) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus penempatan ini?\\nStatus pengajuan akan kembali ke Pending.');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="from_pengajuan" value="true">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash me-1"></i> Hapus Penempatan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- ============================================================ -->
        <!-- FORM PENEMPATAN - MUNCUL JIKA STATUS DITERIMA ATAU DITOLAK -->
        <!-- ============================================================ -->
        @if (($pengajuan->status == 'diterima' || $pengajuan->status == 'ditolak') && is_null($pengajuan->penempatan_id))
            <div class="mt-4">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i> Buat Penempatan PKL
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Pilih industri</strong> yang sesuai untuk penempatan PKL siswa ini.
                        </div>

                        <form id="formPenempatan" action="{{ route('admin.penempatan.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
                            <input type="hidden" name="siswa_id" value="{{ $pengajuan->siswa_id }}">

                            <div class="row">
                                <!-- INDUSTRI -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-building me-1"></i> Industri <span class="text-danger">*</span>
                                    </label>
                                    <select name="industri_id" class="form-select" id="industriSelect" required>
                                        <option value="">-- Pilih Industri --</option>
                                        @foreach ($industris as $industri)
                                            @php
                                                $penempatanAktif = $industri->penempatan
                                                    ->where('status', 'aktif')
                                                    ->count();
                                                $sisaKuota = $industri->kuota - $penempatanAktif;
                                            @endphp
                                            <option value="{{ $industri->id }}"
                                                {{ old('industri_id') == $industri->id ? 'selected' : '' }}
                                                data-sisa="{{ $sisaKuota }}">
                                                {{ $industri->nama_perusahaan }}
                                                (Kuota: {{ $industri->kuota }} | Sisa: {{ $sisaKuota }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i> Pilih industri yang akan menampung siswa
                                    </small>
                                    @error('industri_id')
                                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- GURU PEMBIMBING -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-chalkboard-teacher me-1"></i> Guru Pembimbing <span
                                            class="text-danger">*</span>
                                    </label>
                                    <select name="guru_id" class="form-select" required>
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach ($gurus as $guru)
                                            <option value="{{ $guru->id }}"
                                                {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                                {{ $guru->nama_guru }} ({{ $guru->nip }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('guru_id')
                                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- KOMPETENSI -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-tasks me-1"></i> Kompetensi <span class="text-danger">*</span>
                                    </label>
                                    <select name="kompetensi_id" class="form-select" required>
                                        <option value="">-- Pilih Kompetensi --</option>
                                        @foreach ($kompetensis as $kompetensi)
                                            <option value="{{ $kompetensi->id }}"
                                                {{ old('kompetensi_id') == $kompetensi->id ? 'selected' : '' }}>
                                                {{ $kompetensi->nama_kompetensi }} ({{ $kompetensi->kode_kompetensi }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kompetensi_id')
                                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- STATUS PENEMPATAN -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-info-circle me-1"></i> Status Penempatan
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        <input type="text" class="form-control bg-light" value="Aktif" disabled>
                                    </div>
                                    <input type="hidden" name="status" value="aktif">
                                </div>

                                <!-- TANGGAL MULAI -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-calendar-alt me-1"></i> Tanggal Mulai <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="tanggal_mulai" class="form-control"
                                        value="{{ old('tanggal_mulai') }}" required min="{{ date('Y-m-d') }}">
                                    @error('tanggal_mulai')
                                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- TANGGAL SELESAI -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-calendar-check me-1"></i> Tanggal Selesai <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="tanggal_selesai" class="form-control"
                                        value="{{ old('tanggal_selesai') }}" required>
                                    @error('tanggal_selesai')
                                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle me-2"></i> Simpan Penempatan
                                </button>
                                <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ============================================
            // 1. TOGGLE TEMPAT DITERIMA (Dropdown/Input) - untuk form pengajuan
            // ============================================
            const statusSelect = document.getElementById('statusSelect');
            const tempatSelect = document.getElementById('tempatDiterimaSelect');
            const tempatInput = document.getElementById('tempatDiterimaInput');
            const tempatHint = document.getElementById('tempatDiterimaHint');

            function toggleTempatDiterima() {
                const status = statusSelect.value;

                if (status === 'diterima') {
                    tempatSelect.style.display = 'block';
                    tempatInput.style.display = 'none';
                    tempatHint.textContent = 'Harus sesuai dengan pilihan 1 atau pilihan 2';
                    tempatSelect.required = true;
                    tempatInput.required = false;
                } else if (status === 'ditolak') {
                    tempatSelect.style.display = 'none';
                    tempatInput.style.display = 'block';
                    tempatHint.textContent = 'Bisa diketik manual';
                    tempatSelect.required = false;
                    tempatInput.required = false;
                } else {
                    tempatSelect.style.display = 'none';
                    tempatInput.style.display = 'none';
                    tempatSelect.required = false;
                    tempatInput.required = false;
                }
            }

            toggleTempatDiterima();
            statusSelect.addEventListener('change', toggleTempatDiterima);

            // ============================================
            // 2. DATA INDUSTRI & VALIDASI
            // ============================================
            const industriData = JSON.parse('{!! json_encode($industriDataJs) !!}');

            // ============================================
            // 3. TOGGLE TEMPAT DITERIMA (Dropdown/Input)
            // ============================================

            // ============================================
            // 4. FUNGSI ALERT
            // ============================================
            function showAlert(message, type) {
                type = type || 'info';
                let alertDiv = document.getElementById('customAlert');
                if (alertDiv) {
                    alertDiv.remove();
                }

                alertDiv = document.createElement('div');
                alertDiv.id = 'customAlert';
                alertDiv.className = 'alert alert-' + type + ' alert-dismissible fade show mt-2';
                alertDiv.role = 'alert';
                alertDiv.innerHTML = message +
                    ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';

                const fp = document.getElementById('formPenempatan');
                if (fp) {
                    fp.insertBefore(alertDiv, fp.firstChild);
                }

                setTimeout(function() {
                    if (alertDiv) {
                        alertDiv.remove();
                    }
                }, 5000);
            }

            // ============================================
            // 5. VALIDASI SEBELUM SUBMIT PENEMPATAN
            // ============================================
            const formPenempatan = document.getElementById('formPenempatan');
            if (formPenempatan) {
                formPenempatan.addEventListener('submit', function(e) {
                    const industriSelect = document.getElementById('industriSelect');
                    if (!industriSelect || !industriSelect.value) {
                        e.preventDefault();
                        showAlert('Silakan pilih industri untuk penempatan.', 'danger');
                        return false;
                    }

                    const found = industriData.find(function(item) {
                        return item.id == industriSelect.value;
                    });
                    if (found) {
                        const sisa = found.kuota - found.terpakai;
                        if (sisa <= 0) {
                            e.preventDefault();
                            showAlert('Kuota industri ini sudah penuh! Sisa kuota: 0', 'danger');
                            return false;
                        }
                    }
                });
            }
        });
    </script>
@endsection
