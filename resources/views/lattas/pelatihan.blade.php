@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="m-0">Bidang Lattas - Rekap Pelatihan</h3>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="card shadow p-3 mb-4">
    <form method="GET" action="{{ url('/lattas/pelatihan') }}" class="row g-3 align-items-end">
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
            <a href="{{ url('/lattas/pelatihan') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
        @endif
        <div class="col-md-auto ms-auto">
            <a href="{{ route('lattas.export.pelatihan', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="btn btn-success">
                <i class="fa fa-file-excel me-1"></i> Export Excel
            </a>
        </div>
    </form>
</div>

<div class="card shadow p-3">
    <div class="d-flex justify-content-between mb-3">
        <h6>Daftar Program Pelatihan</h6>
        <div>
            <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#tambahTrainingModal">
                <i class="fa fa-plus me-1"></i> Tambah Data Manual
            </button>
            <a href="{{ route('lattas.import') }}" class="btn btn-primary btn-sm"><i class="fa fa-upload me-1"></i> Import Excel</a>
        </div>
    </div>
    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-light">
            <tr>
                <th width="5%">No</th>
                <th width="15%">Bulan / Tahun</th>
                <th>Nama LPK/Lembaga</th>
                <th>Program Pelatihan (PORGLAT)</th>
                <th>Jumlah Peserta</th>
                <th>Jumlah Paket</th>
                <th width="8%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($trainings as $training)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $training->bulan ? date('F', mktime(0, 0, 0, $training->bulan, 1)) : '-' }} {{ $training->tahun ?? '' }}</td>
                <td class="text-start">{{ $training->nama_lpk ?? '-' }}</td>
                <td class="text-start">{{ $training->program_pelatihan }}</td>
                <td>{{ $training->jumlah_peserta }} Orang</td>
                <td>{{ $training->jumlah_paket }} Paket</td>
                <td>
                    <div class="d-flex gap-1 justify-content-center">
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editTrainingModal{{ $training->id }}" title="Edit Data">
                            <i class="fa fa-edit"></i>
                        </button>
                        <form action="{{ route('lattas.destroy.training', $training->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pelatihan LPK ini?');">
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
                <td colspan="8" class="text-center text-muted py-4">Belum ada data Rekap Pelatihan untuk periode tersebut.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot class="table-group-divider fw-bold bg-light">
            <tr>
                <td colspan="4" class="text-end">JUMLAH TOTAL</td>
                <td>{{ $trainings->sum('jumlah_peserta') }} Orang</td>
                <td>{{ $trainings->sum('jumlah_paket') }} Paket</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Render Modals Outside Table -->

<!-- Modal Tambah Pelatihan -->
<div class="modal fade text-start" id="tambahTrainingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('lattas.store.training') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Rekap Pelatihan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama LPK / Lembaga Penyelenggara <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lpk" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program Pelatihan <span class="text-danger">*</span></label>
                        <input type="text" name="program_pelatihan" class="form-control" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Peserta <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_peserta" class="form-control" required min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Paket <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_paket" class="form-control" required min="0">
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
@foreach ($trainings as $training)
<div class="modal fade text-start" id="editTrainingModal{{ $training->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('lattas.update.training', $training->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Pelatihan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama LPK / Lembaga Penyelenggara</label>
                        <input type="text" name="nama_lpk" class="form-control" value="{{ $training->nama_lpk }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program Pelatihan</label>
                        <input type="text" name="program_pelatihan" class="form-control" value="{{ $training->program_pelatihan }}" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Peserta</label>
                            <input type="number" name="jumlah_peserta" class="form-control" value="{{ $training->jumlah_peserta }}" required min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Paket</label>
                            <input type="number" name="jumlah_paket" class="form-control" value="{{ $training->jumlah_paket }}" required min="0">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Bulan Pelaporan</label>
                            <select name="bulan" class="form-select">
                                @for($i=1; $i<=12; $i++)
                                    <option value="{{ $i }}" {{ $training->bulan == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tahun Pelaporan</label>
                            <input type="number" name="tahun" class="form-control" value="{{ $training->tahun }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection