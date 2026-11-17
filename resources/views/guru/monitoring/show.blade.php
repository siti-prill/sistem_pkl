@extends('layouts.app')

@section('title', 'Detail Monitoring')

@section('content')
    <div class="animate-fadeIn">
        <!-- Tombol Kembali & Judul -->
        <div class="flex items-center mb-6">
            <a href="{{ route('guru.monitoring.index') }}"
                class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4 transition">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-user-graduate mr-2 text-indigo-500"></i> Monitoring: {{ $penempatan->siswa->nama_siswa }}
            </h2>
        </div>

        <!-- Info Siswa -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">NIS</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $penempatan->siswa->nis }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nama</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $penempatan->siswa->nama_siswa }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Industri</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $penempatan->industri->nama_perusahaan }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                    <span class="px-2 py-1 rounded-full text-sm bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                        {{ ucfirst($penempatan->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Nilai dari Industri -->
        @php
            $nilaiIndustri = \App\Models\MonitoringNilai::where('penempatan_id', $penempatan->id)
                ->where('role_penilai', 'industri')
                ->get();
        @endphp

        @if ($nilaiIndustri->count() > 0)
            @php
                $rataIndustri = $nilaiIndustri->avg('nilai');
                $gradeIndustri = $rataIndustri >= 85 ? 'A' : ($rataIndustri >= 70 ? 'B' : ($rataIndustri >= 60 ? 'C' : 'D'));
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                    <i class="fas fa-clipboard-check mr-2 text-teal-500"></i> Nilai dari Industri
                </h3>
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="text-center p-3 bg-teal-50 dark:bg-teal-900/20 rounded-lg">
                        <p class="text-sm text-gray-500">Jumlah Aspek</p>
                        <p class="text-2xl font-bold text-teal-600">{{ $nilaiIndustri->count() }}</p>
                    </div>
                    <div class="text-center p-3 bg-teal-50 dark:bg-teal-900/20 rounded-lg">
                        <p class="text-sm text-gray-500">Rata-rata</p>
                        <p class="text-2xl font-bold text-teal-600">{{ number_format($rataIndustri, 1) }}</p>
                    </div>
                    <div class="text-center p-3 bg-teal-50 dark:bg-teal-900/20 rounded-lg">
                        <p class="text-sm text-gray-500">Grade</p>
                        <p class="text-2xl font-bold text-teal-600">{{ $gradeIndustri }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    @foreach ($nilaiIndustri as $nilai)
                        <div class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $nilai->aspek_penilaian }}</span>
                            <span class="font-bold text-teal-600">{{ $nilai->nilai }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Jurnal List -->
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
            <i class="fas fa-book mr-2 text-indigo-500"></i> Jurnal Harian
        </h3>

        @php
            $jurnalsSubmitted = $jurnals->filter(fn($j) => $j->status === 'submitted');
        @endphp

        @forelse($jurnalsSubmitted as $jurnal)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 mb-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border border-gray-100 dark:border-gray-700">
                <div class="flex flex-wrap items-center gap-4">
                    <h4 class="font-bold text-lg text-gray-800 dark:text-white">
                        {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d F Y') }}
                    </h4>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        (Hari ke-{{ \Carbon\Carbon::parse($penempatan->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($jurnal->tanggal)) + 1 }})
                    </span>
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                        <i class="fas fa-check-circle mr-1"></i> Submitted
                    </span>
                </div>
                <button type="button" class="btn-primary btn-sm" onclick="showDetailJurnal({{ $jurnal->id }})">
                    <i class="fas fa-eye mr-1"></i> Lihat Detail
                </button>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-book-open text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Belum Ada Jurnal</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Siswa belum mengisi atau mensubmit jurnal</p>
            </div>
        @endforelse

        <div class="mt-4">
            {{ $jurnals->withQueryString()->links() }}
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- PURE CSS MODAL (Anti Berantakan)             --}}
    {{-- Semua style di-scope dengan prefix .jm-       --}}
    {{-- ============================================= --}}
    <style>
        /* ===== OVERLAY / BACKDROP ===== */
        .jm-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: none;
            overflow-y: auto;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            padding: 16px;
            animation: jmFadeIn 0.25s ease-out;
        }
        .jm-overlay.jm-show {
            display: block;
        }

        /* ===== PANEL / KOTAK MODAL ===== */
        .jm-panel {
            position: relative;
            width: 100%;
            max-width: 640px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.45);
            animation: jmZoomIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 80px);
        }

        /* ===== HEADER MODAL ===== */
        .jm-header {
            position: relative;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-shrink: 0;
            overflow: hidden;
        }
        .jm-header::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 130px;
            height: 130px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 50%;
            filter: blur(20px);
        }
        .jm-header::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -40px;
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            filter: blur(20px);
        }
        .jm-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            position: relative;
            z-index: 1;
        }
        .jm-header-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            font-size: 18px;
        }
        .jm-header-title {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            margin: 0;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .jm-header-subtitle {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.85);
            margin: 2px 0 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .jm-close-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }
        .jm-close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        /* ===== BODY MODAL (SCROLLABLE) ===== */
        .jm-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
            background: #f9fafb;
        }
        .jm-body::-webkit-scrollbar {
            width: 6px;
        }
        .jm-body::-webkit-scrollbar-track {
            background: transparent;
        }
        .jm-body::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #6366f1, #8b5cf6);
            border-radius: 999px;
        }

        /* ===== SECTION TITLE ===== */
        .jm-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .jm-section-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #fff;
            font-size: 13px;
        }
        .jm-icon-indigo { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
        .jm-icon-purple { background: linear-gradient(135deg, #a855f7, #d946ef); }
        .jm-icon-teal   { background: linear-gradient(135deg, #14b8a6, #10b981); }
        .jm-section-title h4 {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            line-height: 1.3;
        }
        .jm-section-title p {
            font-size: 11px;
            color: #6b7280;
            margin: 0;
            line-height: 1.3;
        }

        /* ===== INFO GRID (Tanggal & Status) ===== */
        .jm-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 18px;
        }
        .jm-info-card {
            border-radius: 14px;
            padding: 12px 14px;
            border: 1px solid;
            transition: all 0.2s;
        }
        .jm-info-card.jm-info-indigo {
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            border-color: #c7d2fe;
        }
        .jm-info-card.jm-info-green {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-color: #a7f3d0;
        }
        .jm-info-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }
        .jm-info-indigo .jm-info-label { color: #4f46e5; }
        .jm-info-green  .jm-info-label { color: #059669; }
        .jm-info-value {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .jm-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            display: inline-block;
            animation: jmPulse 2s infinite;
        }

        /* ===== BLOK KONTEN ===== */
        .jm-block {
            margin-bottom: 18px;
        }
        .jm-content-box {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 14px 16px;
            position: relative;
            font-size: 13px;
            line-height: 1.7;
            color: #374151;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .jm-content-box.jm-content-aktivitas {
            border-left: 4px solid #6366f1;
            padding-left: 18px;
        }

        /* ===== DOKUMENTASI ===== */
        .jm-doc-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            background: linear-gradient(135deg, #faf5ff 0%, #fdf4ff 100%);
            border: 2px dashed #d8b4fe;
            border-radius: 14px;
            text-decoration: none;
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
        }
        .jm-doc-link:hover {
            border-color: #a855f7;
            box-shadow: 0 4px 16px rgba(168, 85, 247, 0.15);
            transform: translateY(-1px);
        }
        .jm-doc-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }
        .jm-doc-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #a855f7, #d946ef);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 15px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.3);
        }
        .jm-doc-title {
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }
        .jm-doc-sub {
            font-size: 11px;
            color: #6b7280;
            margin: 2px 0 0;
        }
        .jm-doc-arrow {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: #f3e8ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #a855f7;
            font-size: 12px;
            flex-shrink: 0;
            transition: all 0.25s;
        }
        .jm-doc-link:hover .jm-doc-arrow {
            background: #a855f7;
            color: #fff;
        }

        /* ===== KOMENTAR ===== */
        .jm-comment-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 999px;
            background: linear-gradient(135deg, #ccfbf1, #a7f3d0);
            color: #047857;
            font-size: 11px;
            font-weight: 700;
            border: 1px solid #6ee7b7;
            margin-left: auto;
            flex-shrink: 0;
        }
        .jm-comment-item {
            display: flex;
            gap: 10px;
            padding: 12px 14px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            margin-bottom: 8px;
            transition: all 0.2s;
        }
        .jm-comment-item:hover {
            border-color: #5eead4;
            box-shadow: 0 2px 10px rgba(20, 184, 166, 0.08);
        }
        .jm-comment-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 13px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25);
        }
        .jm-comment-content {
            flex: 1;
            min-width: 0;
        }
        .jm-comment-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
            flex-wrap: wrap;
        }
        .jm-comment-name {
            font-size: 12px;
            font-weight: 700;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0;
        }
        .jm-comment-name::after {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #14b8a6;
        }
        .jm-comment-time {
            font-size: 10px;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .jm-comment-text {
            font-size: 12px;
            color: #4b5563;
            line-height: 1.6;
            margin: 0;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* ===== EMPTY STATE ===== */
        .jm-empty {
            text-align: center;
            padding: 26px 16px;
            background: #fff;
            border: 2px dashed #d1d5db;
            border-radius: 14px;
            margin-bottom: 14px;
        }
        .jm-empty-icon {
            width: 52px;
            height: 52px;
            margin: 0 auto 10px;
            border-radius: 14px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 20px;
        }
        .jm-empty p {
            margin: 0;
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }
        .jm-empty small {
            display: block;
            font-size: 11px;
            color: #9ca3af;
            margin-top: 4px;
        }

        /* ===== FORM KOMENTAR ===== */
        .jm-form-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 8px;
        }
        .jm-form-label i { color: #6366f1; }
        .jm-form-row {
            display: flex;
            gap: 8px;
        }
        .jm-input-wrap {
            flex: 1;
            position: relative;
        }
        .jm-input {
            width: 100%;
            padding: 11px 12px 11px 36px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            font-size: 13px;
            background: #fff;
            color: #111827;
            transition: all 0.2s;
            outline: none;
            box-sizing: border-box;
            font-family: inherit;
        }
        .jm-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        .jm-input::placeholder { color: #9ca3af; }
        .jm-input-wrap > i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 13px;
            pointer-events: none;
        }
        .jm-submit {
            padding: 11px 20px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            white-space: nowrap;
        }
        .jm-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(99, 102, 241, 0.5);
        }
        .jm-submit:active { transform: translateY(0); }

        /* ===== FOOTER ===== */
        .jm-footer {
            padding: 14px 20px;
            border-top: 1px solid #e5e7eb;
            background: #fff;
            display: flex;
            justify-content: flex-end;
            flex-shrink: 0;
        }
        .jm-footer-btn {
            padding: 10px 20px;
            border-radius: 11px;
            background: #fff;
            border: 1px solid #d1d5db;
            color: #374151;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .jm-footer-btn:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
            transform: translateY(-1px);
        }
        .jm-footer-btn i { color: #9ca3af; }

        /* ===== ANIMATIONS ===== */
        @keyframes jmFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes jmZoomIn {
            from { opacity: 0; transform: scale(0.94) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes jmPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5); }
            50%      { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 640px) {
            .jm-panel { margin: 16px auto; max-height: calc(100vh - 32px); }
            .jm-overlay { padding: 8px; }
            .jm-header { padding: 14px 16px; }
            .jm-header-icon { width: 38px; height: 38px; font-size: 15px; }
            .jm-header-title { font-size: 14px; }
            .jm-body { padding: 14px; }
            .jm-info-grid { grid-template-columns: 1fr; }
            .jm-form-row { flex-direction: column; }
            .jm-submit { width: 100%; justify-content: center; }
        }
    </style>

    {{-- ============================================= --}}
    {{-- MODAL CONTAINER                              --}}
    {{-- ============================================= --}}
    <div id="jm-modal-container">
        @foreach ($jurnalsSubmitted as $jurnal)
            <div id="jm-modal-{{ $jurnal->id }}" class="jm-overlay" onclick="if(event.target===this)closeJurnalModal({{ $jurnal->id }})">
                <div class="jm-panel">

                    {{-- HEADER --}}
                    <div class="jm-header">
                        <div class="jm-header-left">
                            <div class="jm-header-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div>
                                <h3 class="jm-header-title">Detail Jurnal Harian</h3>
                                <p class="jm-header-subtitle">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('l, d F Y') }}
                                </p>
                            </div>
                        </div>
                        <button type="button" class="jm-close-btn" onclick="closeJurnalModal({{ $jurnal->id }})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    {{-- BODY --}}
                    <div class="jm-body">

                        {{-- INFO TANGGAL & STATUS --}}
                        <div class="jm-info-grid">
                            <div class="jm-info-card jm-info-indigo">
                                <div class="jm-info-label">
                                    <i class="fas fa-calendar-alt"></i> Tanggal
                                </div>
                                <div class="jm-info-value">
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') }}
                                </div>
                            </div>
                            <div class="jm-info-card jm-info-green">
                                <div class="jm-info-label">
                                    <i class="fas fa-check-circle"></i> Status
                                </div>
                                <div class="jm-info-value">
                                    <span class="jm-dot"></span> Submitted
                                </div>
                            </div>
                        </div>

                        {{-- AKTIVITAS --}}
                        <div class="jm-block">
                            <div class="jm-section-title">
                                <div class="jm-section-icon jm-icon-indigo">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <div>
                                    <h4>Aktivitas Harian</h4>
                                    <p>Kegiatan yang dilakukan siswa</p>
                                </div>
                            </div>
                            <div class="jm-content-box jm-content-aktivitas">{{ $jurnal->aktivitas }}</div>
                        </div>

                        {{-- DOKUMENTASI --}}
                        @if ($jurnal->dokumentasi)
                            @php
                                $isUrl = \Illuminate\Support\Str::startsWith($jurnal->dokumentasi, ['http://', 'https://']);
                            @endphp
                            <div class="jm-block">
                                <div class="jm-section-title">
                                    <div class="jm-section-icon jm-icon-purple">
                                        <i class="fas fa-images"></i>
                                    </div>
                                    <div>
                                        <h4>Dokumentasi</h4>
                                        <p>Bukti kegiatan jurnal</p>
                                    </div>
                                </div>
                                <a href="{{ $isUrl ? $jurnal->dokumentasi : route('jurnal.dokumentasi', $jurnal) }}"
                                   target="_blank" rel="noopener" class="jm-doc-link">
                                    <div class="jm-doc-left">
                                        <div class="jm-doc-icon">
                                            <i class="fas {{ $isUrl ? 'fa-link' : 'fa-image' }}"></i>
                                        </div>
                                        <div>
                                            <p class="jm-doc-title">Lihat Dokumentasi Foto</p>
                                            <p class="jm-doc-sub">Buka file dokumentasi jurnal</p>
                                        </div>
                                    </div>
                                    <div class="jm-doc-arrow">
                                        <i class="fas fa-arrow-up-right-from-square"></i>
                                    </div>
                                </a>
                            </div>
                        @endif

                        {{-- KOMENTAR --}}
                        <div class="jm-block">
                            <div class="jm-section-title">
                                <div class="jm-section-icon jm-icon-teal">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <div>
                                    <h4>Komentar Guru</h4>
                                    <p>Evaluasi & masukan</p>
                                </div>
                                <span class="jm-comment-badge">
                                    <i class="fas fa-comment-dots"></i>
                                    {{ $jurnal->komentarJurnal->count() }} Komentar
                                </span>
                            </div>

                            @if ($jurnal->komentarJurnal->count() > 0)
                                @foreach ($jurnal->komentarJurnal as $komentar)
                                    <div class="jm-comment-item">
                                        <div class="jm-comment-avatar">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div class="jm-comment-content">
                                            <div class="jm-comment-head">
                                                <p class="jm-comment-name">
                                                    {{ $komentar->guru->nama_guru ?? 'Guru Pembimbing' }}
                                                </p>
                                                <span class="jm-comment-time">
                                                    <i class="far fa-clock"></i>
                                                    {{ \Carbon\Carbon::parse($komentar->created_at)->translatedFormat('d M Y • H:i') }}
                                                </span>
                                            </div>
                                            <p class="jm-comment-text">{{ $komentar->komentar }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="jm-empty">
                                    <div class="jm-empty-icon">
                                        <i class="fas fa-comment-slash"></i>
                                    </div>
                                    <p>Belum ada komentar dari guru pembimbing.</p>
                                    <small>Berikan komentar pertama di bawah ini.</small>
                                </div>
                            @endif

                            {{-- FORM KOMENTAR --}}
                            <form action="{{ route('guru.komentar.store', $jurnal->id) }}" method="POST">
                                @csrf
                                <label class="jm-form-label">
                                    <i class="fas fa-pen-fancy"></i> Berikan Komentar
                                </label>
                                <div class="jm-form-row">
                                    <div class="jm-input-wrap">
                                        <i class="fas fa-comment-dots"></i>
                                        <input type="text" name="komentar" class="jm-input"
                                            placeholder="Tulis komentar atau evaluasi jurnal..." required>
                                    </div>
                                    <button type="submit" class="jm-submit">
                                        <i class="fas fa-paper-plane"></i> Kirim
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="jm-footer">
                        <button type="button" class="jm-footer-btn" onclick="closeJurnalModal({{ $jurnal->id }})">
                            <i class="fas fa-times-circle"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ============================================= --}}
    {{-- SCRIPT MODAL                                 --}}
    {{-- ============================================= --}}
    <script>
        // Pindahkan container modal ke body agar tidak terpotong layout
        document.addEventListener("DOMContentLoaded", function () {
            const container = document.getElementById('jm-modal-container');
            if (container) {
                document.body.appendChild(container);
            }
        });

        function showDetailJurnal(id) {
            const modal = document.getElementById('jm-modal-' + id);
            if (modal) {
                modal.classList.add('jm-show');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeJurnalModal(id) {
            const modal = document.getElementById('jm-modal-' + id);
            if (modal) {
                modal.classList.remove('jm-show');
                document.body.style.overflow = '';
            }
        }

        // Tutup semua modal jika tekan ESC
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.jm-overlay.jm-show').forEach(modal => {
                    const id = modal.id.replace('jm-modal-', '');
                    closeJurnalModal(id);
                });
            }
        });
    </script>
@endsection