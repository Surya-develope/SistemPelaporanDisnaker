@extends('layouts.app')

@section('content')

<h3 class="mb-4">Bidang PHI - Rekap Peraturan Perusahaan</h3>

@if(session('success'))
<div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<div class="card shadow p-3">
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <form method="GET" action="{{ route('phi.peraturan') }}" class="d-flex gap-2">
            <select name="bulan" class="form-select w-auto shadow-sm border-primary-subtle" onchange="this.form.submit()">
                <option value="">Semua Bulan</option>
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endforeach
            </select>
            <select name="tahun" class="form-select w-auto shadow-sm border-primary-subtle" onchange="this.form.submit()">
                <option value="">Semua Tahun</option>
                @for ($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <input type="text" name="keyword" class="form-control shadow-sm border-primary-subtle w-auto" placeholder="Cari nama perusahaan..." value="{{ request('keyword') }}">
            <button type="submit" class="btn btn-outline-primary shadow-sm" style="display: none;">
                <i class="fa fa-search"></i> Cari
            </button>
            <a href="{{ route('phi.export.peraturan', ['bulan' => request('bulan'), 'tahun' => request('tahun'), 'keyword' => request('keyword')]) }}" class="btn btn-outline-success shadow-sm">
                <i class="fa fa-file-excel me-1"></i> Export Excel
            </a>
        </form>

        <div class="d-flex gap-2">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportPeraturan">
                <i class="fa fa-file-excel me-1"></i> Impor Excel
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPeraturan">
                <i class="fa fa-plus me-1"></i> Tambah Manual
            </button>
        </div>
    </div>

    <form id="formBulkDelete" action="{{ route('phi.bulk-delete.peraturan') }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="mb-2">
            <button type="submit" id="btnBulkDelete" class="btn btn-danger btn-sm" style="display: none;">
                <i class="fa fa-trash me-1"></i> Hapus Terpilih
            </button>
        </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border-top">
            <thead class="table-light">
                <tr class="text-secondary fw-semibold">
                    <th class="ps-3" width="4%"><input type="checkbox" id="checkAll"></th>
                    <th width="50">No</th>
                    <th width="300">Deskripsi Perusahaan</th>
                    <th width="150" class="text-center">Sektor Usaha</th>
                    <th class="text-center" width="120">Pekerja</th>
                    <th width="200">Informasi SK PP</th>
                    <th width="180" class="text-center">Masa Berlaku</th>
                    <th class="text-center ps-3" width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peraturans as $index => $p)
                <tr>
                    <td class="ps-3"><input type="checkbox" class="checkItem" value="{{ $p->id }}"></td>
                    <td class="text-muted">{{ $index + 1 }}</td>
                    <td>
                        <div class="fw-bold text-primary mb-0" style="font-size: 1.05rem;">{{ $p->nama_perusahaan }}</div>
                        @if($p->nama_pimpinan)
                        <div class="small text-muted mb-1"><i class="fa fa-user-tie me-1"></i> {{ $p->nama_pimpinan }}</div>
                        @endif
                        <div class="small text-muted" title="{{ $p->alamat_perusahaan }}">
                            <i class="fa fa-map-marker-alt me-1"></i> {{ Str::limit($p->alamat_perusahaan, 50) }}
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-secondary text-white fw-normal">{{ $p->sektor_usaha ?? '-' }}</span>
                    </td>
                    <td class="text-center">
                        <div class="fw-bold fs-5">{{ $p->total_pekerja }}</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">
                            L: {{ $p->pekerja_lk }} | P: {{ $p->pekerja_pr }}
                        </div>
                    </td>
                    <td>
                        <div class="small fw-bold text-dark mb-1">Status: 
                            <span class="badge {{ $p->status_pp == 'Baru' ? 'bg-success' : 'bg-info text-dark' }}">{{ $p->status_pp }}</span>
                        </div>
                        <div class="small text-muted font-monospace">{{ $p->no_sk ?? '-' }}</div>
                        <div class="small text-muted">PP Ke : <b>{{ $p->pp_ke ?? '-' }}</b></div>
                    </td>
                    <td class="text-center">
                        <div class="badge rounded-pill border fw-normal text-dark">
                            {{ $p->masa_berlaku_awal ? \Carbon\Carbon::parse($p->masa_berlaku_awal)->format('d/m/Y') : '-' }} <br> s/d <br>
                            {{ $p->masa_berlaku_akhir ? \Carbon\Carbon::parse($p->masa_berlaku_akhir)->format('d/m/Y') : '-' }}
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="btn-group shadow-sm border">
                            <button type="button" class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditPeraturan{{ $p->id }}">
                                <i class="fa fa-pencil text-primary"></i>
                            </button>
                            <form action="{{ route('phi.destroy.peraturan', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data PP dari {{ $p->nama_perusahaan }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-white btn-sm border-start">
                                    <i class="fa fa-trash text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                
                @if($peraturans->isEmpty())
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="fa fa-folder-open fs-1 d-block mb-3 opacity-25"></i>
                        Belum ada data Rekap Peraturan Perusahaan.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    </form>
