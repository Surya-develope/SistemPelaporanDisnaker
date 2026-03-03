@extends('layouts.app')

@section('content')

<h4 class="mb-4">Bidang Penta - Pencari Kerja Aktif</h4>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

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

<div class="card shadow p-3 mb-4">
    <form method="GET" action="{{ url('/penta/tenaga-kerja') }}" class="row g-3 align-items-end">
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
            <a href="{{ url('/penta/tenaga-kerja') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
        @endif
    </form>
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
                    <th width="8%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pencaris as $pencari)
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
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPencariModal{{ $pencari->id }}" title="Edit Data">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form action="{{ route('penta.destroy.pencari', $pencari->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data Pencari Kerja ini?');">
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
                    <td colspan="8" class="text-center py-4 text-muted">Belum ada data Pencari Kerja yang diimpor.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Render Modals Outside Table -->
@foreach ($pencaris as $pencari)
<div class="modal fade text-start" id="editPencariModal{{ $pencari->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('penta.update.pencari', $pencari->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Pencari Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control" value="{{ $pencari->nik }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Verifikasi</label>
                            <select name="status_verifikasi" class="form-select">
                                <option value="DIVERIFIKASI" {{ $pencari->status_verifikasi == 'DIVERIFIKASI' ? 'selected' : '' }}>DIVERIFIKASI</option>
                                <option value="BELUM DIVERIFIKASI" {{ $pencari->status_verifikasi == 'BELUM DIVERIFIKASI' ? 'selected' : '' }}>BELUM DIVERIFIKASI</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="{{ $pencari->nama }}" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select">
                                <option value="L" {{ $pencari->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $pencari->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" class="form-control" value="{{ $pencari->pendidikan_terakhir }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Domisili</label>
                        <input type="text" name="domisili" class="form-control" value="{{ $pencari->domisili }}" required>
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