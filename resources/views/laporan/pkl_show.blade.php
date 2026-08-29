@extends('layouts.app')

@section('title', 'Laporan PKL - ' . $penempatan->siswa->nama_siswa)

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('laporan.pkl') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <div class="flex-1">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-clipboard-list mr-2 text-teal-500"></i> DAFTAR NILAI PRAKTIK KERJA LAPANGAN
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Penilaian oleh Industri (Pembimbing Lapangan)</p>
        </div>
        <div class="flex gap-2 shrink-0">
            <a href="{{ route('laporan.nilai.cetak', $penempatan->id) }}" target="_blank" class="btn-danger">
                <i class="fas fa-file-pdf mr-2"></i> Cetak PDF
            </a>
            <a href="{{ route('laporan.pkl.excel', $penempatan->id) }}" class="btn-success">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Header Info Siswa -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
        <table class="w-full text-sm">
            <tr>
                <td class="py-1 font-semibold text-gray-700 dark:text-gray-300 w-40">Nama</td>
                <td class="py-1 text-gray-800 dark:text-white">: {{ $penempatan->siswa->nama_siswa }}</td>
            </tr>
            <tr>
                <td class="py-1 font-semibold text-gray-700 dark:text-gray-300">NIS</td>
                <td class="py-1 text-gray-800 dark:text-white">: {{ $penempatan->siswa->nis }}</td>
            </tr>
            <tr>
                <td class="py-1 font-semibold text-gray-700 dark:text-gray-300">Kompetensi Keahlian</td>
                <td class="py-1 text-gray-800 dark:text-white">: {{ $penempatan->kompetensi->nama_kompetensi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="py-1 font-semibold text-gray-700 dark:text-gray-300">Program Keahlian</td>
                <td class="py-1 text-gray-800 dark:text-white">: {{ $penempatan->siswa->jurusan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="py-1 font-semibold text-gray-700 dark:text-gray-300">Tempat PKL</td>
                <td class="py-1 text-gray-800 dark:text-white">: {{ $penempatan->industri->nama_perusahaan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="py-1 font-semibold text-gray-700 dark:text-gray-300">Guru Pembimbing</td>
                <td class="py-1 text-gray-800 dark:text-white">: {{ $penempatan->guru->nama_guru ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- A. Aspek Kejuruan -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 border-b pb-2">
            A. Aspek Kejuruan
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-12 text-center">No</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-left">Komponen Kompetensi Kejuruan</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-16 text-center">Angka</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-16 text-center">Huruf</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; $allKejuruanNilai = []; @endphp
                    @foreach($kejuruanRoot as $komponen)
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center font-semibold">{{ $no++ }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 font-semibold" colspan="3">
                                {{ $komponen->nama_aspek }}
                            </td>
                        </tr>
                        @foreach($komponen->children->where('is_active', true) as $child)
                            @php
                                $existing = $nilais->get($child->nama_aspek);
                                if ($existing) $allKejuruanNilai[] = $existing->nilai;
                            @endphp
                            <tr>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center"></td>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 pl-8">
                                    {{ $child->nama_aspek }}
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center font-semibold">
                                    {{ $existing ? $existing->nilai : '-' }}
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                    {{ $existing ? \App\Models\TemplatePenilaian::nilaiToHuruf($existing->nilai) : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr class="bg-gray-100 dark:bg-gray-700 font-semibold">
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2" colspan="2">Jumlah</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                            {{ count($allKejuruanNilai) > 0 ? array_sum($allKejuruanNilai) : '-' }}
                        </td>
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2"></td>
                    </tr>
                    <tr class="bg-gray-100 dark:bg-gray-700 font-semibold">
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2" colspan="2">Rata-rata</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                            {{ count($allKejuruanNilai) > 0 ? number_format(array_sum($allKejuruanNilai) / count($allKejuruanNilai), 1) : '-' }}
                        </td>
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                            @if(count($allKejuruanNilai) > 0)
                                {{ \App\Models\TemplatePenilaian::nilaiToHuruf((int)(array_sum($allKejuruanNilai) / count($allKejuruanNilai))) }}
                            @else - @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- B. Aspek Sikap -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 border-b pb-2">
            B. Aspek Sikap
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 dark:border-gray-600 text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-12 text-center">No</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-left">Komponen Sikap</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-16 text-center">Angka</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-16 text-center">Huruf</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; $allSikapNilai = []; @endphp
                    @foreach($sikapItems as $item)
                        @php
                            $existing = $nilais->get($item->nama_aspek);
                            if ($existing) $allSikapNilai[] = $existing->nilai;
                        @endphp
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">{{ $no++ }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2">{{ $item->nama_aspek }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center font-semibold">
                                {{ $existing ? $existing->nilai : '-' }}
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                                {{ $existing ? \App\Models\TemplatePenilaian::nilaiToHuruf($existing->nilai) : '-' }}
                            </td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-100 dark:bg-gray-700 font-semibold">
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2" colspan="2">Jumlah</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                            {{ count($allSikapNilai) > 0 ? array_sum($allSikapNilai) : '-' }}
                        </td>
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2"></td>
                    </tr>
                    <tr class="bg-gray-100 dark:bg-gray-700 font-semibold">
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2" colspan="2">Rata-rata</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                            {{ count($allSikapNilai) > 0 ? number_format(array_sum($allSikapNilai) / count($allSikapNilai), 1) : '-' }}
                        </td>
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">
                            @if(count($allSikapNilai) > 0)
                                {{ \App\Models\TemplatePenilaian::nilaiToHuruf((int)(array_sum($allSikapNilai) / count($allSikapNilai))) }}
                            @else - @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
        <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-2">Keterangan Nilai Angka dan Huruf</h3>
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
@endsection