@extends('layouts.app')

@section('title', 'Input Penilaian Guru')

@section('content')
<div class="animate-fadeIn">
    <div class="flex items-center mb-6">
        <a href="{{ route('guru.nilai.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-clipboard-check mr-2 text-yellow-500"></i> DAFTAR NILAI PRAKTIK KERJA LAPANGAN
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Penilaian oleh Guru Pembimbing</p>
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
        </table>
    </div>

    <form action="{{ route('guru.nilai.store') }}" method="POST" id="formPenilaian">
        @csrf
        <input type="hidden" name="penempatan_id" value="{{ $penempatan->id }}">

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
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-24 text-center" colspan="2">Nilai</th>
                        </tr>
                        <tr class="bg-gray-50 dark:bg-gray-600">
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-1"></th>
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-1"></th>
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-1 w-16 text-center text-xs">Angka</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-1 w-16 text-center text-xs">Huruf</th>
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
                                    $existing = $existingNilais->get($child->nama_aspek);
                                    $nilaiKey = 'nilai_' . $child->id;
                                    $catatanKey = 'catatan_' . $child->id;
                                    $oldNilai = old($nilaiKey, $existing ? $existing->nilai : '');
                                    if ($oldNilai !== '') $allKejuruanNilai[] = (int) $oldNilai;
                                @endphp
                                <tr>
                                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center"></td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 pl-8">
                                        {{ $child->nama_aspek }}
                                        <input type="hidden" name="{{ $catatanKey }}" value="">
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-2 py-1">
                                        <input type="number" name="{{ $nilaiKey }}"
                                            class="w-full text-center border-0 bg-transparent focus:ring-1 focus:ring-yellow-500 rounded"
                                            min="0" max="100"
                                            value="{{ $oldNilai }}"
                                            placeholder="0" data-kategori="kejuruan"
                                            oninput="updateHuruf(this); hitungRataRata('kejuruan')">
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center huruf-cell" data-for="{{ $nilaiKey }}">
                                        @if($oldNilai !== '')
                                            {{ \App\Models\TemplatePenilaian::nilaiToHuruf((int) $oldNilai) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        <tr class="bg-gray-100 dark:bg-gray-700 font-semibold">
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2" colspan="2">Jumlah</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center" id="jumlah-kejuruan">
                                @if(count($allKejuruanNilai) > 0) {{ array_sum($allKejuruanNilai) }} @else - @endif
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2"></td>
                        </tr>
                        <tr class="bg-gray-100 dark:bg-gray-700 font-semibold">
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2" colspan="2">Rata-rata</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center" id="rata-kejuruan">
                                @if(count($allKejuruanNilai) > 0) {{ number_format(array_sum($allKejuruanNilai) / count($allKejuruanNilai), 1) }} @else - @endif
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center" id="huruf-kejuruan">
                                @if(count($allKejuruanNilai) > 0)
                                    @php $avg = array_sum($allKejuruanNilai) / count($allKejuruanNilai); @endphp
                                    {{ \App\Models\TemplatePenilaian::nilaiToHuruf((int) $avg) }}
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
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 w-24 text-center" colspan="2">Nilai</th>
                        </tr>
                        <tr class="bg-gray-50 dark:bg-gray-600">
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-1"></th>
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-1"></th>
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-1 w-16 text-center text-xs">Angka</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-3 py-1 w-16 text-center text-xs">Huruf</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; $allSikapNilai = []; @endphp
                        @foreach($sikapItems as $item)
                            @php
                                $existing = $existingNilais->get($item->nama_aspek);
                                $nilaiKey = 'nilai_' . $item->id;
                                $catatanKey = 'catatan_' . $item->id;
                                $oldNilai = old($nilaiKey, $existing ? $existing->nilai : '');
                                if ($oldNilai !== '') $allSikapNilai[] = (int) $oldNilai;
                            @endphp
                            <tr>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center">{{ $no++ }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2">
                                    {{ $item->nama_aspek }}
                                    <input type="hidden" name="{{ $catatanKey }}" value="">
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-2 py-1">
                                    <input type="number" name="{{ $nilaiKey }}"
                                        class="w-full text-center border-0 bg-transparent focus:ring-1 focus:ring-yellow-500 rounded"
                                        min="0" max="100"
                                        value="{{ $oldNilai }}"
                                        placeholder="0" data-kategori="sikap"
                                        oninput="updateHuruf(this); hitungRataRata('sikap')">
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center huruf-cell" data-for="{{ $nilaiKey }}">
                                    @if($oldNilai !== '')
                                        {{ \App\Models\TemplatePenilaian::nilaiToHuruf((int) $oldNilai) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-100 dark:bg-gray-700 font-semibold">
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2" colspan="2">Jumlah</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center" id="jumlah-sikap">
                                @if(count($allSikapNilai) > 0) {{ array_sum($allSikapNilai) }} @else - @endif
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2"></td>
                        </tr>
                        <tr class="bg-gray-100 dark:bg-gray-700 font-semibold">
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2" colspan="2">Rata-rata</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center" id="rata-sikap">
                                @if(count($allSikapNilai) > 0) {{ number_format(array_sum($allSikapNilai) / count($allSikapNilai), 1) }} @else - @endif
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center" id="huruf-sikap">
                                @if(count($allSikapNilai) > 0)
                                    @php $avg = array_sum($allSikapNilai) / count($allSikapNilai); @endphp
                                    {{ \App\Models\TemplatePenilaian::nilaiToHuruf((int) $avg) }}
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

        <!-- Submit -->
        <div class="flex gap-3 pt-4">
            <button type="submit" class="btn-primary flex-1">
                <i class="fas fa-save mr-2"></i> Simpan Semua Nilai
            </button>
            <a href="{{ route('guru.nilai.index') }}" class="btn-danger flex-1 text-center">
                <i class="fas fa-times mr-2"></i> Batal
            </a>
        </div>
    </form>
</div>

<script>
function nilaiToHuruf(n) {
    if (n >= 90) return 'A';
    if (n >= 80) return 'B';
    if (n >= 70) return 'C';
    return 'D';
}

function updateHuruf(input) {
    const val = input.value;
    const key = input.name.replace('nilai_', '');
    const cell = document.querySelector(`.huruf-cell[data-for="nilai_${key}"]`);
    if (cell) {
        cell.textContent = val !== '' ? nilaiToHuruf(parseInt(val)) : '-';
    }
}

function hitungRataRata(kategori) {
    const inputs = document.querySelectorAll(`input[data-kategori="${kategori}"]`);
    let total = 0, count = 0;
    inputs.forEach(inp => {
        if (inp.value !== '') {
            total += parseInt(inp.value);
            count++;
        }
    });
    document.getElementById('jumlah-' + kategori).textContent = count > 0 ? total : '-';
    document.getElementById('rata-' + kategori).textContent = count > 0 ? (total / count).toFixed(1) : '-';
    document.getElementById('huruf-' + kategori).textContent = count > 0 ? nilaiToHuruf(Math.round(total / count)) : '-';
}
</script>
@endsection
