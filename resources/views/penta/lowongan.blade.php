@extends('layouts.app')

@section('content')

<h4 class="mb-4">Bidang Penta - Lowongan Kerja</h4>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

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

<div class="card shadow p-3 mb-4">
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <form method="GET" action="{{ url('/penta/lowongan') }}" class="d-flex gap-2">
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
            <a href="{{ route('penta.export.lowongan', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="btn btn-outline-success shadow-sm">
                <i class="fa fa-file-excel me-1"></i> Export Excel
            </a>
        </form>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportLowongan">
                <i class="fa fa-file-excel me-1"></i> Impor Excel
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahLowonganModal">
                <i class="fa fa-plus me-1"></i> Tambah Manual
            </button>
        </div>
    </div>

    <form id="formBulkDelete" action="{{ route('penta.bulk-delete.lowongan') }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="mb-2">
            <button type="submit" id="btnBulkDelete" class="btn btn-danger btn-sm" style="display: none;">
                <i class="fa fa-trash me-1"></i> Hapus Terpilih
            </button>
        </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th width="4%"><input type="checkbox" id="checkAll"></th>
                    <th>No</th>
                    <th>Nama Perusahaan</th>
                    <th>Posisi / Judul</th>
                    <th>Pendidikan Min.</th>
                    <th>Kuota Awal</th>
                    <th>Sisa Kuota</th>
                    <th>Status</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lowongans as $loker)
                <tr>
                    <td class="text-center"><input type="checkbox" class="checkItem" value="{{ $loker->id }}"></td>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $loker->perusahaan }}</td>
                    <td>
                        <strong class="text-primary">{{ $loker->judul_lowongan }}</strong>
                        @if($loker->tipe_pekerjaan && $loker->tipe_pekerjaan !== '-')
                            <br><small class="text-muted">{{ $loker->tipe_pekerjaan }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $loker->minimal_pendidikan && $loker->minimal_pendidikan !== '-' ? $loker->minimal_pendidikan : '-' }}</td>
                    <td class="text-center">{{ $loker->kuota }}</td>
                    <td class="text-center"><strong>{{ $loker->kuota_sisa }}</strong></td>
                    <td class="text-center">
                        @if(strtolower($loker->status_lowongan) == 'open' || strtolower($loker->status_lowongan) == 'tersedia')
                            <span class="badge bg-success">{{ ucfirst($loker->status_lowongan) }}</span>
                        @elseif(strtolower($loker->status_lowongan) == 'closed' || strtolower($loker->status_lowongan) == 'tutup')
                            <span class="badge bg-secondary">{{ ucfirst($loker->status_lowongan) }}</span>
                        @else
                            <span class="badge bg-danger">{{ $loker->status_lowongan }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailLowonganModal{{ $loker->id }}" title="Detail Lowongan">
                                <i class="fa fa-info-circle"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editLowonganModal{{ $loker->id }}" title="Edit Data">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form action="{{ route('penta.destroy.lowongan', $loker->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data Lowongan ini?');">
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
                    <td colspan="9" class="text-center py-4 text-muted">Belum ada data Lowongan Kerja yang diimpor.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </form>
</div>

<!-- Render Modals Outside Table -->

<!-- Modal Tambah Lowongan -->
<div class="modal fade text-start" id="tambahLowonganModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('penta.store.lowongan') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Lowongan Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Posisi / Judul <span class="text-danger">*</span></label>
                            <input type="text" name="judul_lowongan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" name="perusahaan" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipe Pekerjaan</label>
                            <input type="text" name="tipe_pekerjaan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan Minimal</label>
                            <input type="text" name="minimal_pendidikan" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Kuota Awal <span class="text-danger">*</span></label>
                            <input type="number" name="kuota" class="form-control" required min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sisa Kuota <span class="text-danger">*</span></label>
                            <input type="number" name="kuota_sisa" class="form-control" required min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status_lowongan" class="form-select" required>
                                <option value="open">Open / Tersedia</option>
                                <option value="closed">Closed / Tutup</option>
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
@foreach ($lowongans as $loker)
<!-- Modal Detail Lowongan -->
<div class="modal fade text-start" id="detailLowonganModal{{ $loker->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Lowongan Kerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Posisi / Judul</div>
                    <div class="col-md-8 fw-bold">{{ $loker->judul_lowongan }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Perusahaan</div>
                    <div class="col-md-8">{{ $loker->perusahaan }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Deskripsi Pekerjaan</div>
                    <div class="col-md-8">{{ $loker->deskripsi_pekerjaan ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Kategori Pekerjaan</div>
                    <div class="col-md-8">{{ $loker->kategori_pekerjaan ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Tipe Pekerjaan</div>
                    <div class="col-md-8">{{ $loker->tipe_pekerjaan ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Sektor Pekerjaan</div>
                    <div class="col-md-8">{{ $loker->sektor_pekerjaan ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Fungsi Pekerjaan</div>
                    <div class="col-md-8">{{ $loker->fungsi_pekerjaan ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Kode KBJI</div>
                    <div class="col-md-8">{{ $loker->kode_kbji ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Pendidikan Minimal</div>
                    <div class="col-md-8">{{ $loker->minimal_pendidikan ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Keahlian Diperlukan</div>
                    <div class="col-md-8">{{ $loker->keahlian_diperlukan ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Kebutuhan Disabilitas</div>
                    <div class="col-md-8">{{ $loker->kebutuhan_disabilitas ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Kuota / Sisa</div>
                    <div class="col-md-8">{{ $loker->kuota }} / {{ $loker->kuota_sisa }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Tanggal Posting</div>
                    <div class="col-md-8">{{ $loker->tanggal_posting ? \Carbon\Carbon::parse($loker->tanggal_posting)->format('d M Y') : '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Tanggal Kadaluwarsa</div>
                    <div class="col-md-8">{{ $loker->tanggal_kadaluwarsa ? \Carbon\Carbon::parse($loker->tanggal_kadaluwarsa)->format('d M Y') : '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 text-muted">Status</div>
                    <div class="col-md-8">
                        @if(strtolower($loker->status_lowongan) == 'open' || strtolower($loker->status_lowongan) == 'tersedia')
                            <span class="badge bg-success">{{ ucfirst($loker->status_lowongan) }}</span>
                        @elseif(strtolower($loker->status_lowongan) == 'closed' || strtolower($loker->status_lowongan) == 'tutup')
                            <span class="badge bg-secondary">{{ ucfirst($loker->status_lowongan) }}</span>
                        @else
                            <span class="badge bg-danger">{{ $loker->status_lowongan }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-start" id="editLowonganModal{{ $loker->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('penta.update.lowongan', $loker->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Lowongan Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Posisi / Judul</label>
                        <input type="text" name="judul_lowongan" class="form-control" value="{{ $loker->judul_lowongan }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Perusahaan</label>
                        <input type="text" name="perusahaan" class="form-control" value="{{ $loker->perusahaan }}" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipe Pekerjaan</label>
                            <input type="text" name="tipe_pekerjaan" class="form-control" value="{{ $loker->tipe_pekerjaan }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status_lowongan" class="form-select">
                                <option value="open" {{ in_array(strtolower($loker->status_lowongan), ['open', 'tersedia']) ? 'selected' : '' }}>Open / Tersedia</option>
                                <option value="closed" {{ in_array(strtolower($loker->status_lowongan), ['closed', 'tutup']) ? 'selected' : '' }}>Closed / Tutup</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kuota Awal</label>
                            <input type="number" name="kuota" class="form-control" value="{{ $loker->kuota }}" required min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sisa Kuota</label>
                            <input type="number" name="kuota_sisa" class="form-control" value="{{ $loker->kuota_sisa }}" required min="0">
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

<!-- Modal Import Lowongan -->
<div class="modal fade" id="modalImportLowongan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Impor Data Lowongan Kerja dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('penta.import.lowongan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info py-2">
                        <small>
                            <strong>Info:</strong> Pastikan format header Excel sesuai. 
                            <br>Format: <code>judul_lowongan</code>, <code>perusahaan</code>, <code>kuota</code>, <code>status_lowongan</code>
                            <br>
                            <a href="{{ route('penta.template.lowongan') }}" class="fw-bold text-decoration-none"><i class="fa fa-download"></i> Unduh Template Excel</a>
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