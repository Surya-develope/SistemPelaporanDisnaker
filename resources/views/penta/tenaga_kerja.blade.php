@extends('layouts.app')

@section('content')

<h4 class="mb-4">Bidang Penta - Pencari Kerja Aktif</h4>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card card-modern shadow-sm p-4">
            <h6 class="text-muted">Total Pencari Kerja</h6>
            <h3>{{ $pencaris->count() }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-modern shadow-sm p-4">
            <h6 class="text-muted">Pria / Wanita</h6>
            <h3>
                {{ $pencaris->where('jenis_kelamin', 'L')->count() }} / 
                {{ $pencaris->where('jenis_kelamin', 'P')->count() }}
            </h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-modern shadow-sm p-4">
            <h6 class="text-muted">Sudah Diverifikasi</h6>
            <h3>{{ $pencaris->where('status_verifikasi', 'DIVERIFIKASI')->count() }}</h3>
        </div>
    </div>
</div>

<div class="card card-modern shadow-sm p-4">
    <div class="d-flex justify-content-between mb-3">
        <h6>Daftar Pencari Kerja Aktif</h6>
        <div>
            <a href="{{ route('penta.import') }}" class="btn btn-primary btn-sm"><i class="fa fa-upload me-1"></i> Import Excel</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Domisili</th>
                    <th>Pendidikan</th>
                    <th>Tgl. Daftar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pencaris as $pencari)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $pencari->nik }}</td>
                    <td>
                        <strong class="text-primary">{{ $pencari->nama }}</strong><br>
                        <small class="text-muted">{{ $pencari->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</small>
                    </td>
                    <td>{{ $pencari->domisili }}</td>
                    <td class="text-center">
                        {{ $pencari->pendidikan_terakhir }}<br>
                        <small class="text-muted">{{ $pencari->jurusan }}</small>
                    </td>
                    <td class="text-center">{{ $pencari->tanggal_daftar ? \Carbon\Carbon::parse($pencari->tanggal_daftar)->format('d-m-Y') : '-' }}</td>
                    <td class="text-center">
                        @if($pencari->status_verifikasi == 'DIVERIFIKASI')
                            <span class="badge bg-success">Diverifikasi</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ $pencari->status_verifikasi }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Belum ada data Pencari Kerja yang diimpor.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection