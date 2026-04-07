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
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <form method="GET" action="{{ url('/penta/tenaga-kerja') }}" class="d-flex gap-2">
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
            <a href="{{ route('penta.export.tenaga-kerja', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="btn btn-outline-success shadow-sm">
                <i class="fa fa-file-excel me-1"></i> Export Excel
            </a>
        </form>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportPencari">
                <i class="fa fa-file-excel me-1"></i> Impor Excel
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPencariModal">
                <i class="fa fa-plus me-1"></i> Tambah Manual
            </button>
        </div>
    </div>

    <form id="formBulkDelete" action="{{ route('penta.bulk-delete.pencari') }}" method="POST">
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
                    <td class="text-center"><input type="checkbox" class="checkItem" value="{{ $pencari->id }}"></td>
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
                    <td colspan="9" class="text-center py-4 text-muted">Belum ada data Pencari Kerja yang diimpor.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Render Modals Outside Table -->

<!-- Modal Tambah Pencari Kerja -->
<div class="modal fade text-start" id="tambahPencariModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('penta.store.tenaga-kerja') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pencari Kerja Aktif</h5>
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
                            <label class="form-label">NIK <span class="text-danger">*</span></label>
                            <input type="text" name="nik" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No HP <span class="text-danger">*</span></label>
                            <input type="text" name="no_hp" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tempat Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="text" name="tempat_tanggal_lahir" class="form-control" placeholder="Contoh: Jakarta, 01 Januari 2000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat Domisili <span class="text-danger">*</span></label>
                            <input type="text" name="alamat_domisili" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Domisili <span class="text-danger">*</span></label>
                            <input type="text" name="domisili" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kondisi Fisik</label>
                            <input type="text" name="kondisi_fisik" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Daftar</label>
                            <input type="date" name="tanggal_daftar" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Status Verifikasi <span class="text-danger">*</span></label>
                            <select name="status_verifikasi" class="form-select" required>
                                <option value="BELUM DIVERIFIKASI">BELUM DIVERIFIKASI</option>
                                <option value="DIVERIFIKASI">DIVERIFIKASI</option>
                            </select>
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
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ $pencari->nama }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $pencari->email }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No HP</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ $pencari->no_hp }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tempat Tanggal Lahir</label>
                            <input type="text" name="tempat_tanggal_lahir" class="form-control" value="{{ $pencari->tempat_tanggal_lahir }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat Domisili</label>
                            <input type="text" name="alamat_domisili" class="form-control" value="{{ $pencari->alamat_domisili }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Domisili</label>
                            <input type="text" name="domisili" class="form-control" value="{{ $pencari->domisili }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="L" {{ $pencari->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $pencari->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kondisi Fisik</label>
                            <input type="text" name="kondisi_fisik" class="form-control" value="{{ $pencari->kondisi_fisik }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" class="form-control" value="{{ $pencari->pendidikan_terakhir }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" value="{{ $pencari->jurusan }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Daftar</label>
                            <input type="date" name="tanggal_daftar" class="form-control" value="{{ $pencari->tanggal_daftar ? \Carbon\Carbon::parse($pencari->tanggal_daftar)->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Status Verifikasi</label>
                            <select name="status_verifikasi" class="form-select" required>
                                <option value="DIVERIFIKASI" {{ $pencari->status_verifikasi == 'DIVERIFIKASI' ? 'selected' : '' }}>DIVERIFIKASI</option>
                                <option value="BELUM DIVERIFIKASI" {{ $pencari->status_verifikasi == 'BELUM DIVERIFIKASI' ? 'selected' : '' }}>BELUM DIVERIFIKASI</option>
                            </select>
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

<!-- Modal Import Pencari Kerja -->
<div class="modal fade" id="modalImportPencari" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Impor Data Pencari Kerja Aktif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('penta.import.pencari') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info py-2">
                        <small>
                            <strong>Info:</strong> Pastikan format header Excel sesuai. 
                            <br>Format: <code>nik</code>, <code>nama</code>, <code>email</code>, <code>no_hp</code>, <code>tempat_tanggal_lahir</code>, <code>alamat_domisili</code>, <code>domisili</code>, <code>jenis_kelamin</code>, <code>kondisi_fisik</code>, <code>pendidikan_terakhir</code>, <code>jurusan</code>, <code>tanggal_daftar</code>, <code>status_verifikasi</code>
                            <br>
                            <a href="{{ route('penta.template.pencari') }}" class="fw-bold text-decoration-none"><i class="fa fa-download"></i> Unduh Template</a>
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