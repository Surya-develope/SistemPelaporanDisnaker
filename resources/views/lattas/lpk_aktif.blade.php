@extends('layouts.app')

@section('content')
<h4 class="mb-4">Rekap LPK Aktif</h4>

@if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
@endif

<div class="card shadow p-3 mb-4">
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <form method="GET" action="{{ url('/lattas/lpk-aktif') }}" class="d-flex gap-2">
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
            <a href="{{ route('lattas.export.lpk_aktif', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="btn btn-outline-success shadow-sm">
                <i class="fa fa-file-excel me-1"></i> Export Excel
            </a>
        </form>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportLpk">
                <i class="fa fa-file-excel me-1"></i> Impor Excel
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahLpkModal">
                <i class="fa fa-plus me-1"></i> Tambah Manual
            </button>
        </div>
    </div>

    <form id="formBulkDelete" action="{{ route('lattas.bulk-delete.lpk') }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="mb-2">
            <button type="submit" id="btnBulkDelete" class="btn btn-danger btn-sm" style="display: none;">
                <i class="fa fa-trash me-1"></i> Hapus Terpilih
            </button>
        </div>

    <div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th width="4%"><input type="checkbox" id="checkAll"></th>
                <th>No</th>
                <th>Nama LPK</th>
                <th>Nama Pimpinan</th>
                <th>Tahun Berdiri</th>
                <th>Alamat</th>
                <th>Status</th>
                <th width="10%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lpks as $lpk)
            <tr>
                <td><input type="checkbox" class="checkItem" value="{{ $lpk->id }}"></td>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $lpk->nama_lpk }}</td>
                <td>{{ $lpk->nama_pimpinan }}</td>
                <td>{{ $lpk->tahun_berdiri }}</td>
                <td>{{ $lpk->alamat }}</td>
                <td><span class="badge bg-success">Aktif</span></td>
                <td>
                    <div class="d-flex gap-1 justify-content-center">
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editLpkModal{{ $lpk->id }}" title="Edit Data">
                            <i class="fa fa-edit"></i>
                        </button>
                        <form action="{{ route('lattas.destroy.lpk', $lpk->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus LPK ini? Semua riwayat pelatihannya juga akan ikut terhapus!');">
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
                <td colspan="8" class="text-center">Belum ada data LPK Aktif</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    </form>
</div>

<!-- Render Modals Outside Table -->

<!-- Modal Tambah LPK -->
<div class="modal fade text-start" id="tambahLpkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('lattas.store.lpk') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data LPK Aktif</h5>
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
                            <label class="form-label">Nama LPK <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lpk" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Pimpinan</label>
                            <input type="text" name="nama_pimpinan" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tahun Berdiri</label>
                            <input type="number" name="tahun_berdiri" class="form-control" min="1900">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" readonly>
                                <option value="aktif" selected>Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"></textarea>
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
@foreach ($lpks as $lpk)
<div class="modal fade text-start" id="editLpkModal{{ $lpk->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('lattas.update.lpk', $lpk->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data LPK</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama LPK</label>
                        <input type="text" name="nama_lpk" class="form-control" value="{{ $lpk->nama_lpk }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pimpinan</label>
                        <input type="text" name="nama_pimpinan" class="form-control" value="{{ $lpk->nama_pimpinan }}">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tahun Berdiri</label>
                            <input type="number" name="tahun_berdiri" class="form-control" value="{{ $lpk->tahun_berdiri }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="aktif" {{ $lpk->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak aktif" {{ $lpk->status == 'tidak aktif' ? 'selected' : '' }}>Non Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2">{{ $lpk->alamat }}</textarea>
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

<!-- Modal Import LPK -->
<div class="modal fade" id="modalImportLpk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Impor Data Master LPK dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('lattas.import.master') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info py-2">
                        <small>
                            <strong>Info:</strong> Pastikan format header Excel sesuai. 
                            <br>Format: <code>Nama LPK</code>, <code>Nama Pimpinan</code>, <code>Tahun Berdiri</code>, <code>Alamat</code>, <code>Status</code>
                            <br>
                            <a href="{{ route('lattas.template.lpk') }}" class="fw-bold text-decoration-none"><i class="fa fa-download"></i> Unduh Template Excel</a>
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