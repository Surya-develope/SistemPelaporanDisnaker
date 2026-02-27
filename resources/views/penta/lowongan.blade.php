@extends('layouts.app')

@section('content')

<h4 class="mb-4">Bidang Penta - Lowongan Kerja</h4>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card card-modern shadow-sm p-4">
            <h6 class="text-muted">Total Lowongan Aktif</h6>
            <h3>{{ $lowongans->where('status_lowongan', 'open')->count() }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-modern shadow-sm p-4">
            <h6 class="text-muted">Perusahaan Terdaftar</h6>
            <h3>{{ $lowongans->unique('perusahaan')->count() }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-modern shadow-sm p-4">
            <h6 class="text-muted">Total Kuota Tersedia</h6>
            <h3>{{ $lowongans->sum('kuota_sisa') }}</h3>
        </div>
    </div>
</div>

<div class="card card-modern shadow-sm p-4">
    <div class="d-flex justify-content-between mb-3">
        <h6>Daftar Lowongan Pekerjaan</h6>
        <div>
            <a href="{{ route('penta.import') }}" class="btn btn-primary btn-sm"><i class="fa fa-upload me-1"></i> Import Excel</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Perusahaan</th>
                    <th>Posisi / Judul</th>
                    <th>Pendidikan Min.</th>
                    <th>Kuota Awal</th>
                    <th>Sisa Kuota</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lowongans as $loker)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $loker->perusahaan }}</td>
                    <td>
                        <strong class="text-primary">{{ $loker->judul_lowongan }}</strong><br>
                        <small class="text-muted">{{ $loker->tipe_pekerjaan }}</small>
                    </td>
                    <td class="text-center">{{ $loker->minimal_pendidikan ?? '-' }}</td>
                    <td class="text-center">{{ $loker->kuota }}</td>
                    <td class="text-center"><strong>{{ $loker->kuota_sisa }}</strong></td>
                    <td class="text-center">
                        @if(strtolower($loker->status_lowongan) == 'open')
                            <span class="badge bg-success">Open</span>
                        @else
                            <span class="badge bg-danger">{{ $loker->status_lowongan }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Belum ada data Lowongan Kerja yang diimpor.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection