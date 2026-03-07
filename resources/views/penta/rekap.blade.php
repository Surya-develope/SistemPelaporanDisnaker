@extends('layouts.app')

@section('content')

<h4 class="mb-4">Bidang Penta - Rekap Penempatan (Diterima)</h4>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card card-modern shadow-sm p-4">
            <h6 class="text-muted">Total Diterima Kerja</h6>
            <h3>{{ $penempatans->count() }} Orang</h3>
        </div>
    </div>
</div>

</div>

<div class="card shadow p-3 mb-4">
    <form method="GET" action="{{ url('/penta/rekap') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Tampilkan Bulan:</label>
            <select name="bulan" class="form-select">
                <option value="">Semua Bulan</option>
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Tahun:</label>
            <input type="number" name="tahun" class="form-control" value="{{ request('tahun') }}" placeholder="Contoh: 2026">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fa fa-filter me-1"></i> Filter</button>
        </div>
        @if(request('bulan') || request('tahun'))
        <div class="col-md-2">
            <a href="{{ url('/penta/rekap') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
        @endif
        <div class="col-md-auto ms-auto">
            <a href="{{ route('penta.export.rekap', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="btn btn-success">
                <i class="fa fa-file-excel me-1"></i> Export Excel
            </a>
        </div>
    </form>
</div>

<div class="card card-modern shadow-sm p-4">
    <div class="d-flex justify-content-between mb-3">
        <h6>Data Pelamar Diterima</h6>
        <div>
            <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#tambahPenempatanModal">
                <i class="fa fa-plus me-1"></i> Tambah Data Manual
            </button>
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
                    <th width="8%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penempatans as $penempatan)
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
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPenempatanModal{{ $penempatan->id }}" title="Edit Data">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form action="{{ route('penta.destroy.penempatan', $penempatan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat Penempatan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Data">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty 
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Belum ada data Rekap Penempatan yang diimpor.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Render Modals Outside Table -->

<!-- Modal Tambah Penempatan -->
<div class="modal fade text-start" id="tambahPenempatanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('penta.store.penempatan') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Penempatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Pelamar <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan Terakhir <span class="text-danger">*</span></label>
                            <input type="text" name="pendidikan_terakhir_pelamar" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Posisi Diterima <span class="text-danger">*</span></label>
                            <input type="text" name="judul_lowongan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_perusahaan" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@foreach ($penempatans as $penempatan)
<div class="modal fade text-start" id="editPenempatanModal{{ $penempatan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('penta.update.penempatan', $penempatan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Penempatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Pelamar</label>
                        <input type="text" name="nama" class="form-control" value="{{ $penempatan->nama }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Posisi Diterima (Lowongan)</label>
                        <input type="text" name="judul_lowongan" class="form-control" value="{{ $penempatan->judul_lowongan }}" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" class="form-control" value="{{ $penempatan->nama_perusahaan }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir_pelamar" class="form-control" value="{{ $penempatan->pendidikan_terakhir_pelamar }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection