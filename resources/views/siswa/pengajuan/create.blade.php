@extends('layouts.app')

@section('title', 'Form Pengajuan PKL')
@section('page-title', 'Pengajuan PKL')
@section('page-subtitle', 'Isi form pengajuan tempat PKL')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5>Form Pengajuan Tempat PKL</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.pengajuan.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- NIS -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">NIS</label>
                            <input type="text" class="form-control" value="{{ $siswa->nis }}" disabled>
                        </div>

                        <!-- Nama Siswa -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Nama Siswa</label>
                            <input type="text" class="form-control" value="{{ $siswa->nama_siswa }}" disabled>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Jurusan</label>
                            <input type="text" class="form-control" value="{{ $siswa->jurusan }}" disabled>
                        </div>

                        <!-- Jurusan -->
                        {{-- <div class="col-md-12 mb-3">
                            <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                            <input type="text" name="jurusan" class="form-control @error('jurusan') is-invalid @enderror"
                                value="{{ old('jurusan', $siswa->jurusan) }}" readonly disabled>
                            @error('jurusan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <!-- Pilihan Tempat PKL 1 -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Pilihan Tempat PKL 1 <span class="text-danger">*</span></label>
                            <select name="pilihan_1" id="pilihan_1_select"
                                class="form-select @error('pilihan_1') is-invalid @enderror" onchange="toggleLainnya(1)">
                                <option value="">Pilih Tempat</option>
                                <option value="Padang" {{ old('pilihan_1') == 'Padang' ? 'selected' : '' }}>Padang</option>
                                <option value="Bandung" {{ old('pilihan_1') == 'Bandung' ? 'selected' : '' }}>Bandung
                                </option>
                                <option value="Yogyakarta" {{ old('pilihan_1') == 'Yogyakarta' ? 'selected' : '' }}>
                                    Yogyakarta</option>
                                <option value="Pekanbaru" {{ old('pilihan_1') == 'Pekanbaru' ? 'selected' : '' }}>Pekanbaru
                                </option>
                                <option value="Batam" {{ old('pilihan_1') == 'Batam' ? 'selected' : '' }}>Batam</option>
                                <option value="Jakarta" {{ old('pilihan_1') == 'Jakarta' ? 'selected' : '' }}>Jakarta
                                </option>
                                <option value="Lainnya" {{ old('pilihan_1') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                    (tulis sendiri)</option>
                            </select>
                            <input type="text" name="pilihan_1" id="pilihan_1_input"
                                class="form-control @error('pilihan_1') is-invalid @enderror d-none"
                                placeholder="Tulis tempat PKL lainnya..."
                                value="{{ old('pilihan_1') == 'Lainnya' ? '' : old('pilihan_1') }}">
                            @error('pilihan_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Industri Pilihan 1 -->
                        <div class="col-md-12 mb-3 d-none industri-box" id="industri_1_wrapper">
                            <label class="form-label">Industri Pilihan 1</label>

                            <select name="industri_1" id="industri_1_select"
                                class="form-select @error('industri_1') is-invalid @enderror"
                                onchange="toggleIndustriLainnya(1)">
                                <option value="">Pilih Industri</option>
                                <option value="Lainnya">Lainnya (tulis sendiri)</option>
                            </select>

                            <input type="text" name="industri_1" id="industri_1_input"
                                class="form-control @error('industri_1') is-invalid @enderror d-none"
                                placeholder="Tulis nama industri lainnya...">

                            @error('industri_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pilihan Tempat PKL 2 -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Pilihan Tempat PKL 2 <span class="text-danger">*</span></label>
                            <select name="pilihan_2" id="pilihan_2_select"
                                class="form-select @error('pilihan_2') is-invalid @enderror" onchange="toggleLainnya(2)">
                                <option value="">Pilih Tempat</option>
                                <option value="Padang" {{ old('pilihan_2') == 'Padang' ? 'selected' : '' }}>Padang</option>
                                <option value="Bandung" {{ old('pilihan_2') == 'Bandung' ? 'selected' : '' }}>Bandung
                                </option>
                                <option value="Yogyakarta" {{ old('pilihan_2') == 'Yogyakarta' ? 'selected' : '' }}>
                                    Yogyakarta</option>
                                <option value="Pekanbaru" {{ old('pilihan_2') == 'Pekanbaru' ? 'selected' : '' }}>Pekanbaru
                                </option>
                                <option value="Batam" {{ old('pilihan_2') == 'Batam' ? 'selected' : '' }}>Batam</option>
                                <option value="Jakarta" {{ old('pilihan_2') == 'Jakarta' ? 'selected' : '' }}>Jakarta
                                </option>
                                <option value="Lainnya" {{ old('pilihan_2') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                    (tulis sendiri)</option>
                            </select>
                            <input type="text" name="pilihan_2" id="pilihan_2_input"
                                class="form-control @error('pilihan_2') is-invalid @enderror d-none"
                                placeholder="Tulis tempat PKL lainnya..."
                                value="{{ old('pilihan_2') == 'Lainnya' ? '' : old('pilihan_2') }}">
                            @error('pilihan_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Industri Pilihan 2 -->
                        <div class="col-md-12 mb-3 d-none industri-box" id="industri_2_wrapper">
                            <label class="form-label">Industri Pilihan 2</label>

                            <select name="industri_2" id="industri_2_select"
                                class="form-select @error('industri_2') is-invalid @enderror"
                                onchange="toggleIndustriLainnya(2)">
                                <option value="">Pilih Industri</option>
                                <option value="Lainnya">Lainnya (tulis sendiri)</option>
                            </select>

                            <input type="text" name="industri_2" id="industri_2_input"
                                class="form-control @error('industri_2') is-invalid @enderror d-none"
                                placeholder="Tulis nama industri lainnya...">

                            @error('industri_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pekerjaan Orang Tua -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Pekerjaan Orang Tua</label>
                            <input type="text" name="pekerjaan_orang_tua"
                                class="form-control @error('pekerjaan_orang_tua') is-invalid @enderror"
                                value="{{ old('pekerjaan_orang_tua') }}"
                                placeholder="Contoh: Petani, PNS, Wiraswasta, dll">
                            @error('pekerjaan_orang_tua')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Penghasilan Orang Tua -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Penghasilan Orang Tua (per bulan)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" name="penghasilan_ortu" id="penghasilan_ortu"
                                    class="form-control @error('penghasilan_ortu') is-invalid @enderror"
                                    value="{{ old('penghasilan_ortu') }}" placeholder="2.000.000">
                            </div>
                            @error('penghasilan_ortu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alamat Rumah -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat Rumah</label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3"
                                placeholder="Masukkan alamat lengkap rumah Anda">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan
                        </button>
                        <a href="{{ route('siswa.pengajuan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const industriByLokasi = @json($industriByLokasi->map(fn($list) => $list->pluck('nama_perusahaan')));

        function toggleLainnya(n) {
            const select = document.getElementById('pilihan_' + n + '_select');
            const input = document.getElementById('pilihan_' + n + '_input');

            const industriWrapper = document.getElementById('industri_' + n + '_wrapper');
            const industriSelect = document.getElementById('industri_' + n + '_select');
            const industriInput = document.getElementById('industri_' + n + '_input');

            if (select.value === 'Lainnya') {
                // Sembunyikan select tempat PKL
                select.classList.add('d-none');
                select.disabled = true;

                // Tampilkan input tempat PKL
                input.classList.remove('d-none');
                input.disabled = false;
                input.focus();

                // Sembunyikan industri
                industriWrapper.classList.add('d-none');
                industriSelect.disabled = true;
                industriInput.disabled = true;

                industriSelect.value = '';
                industriInput.value = '';

            } else if (select.value === '') {
                // Belum memilih tempat PKL
                select.classList.remove('d-none');
                select.disabled = false;

                input.classList.add('d-none');
                input.disabled = true;
                input.value = '';

                // Sembunyikan industri
                industriWrapper.classList.add('d-none');
                industriSelect.disabled = true;
                industriInput.disabled = true;

                industriSelect.value = '';
                industriInput.value = '';

            } else {
                // Tempat PKL sudah dipilih
                select.classList.remove('d-none');
                select.disabled = false;

                input.classList.add('d-none');
                input.disabled = true;
                input.value = '';

                // Tampilkan industri
                industriWrapper.classList.remove('d-none');
                industriSelect.disabled = false;

                // Isi daftar industri berdasarkan lokasi
                fillIndustri(n, select.value);
            }
        }

        function toggleIndustriLainnya(n) {
            const select = document.getElementById('industri_' + n + '_select');
            const input = document.getElementById('industri_' + n + '_input');

            if (select.value === 'Lainnya') {
                select.classList.add('d-none');
                select.disabled = true;
                input.classList.remove('d-none');
                input.disabled = false;
                input.focus();
            } else {
                select.classList.remove('d-none');
                select.disabled = false;
                input.classList.add('d-none');
                input.disabled = true;
                input.value = '';
            }
        }

        function fillIndustri(n, lokasi) {
            const select = document.getElementById('industri_' + n + '_select');
            const input = document.getElementById('industri_' + n + '_input');
            const wrapper = document.getElementById('industri_' + n + '_wrapper');

            // Kalau lokasi belum dipilih, sembunyikan industri
            if (!lokasi || lokasi === 'Lainnya') {
                wrapper.classList.add('d-none');
                select.disabled = true;
                input.disabled = true;
                return;
            }

            // Tampilkan industri
            wrapper.classList.remove('d-none');

            select.innerHTML = '<option value="">Pilih Industri</option>';

            (industriByLokasi[lokasi] || []).forEach(function(name) {
                const opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                select.appendChild(opt);
            });

            const lainnya = document.createElement('option');
            lainnya.value = 'Lainnya';
            lainnya.textContent = 'Lainnya (tulis sendiri)';
            select.appendChild(lainnya);

            select.disabled = false;
            select.value = '';

            input.value = '';
            input.classList.add('d-none');
            input.disabled = true;

            select.classList.remove('d-none');
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleLainnya(1);
            toggleLainnya(2);
            toggleIndustriLainnya(1);
            toggleIndustriLainnya(2);

            const pilihan1 = document.getElementById('pilihan_1_select');
            const pilihan2 = document.getElementById('pilihan_2_select');
            const industri1 = document.getElementById('industri_1_select');
            const industri2 = document.getElementById('industri_2_select');

            @if (old('industri_1') && old('industri_1') != 'Lainnya')
                fillIndustri(1, @json(old('pilihan_1')));
                industri1.value = @json(old('industri_1'));
            @elseif (old('industri_1') && old('industri_1') == 'Lainnya')
                fillIndustri(1, @json(old('pilihan_1')));
                toggleIndustriLainnya(1);
            @else
                fillIndustri(1, pilihan1.value);
            @endif

            @if (old('industri_2') && old('industri_2') != 'Lainnya')
                fillIndustri(2, @json(old('pilihan_2')));
                industri2.value = @json(old('industri_2'));
            @elseif (old('industri_2') && old('industri_2') == 'Lainnya')
                fillIndustri(2, @json(old('pilihan_2')));
                toggleIndustriLainnya(2);
            @else
                fillIndustri(2, pilihan2.value);
            @endif
        });
    </script>
@endpush
