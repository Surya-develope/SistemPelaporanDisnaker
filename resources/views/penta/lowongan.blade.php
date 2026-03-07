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
    <form method="GET" action="{{ url('/penta/lowongan') }}" class="row g-3 align-items-end">
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
            <a href="{{ url('/penta/lowongan') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
        @endif
        <div class="col-md-auto ms-auto">
            <a href="{{ route('penta.export.lowongan', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="btn btn-success">
                <i class="fa fa-file-excel me-1"></i> Export Excel
            </a>
        </div>
    </form>
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
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lowongans as $loker)
                <tr>
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
                    <td colspan="8" class="text-center py-4 text-muted">Belum ada data Lowongan Kerja yang diimpor.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Render Modals Outside Table -->
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

@endsection