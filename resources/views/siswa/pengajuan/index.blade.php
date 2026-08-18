@extends('layouts.app')

@section('title', 'Status Pengajuan PKL')
@section('page-title', 'Pengajuan PKL')
@section('page-subtitle', 'Status pengajuan tempat PKL Anda')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($pengajuan)
            <div class="card">
                <div class="card-header">
                    <h5>Detail Pengajuan PKL</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Pilihan 1:</strong> {{ $pengajuan->pilihan_1 }}</p>
                            @if ($pengajuan->industri_1)
                                <p><strong>Industri Pilihan 1:</strong> {{ $pengajuan->industri_1 }}</p>
                            @endif
                            <p><strong>Pilihan 2:</strong> {{ $pengajuan->pilihan_2 }}</p>
                            @if ($pengajuan->industri_2)
                                <p><strong>Industri Pilihan 2:</strong> {{ $pengajuan->industri_2 }}</p>
                            @endif
                            <p><strong>Jurusan:</strong> {{ $pengajuan->jurusan }}</p>
                            <div class="col-md-6">
                                @if ($pengajuan->pekerjaan_orang_tua)
                                    <p><strong>Pekerjaan Orang Tua:</strong> {{ $pengajuan->pekerjaan_orang_tua }}</p>
                                @endif
                                <p><strong>Penghasilan Orang Tua:</strong> {{ $pengajuan->penghasilan_ortu ?? '-' }}</p>
                                @if ($pengajuan->alamat)
                                    <p><strong>Alamat Rumah:</strong> {{ $pengajuan->alamat }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong>
                                @if ($pengajuan->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
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

                    <!-- ============================================================ -->
                    <!-- STATUS PENDING -->
                    <!-- ============================================================ -->
                    @if ($pengajuan->status == 'pending')
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Pengajuan kamu sedang menunggu verifikasi admin.</strong>
                            <ul class="mb-0 mt-2">
                                <li>Status ini akan diperbarui setelah admin memeriksa pengajuan kamu.</li>
                                <li>Silakan cek halaman ini secara berkala untuk melihat status terbaru.</li>
                                <li>Kalau sudah lama belum dikonfirmasi (lebih dari 3 hari), hubungi guru pembimbing atau
                                    admin sekolah.</li>
                            </ul>
                        </div>
                    @endif

                    <!-- ============================================================ -->
                    <!-- STATUS DITERIMA -->
                    <!-- ============================================================ -->
                    @if ($pengajuan->status == 'diterima')
                        @if (!is_null($pengajuan->penempatan_id) && $pengajuan->penempatan)
                            <div class="alert alert-success mt-3 mb-0">
                                <h5 class="alert-heading">
                                    <i class="fas fa-check-circle me-2"></i> Selamat! Anda Diterima
                                </h5>
                                <p class="mb-0">Anda diterima melaksanakan PKL di perusahaan berikut:</p>
                            </div>

                            <div class="card bg-light mt-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-building me-2"></i> Perusahaan:</strong></p>
                                            <p class="h5 text-primary">
                                                {{ $pengajuan->penempatan->industri->nama_perusahaan ?? '-' }}</p>
                                            <p><strong><i class="fas fa-map-marker-alt me-2"></i> Lokasi:</strong>
                                                {{ $pengajuan->penempatan->industri->lokasi ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-calendar-alt me-2"></i> Periode PKL:</strong></p>
                                            <p class="h5 text-primary">
                                                {{ \Carbon\Carbon::parse($pengajuan->penempatan->tanggal_mulai)->format('d F Y') }}
                                                <i class="fas fa-arrow-right mx-2"></i>
                                                {{ \Carbon\Carbon::parse($pengajuan->penempatan->tanggal_selesai)->format('d F Y') }}
                                            </p>
                                            <p><strong><i class="fas fa-clock me-2"></i> Lama PKL:</strong>
                                                {{ \Carbon\Carbon::parse($pengajuan->penempatan->tanggal_mulai)->diffInDays($pengajuan->penempatan->tanggal_selesai) + 1 }}
                                                Hari
                                            </p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-chalkboard-teacher me-2"></i> Guru
                                                    Pembimbing:</strong>
                                                {{ $pengajuan->penempatan->guru->nama_guru ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-tasks me-2"></i> Kompetensi:</strong>
                                                {{ $pengajuan->penempatan->kompetensi->nama_kompetensi ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mt-3 mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Informasi Tambahan:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Silakan hubungi guru pembimbing Anda untuk informasi lebih lanjut.</li>
                                    <li>Pastikan Anda datang tepat waktu pada tanggal mulai PKL.</li>
                                    <li>Bawa surat pengantar dari sekolah pada hari pertama PKL.</li>
                                </ul>
                            </div>
                        @endif
                    @endif

                    <!-- ============================================================ -->
                    <!-- STATUS DITOLAK -->
                    <!-- ============================================================ -->
                    @if ($pengajuan->status == 'ditolak')
                        @if (!is_null($pengajuan->penempatan_id) && $pengajuan->penempatan)
                            <!-- DITOLAK TAPI DITEMPATKAN -->
                            <div class="alert alert-warning mt-3 mb-0">
                                <h5 class="alert-heading">
                                    <i class="fas fa-info-circle me-2"></i> Informasi Penting
                                </h5>
                                <p class="mb-0">Pengajuan Anda ditolak oleh admin, namun Anda telah ditempatkan di
                                    perusahaan berikut:</p>
                            </div>

                            @if ($pengajuan->catatan_admin)
                                <div class="alert alert-danger mt-2">
                                    <strong><i class="fas fa-exclamation-circle me-2"></i> Alasan Penolakan:</strong>
                                    <p class="mb-0 mt-1">{{ $pengajuan->catatan_admin }}</p>
                                </div>
                            @endif

                            <div class="card border-success mt-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i> Tempat PKL Anda (Ditetapkan
                                        Admin)</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-building me-2"></i> Perusahaan:</strong></p>
                                            <p class="h5 text-success">
                                                {{ $pengajuan->penempatan->industri->nama_perusahaan ?? '-' }}</p>
                                            <p><strong><i class="fas fa-map-marker-alt me-2"></i> Lokasi:</strong>
                                                {{ $pengajuan->penempatan->industri->lokasi ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-calendar-alt me-2"></i> Periode PKL:</strong></p>
                                            <p class="h5 text-success">
                                                {{ \Carbon\Carbon::parse($pengajuan->penempatan->tanggal_mulai)->format('d F Y') }}
                                                <i class="fas fa-arrow-right mx-2"></i>
                                                {{ \Carbon\Carbon::parse($pengajuan->penempatan->tanggal_selesai)->format('d F Y') }}
                                            </p>
                                            <p><strong><i class="fas fa-clock me-2"></i> Lama PKL:</strong>
                                                {{ \Carbon\Carbon::parse($pengajuan->penempatan->tanggal_mulai)->diffInDays($pengajuan->penempatan->tanggal_selesai) + 1 }}
                                                Hari
                                            </p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-chalkboard-teacher me-2"></i> Guru
                                                    Pembimbing:</strong>
                                                {{ $pengajuan->penempatan->guru->nama_guru ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong><i class="fas fa-tasks me-2"></i> Kompetensi:</strong>
                                                {{ $pengajuan->penempatan->kompetensi->nama_kompetensi ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mt-3 mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Informasi Penting:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Pengajuan Anda ditolak karena tempat yang dipilih tidak sesuai dengan pertimbangan
                                        tertentu.</li>
                                    <li>Admin telah menentukan tempat PKL yang lebih sesuai untuk Anda.</li>
                                    <li>Anda WAJIB melaksanakan PKL di tempat yang telah ditentukan oleh admin.</li>
                                    <li>Silakan hubungi guru pembimbing Anda untuk informasi lebih lanjut.</li>
                                </ul>
                            </div>
                        @else
                            <!-- DITOLAK MURNI (TANPA PENEMPATAN) -->
                            <div class="alert alert-danger mt-3 mb-0">
                                <h5 class="alert-heading">
                                    <i class="fas fa-exclamation-circle me-2"></i> Pengajuan Ditolak
                                </h5>
                                <p class="mb-0">Pengajuan tempat PKL Anda ditolak oleh admin.</p>
                            </div>

                            <div class="card border-danger mt-3">
                                <div class="card-body">
                                    <h6 class="text-danger">
                                        <i class="fas fa-info-circle me-2"></i> Alasan Penolakan:
                                    </h6>
                                    @if ($pengajuan->catatan_admin)
                                        <p class="alert alert-danger mt-2">
                                            <i class="fas fa-quote-left me-2"></i>
                                            {{ $pengajuan->catatan_admin }}
                                        </p>
                                    @else
                                        <p class="text-muted">Tidak ada catatan dari admin.</p>
                                    @endif

                                    <hr>
                                    <div class="mt-3">
                                        <p><strong>Yang perlu Anda lakukan:</strong></p>
                                        <ul>
                                            <li>Periksa kembali pilihan tempat PKL Anda.</li>
                                            <li>Pastikan pilihan sesuai dengan ketentuan yang berlaku.</li>
                                            <li>Anda bisa mengajukan ulang dengan pilihan yang berbeda.</li>
                                        </ul>
                                        <a href="{{ route('siswa.pengajuan.create') }}" class="btn btn-warning mt-2">
                                            <i class="fas fa-redo me-1"></i> Ajukan Ulang Pengajuan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                <h5>Belum Ada Pengajuan</h5>
                <p class="text-muted">Silakan ajukan tempat PKL Anda sekarang.</p>
                <a href="{{ route('siswa.pengajuan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Ajukan PKL
                </a>
            </div>
        @endif
    </div>
@endsection