</div>

<!-- Modal Import Peraturan -->
<div class="modal fade" id="modalImportPeraturan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Impor Data Peraturan Perusahaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('phi.import.peraturan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info py-2">
                        <small>
                            <strong>Info:</strong> Pastikan format header Excel sesuai template agar data tidak error. Format tanggal wajib menggunakan (YYYY-MM-DD) atau format date Excel.<br><br>
                            <a href="{{ route('phi.template.peraturan') }}" class="fw-bold text-decoration-none btn btn-sm btn-light border"><i class="fa fa-download"></i> Unduh Template Excel</a>
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

<!-- Modal Tambah Peraturan -->
<div class="modal fade" id="modalTambahPeraturan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Peraturan Perusahaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('phi.store.peraturan') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row bg-light p-2 rounded mb-3 border">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="fw-bold small text-muted">Bulan Pencatatan</label>
                            <select name="bulan" class="form-select" required>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold small text-muted">Tahun Pencatatan</label>
                            <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Informasi Perusahaan</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Nama Pimpinan</label>
                            <input type="text" name="nama_pimpinan" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Sektor Usaha</label>
                            <input type="text" name="sektor_usaha" class="form-control" placeholder="Contoh: Ritel, Jasa, Manufaktur">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Alamat Perusahaan</label>
                            <input type="text" name="alamat_perusahaan" class="form-control">
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mt-2 mb-3 border-bottom pb-2">Jumlah Pekerja</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Laki-laki</label>
                            <input type="number" name="pekerja_lk" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Perempuan</label>
                            <input type="number" name="pekerja_pr" class="form-control" value="0" min="0">
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mt-2 mb-3 border-bottom pb-2">Informasi Peraturan Perusahaan (PP)</h6>
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3">
                            <label>Status PP</label>
                            <select name="status_pp" class="form-select" required>
                                <option value="Baru">Baru</option>
                                <option value="Perpanjangan">Perpanjangan</option>
                            </select>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label>No. SK Masa Berlaku</label>
                            <input type="text" name="no_sk" class="form-control" placeholder="Contoh: B.500.15...">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>PP Ke</label>
                            <input type="number" name="pp_ke" class="form-control" placeholder="Contoh: 1" min="1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Masa Berlaku Awal</label>
                            <input type="date" name="masa_berlaku_awal" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Masa Berlaku Akhir</label>
                            <input type="date" name="masa_berlaku_akhir" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($peraturans as $p)
<!-- Modal Edit Peraturan -->
<div class="modal fade" id="modalEditPeraturan{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Peraturan Perusahaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('phi.update.peraturan', $p->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row bg-light p-2 rounded mb-3 border">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="fw-bold small text-muted">Bulan Pencatatan</label>
                            <select name="bulan" class="form-select" required>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $p->bulan == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold small text-muted">Tahun Pencatatan</label>
                            <input type="number" name="tahun" class="form-control" value="{{ $p->tahun ?? date('Y') }}" required>
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Informasi Perusahaan</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" class="form-control" value="{{ $p->nama_perusahaan }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Nama Pimpinan</label>
                            <input type="text" name="nama_pimpinan" class="form-control" value="{{ $p->nama_pimpinan }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Sektor Usaha</label>
                            <input type="text" name="sektor_usaha" class="form-control" value="{{ $p->sektor_usaha }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Alamat Perusahaan</label>
                            <input type="text" name="alamat_perusahaan" class="form-control" value="{{ $p->alamat_perusahaan }}">
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mt-2 mb-3 border-bottom pb-2">Jumlah Pekerja</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Laki-laki</label>
                            <input type="number" name="pekerja_lk" class="form-control" value="{{ $p->pekerja_lk }}" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Perempuan</label>
                            <input type="number" name="pekerja_pr" class="form-control" value="{{ $p->pekerja_pr }}" min="0">
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mt-2 mb-3 border-bottom pb-2">Informasi Peraturan Perusahaan (PP)</h6>
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3">
                            <label>Status PP</label>
                            <select name="status_pp" class="form-select" required>
                                <option value="Baru" {{ $p->status_pp == 'Baru' ? 'selected' : '' }}>Baru</option>
                                <option value="Perpanjangan" {{ $p->status_pp == 'Perpanjangan' ? 'selected' : '' }}>Perpanjangan</option>
                            </select>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label>No. SK Masa Berlaku</label>
                            <input type="text" name="no_sk" class="form-control" value="{{ $p->no_sk }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>PP Ke</label>
                            <input type="number" name="pp_ke" class="form-control" value="{{ $p->pp_ke }}" min="1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Masa Berlaku Awal</label>
                            <input type="date" name="masa_berlaku_awal" class="form-control" value="{{ $p->masa_berlaku_awal }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Masa Berlaku Akhir</label>
                            <input type="date" name="masa_berlaku_akhir" class="form-control" value="{{ $p->masa_berlaku_akhir }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2">{{ $p->keterangan }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection