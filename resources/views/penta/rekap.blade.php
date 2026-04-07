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

<div class="card shadow p-3 mb-4">
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <form method="GET" action="{{ url('/penta/rekap') }}" class="d-flex gap-2">
            <select name="bulan" class="form-select w-auto shadow-sm border-primary-subtle" onchange="this.form.submit()">
                <option value="">Semua Bulan</option>
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                @endfor
            </select>
            <select name="tahun" class="form-select w-auto shadow-sm border-primary-subtle" onchange="this.form.submit()">
                <option value="">Semua Tahun</option>
                @for ($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <a href="{{ route('penta.export.rekap', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="btn btn-outline-success shadow-sm">
                <i class="fa fa-file-excel me-1"></i> Export Excel
            </a>
        </form>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportPenempatan">
                <i class="fa fa-file-excel me-1"></i> Impor Excel
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPenempatanModal">
                <i class="fa fa-plus me-1"></i> Tambah Manual
            </button>
        </div>
    </div>

    <form id="formBulkDelete" action="{{ route('penta.bulk-delete.penempatan') }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="mb-2">
            <button type="submit" id="btnBulkDelete" class="btn btn-danger btn-sm" style="display: none;">
                <i class="fa fa-trash me-1"></i> Hapus Terpilih
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th width="4%"><input type="checkbox" id="checkAll"></th>
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
                    <td class="text-center"><input type="checkbox" class="checkItem" value="{{ $penempatan->id }}"></td>
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
                    <td colspan="8" class="text-center py-4 text-muted">Belum ada data Rekap Penempatan yang diimpor.</td>
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
                            <label class="form-label">Bulan Pencatatan</label>
                            <select name="bulan" class="form-select" required>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tahun Pencatatan</label>
                            <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Pelamar <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Pelamar</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan Terakhir <span class="text-danger">*</span></label>
                            <input type="text" name="pendidikan_terakhir_pelamar" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Domisili Pelamar</label>
                            <input type="text" name="domisili_pelamar" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Posisi Diterima <span class="text-danger">*</span></label>
                            <input type="text" name="judul_lowongan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode KBJI</label>
                            <input type="text" name="kode_kbji" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_perusahaan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Domisili Lowongan</label>
                            <input type="text" name="domisili_lowongan" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan Minimal Loker</label>
                            <input type="text" name="pendidikan_minimal_loker" class="form-control">
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Melamar</label>
                            <input type="date" name="tanggal_melamar" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Diterima</label>
                            <input type="date" name="tanggal_diterima" class="form-control">
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('penta.update.penempatan', $penempatan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Penempatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Pelamar <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" value="{{ $penempatan->nama }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Pelamar</label>
                            <input type="email" name="email" class="form-control" value="{{ $penempatan->email }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan Terakhir <span class="text-danger">*</span></label>
                            <input type="text" name="pendidikan_terakhir_pelamar" class="form-control" value="{{ $penempatan->pendidikan_terakhir_pelamar }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Domisili Pelamar</label>
                            <input type="text" name="domisili_pelamar" class="form-control" value="{{ $penempatan->domisili_pelamar }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Posisi Diterima <span class="text-danger">*</span></label>
                            <input type="text" name="judul_lowongan" class="form-control" value="{{ $penempatan->judul_lowongan }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode KBJI</label>
                            <input type="text" name="kode_kbji" class="form-control" value="{{ $penempatan->kode_kbji }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_perusahaan" class="form-control" value="{{ $penempatan->nama_perusahaan }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Domisili Lowongan</label>
                            <input type="text" name="domisili_lowongan" class="form-control" value="{{ $penempatan->domisili_lowongan }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan Minimal Loker</label>
                            <input type="text" name="pendidikan_minimal_loker" class="form-control" value="{{ $penempatan->pendidikan_minimal_loker }}">
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Melamar</label>
                            <input type="date" name="tanggal_melamar" class="form-control" value="{{ $penempatan->tanggal_melamar }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Diterima</label>
                            <input type="date" name="tanggal_diterima" class="form-control" value="{{ $penempatan->tanggal_diterima }}">
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

<!-- Modal Import Penempatan -->
<div class="modal fade" id="modalImportPenempatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Impor Data Rekap Penempatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('penta.import.penempatan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info py-2">
                        <small>
                            <strong>Info:</strong> Pastikan format header Excel sesuai. 
                            <br>Format: <code>nama</code>, <code>nama_perusahaan</code>, <code>judul_lowongan</code>, <code>tanggal_diterima</code>
                            <br>
                            <a href="{{ route('penta.template.penempatan') }}" class="fw-bold text-decoration-none"><i class="fa fa-download"></i> Unduh Template</a>
                        </small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Bulan Laporan</label>
                            <select name="bulan" class="form-select" required>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tahun Laporan</label>
                            <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Pilih File Excel (.xlsx, .xls, .csv)</label>
                        <input type="file" name="file" class="form-control" accept=".xls,.xlsx,.csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Mulai Impor</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection