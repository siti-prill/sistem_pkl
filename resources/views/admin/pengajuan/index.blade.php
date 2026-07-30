@extends('layouts.app')

@section('title', 'Daftar Pengajuan PKL')
@section('page-title', 'Pengajuan PKL')
@section('page-subtitle', 'Kelola pengajuan tempat PKL siswa')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5>Daftar Pengajuan PKL</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Pilihan 1</th>
                                <th>Pilihan 2</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuans as $index => $p)
                                <tr>
                                    <td>{{ $pengajuans->firstItem() + $index }}</td>
                                    <td>{{ $p->siswa->nis }}</td>
                                    <td>{{ $p->siswa->nama_siswa }}</td>
                                    <td>{{ $p->pilihan_1 }}</td>
                                    <td>{{ $p->pilihan_2 }}</td>
                                    <td>
                                        @if ($p->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($p->status == 'diterima')
                                            <span class="badge bg-success">Diterima</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>{{ $p->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.pengajuan.show', $p->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada pengajuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $pengajuans->links() }}
            </div>
        </div>
    </div>
@endsection
