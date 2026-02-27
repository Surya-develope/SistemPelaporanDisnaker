@extends('layouts.app')

@section('content')

<h4 class="mb-4">Bidang Penta - Rekap Penempatan (Diterima)</h4>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card card-modern shadow-sm p-4">
            <h6 class="text-muted">Total Diterima Kerja</h6>
            <h3>{{ $penempatans->count() }} Orang</h3>
        </div>
    </div>
</div>

<div class="card card-modern shadow-sm p-4 mb-4">
    <h6 class="mb-3">Statistik Penempatan (Contoh Data Statis Sementara)</h6>
    <canvas id="pendaftaranChart" height="80"></canvas>
</div>

<div class="card card-modern shadow-sm p-4">
    <div class="d-flex justify-content-between mb-3">
        <h6>Data Pelamar Diterima</h6>
        <div>
            <a href="{{ route('penta.import') }}" class="btn btn-primary btn-sm"><i class="fa fa-upload me-1"></i> Import Excel</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Pelamar</th>
                    <th>Posisi Diterima</th>
                    <th>Nama Perusahaan</th>
                    <th>Pendidikan</th>
                    <th>Tgl. Diterima</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penempatans as $penempatan)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <strong class="text-primary">{{ $penempatan->nama }}</strong><br>
                        <small class="text-muted">{{ $penempatan->email ?? '-' }}</small>
                    </td>
                    <td>
                        {{ $penempatan->judul_lowongan }}<br>
                        <small class="text-muted">KBJI: {{ $penempatan->kode_kbji ?? '-' }}</small>
                    </td>
                    <td>{{ $penempatan->nama_perusahaan }}</td>
                    <td class="text-center">{{ $penempatan->pendidikan_terakhir_pelamar }}</td>
                    <td class="text-center">{{ $penempatan->tanggal_diterima ? \Carbon\Carbon::parse($penempatan->tanggal_diterima)->format('d-m-Y') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data Rekap Penempatan yang diimpor.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection