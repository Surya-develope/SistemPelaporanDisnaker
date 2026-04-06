@extends('layouts.app')

@section('content')

<h3 class="mb-4">Bidang PHI - Rekap PKWT</h3>

@if(session('success'))
<div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<div class="card shadow p-3">
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <form method="GET" action="{{ route('phi.pkwt') }}" class="d-flex gap-2">
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
            <a href="{{ route('phi.export.pkwt', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="btn btn-outline-success shadow-sm">
                <i class="fa fa-file-excel me-1"></i> Export Excel
            </a>
        </form>

        <div class="d-flex gap-2">
            <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalImportPkwt">
                <i class="fa fa-file-import me-1"></i> Import Excel
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPkwt">
                <i class="fa fa-plus me-1"></i> Tambah Manual
            </button>
        </div>
    </div>

    <form id="formBulkDelete" action="{{ route('phi.bulk-delete.pkwt') }}" method="POST">
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
                    <th width="120">Periode</th>
                    <th width="200">No. Pencatatan</th>
                    <th>Detail Perusahaan</th>
                    <th class="text-center" width="100">Pekerja</th>
                    <th width="150">Jabatan</th>
                    <th width="150">Kontrak</th>
                    <th>Keterangan</th>
                    <th class="text-center ps-3" width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotalPekerja = 0 @endphp
                @foreach($pkwts as $index => $p)
                @php $grandTotalPekerja += $p->total_pekerja @endphp
                <tr>
                    <td class="ps-3"><input type="checkbox" class="checkItem" value="{{ $p->id }}"></td>
                    <td class="text-muted">{{ $index + 1 }}</td>
                    <td>
                        <div class="fw-medium text-dark">{{ DateTime::createFromFormat('!m', $p->bulan)->format('M') }}</div>
                        <div class="small text-muted">{{ $p->tahun }}</div>
                    </td>
                    <td>
                        <span class="font-monospace small bg-light px-2 py-1 rounded border">{{ $p->no_pencatatan ?? '-' }}</span>
                    </td>
                    <td>
                        <div class="fw-bold text-primary mb-0" style="font-size: 1.05rem;">{{ $p->nama_perusahaan ?? '-' }}</div>
                        <div class="small text-muted" title="{{ $p->alamat_pimpinan }}">
                            <i class="fa fa-map-marker-alt me-1"></i> {{ Str::limit($p->alamat_pimpinan, 50) }}
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="fw-bold fs-5">{{ number_format($p->total_pekerja) }}</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">Orang</div>
                    </td>
                    <td>
                        <div class="text-dark small fw-medium text-truncate" style="max-width: 150px;">{{ $p->jabatan ?? '-' }}</div>
                        <div class="small text-muted text-truncate" style="max-width: 150px;">{{ $p->nama_pekerja }}</div>
                    </td>
                    <td>
                        <span class="badge rounded-pill bg-light text-dark border fw-normal">{{ $p->masa_kontrak ?? '-' }}</span>
                    </td>
                    <td>
                        <div class="small text-muted">{{ $p->keterangan ?? '-' }}</div>
                    </td>
                    <td class="text-center">
                        <div class="btn-group shadow-sm border">
                            <button type="button" class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditPkwt{{ $p->id }}">
                                <i class="fa fa-pencil text-primary"></i>
                            </button>
                            <form action="{{ route('phi.destroy.pkwt', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
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
                
                @if($pkwts->isEmpty())
                <tr>
                    <td colspan="10" class="text-center py-5 text-muted">
                        <i class="fa fa-folder-open fs-1 d-block mb-3 opacity-25"></i>
                        Belum ada data rekapan untuk periode ini.
                    </td>
                </tr>
                @else
                <tr class="table-info border-top-2">
                    <td colspan="5" class="text-end fw-bold py-3 ps-3">TOTAL KESELURUHAN PEKERJA :</td>
                    <td class="text-center py-3">
                        <div class="fw-bolder fs-4 text-dark">{{ number_format($grandTotalPekerja) }}</div>
                        <div class="small text-uppercase fw-bold text-muted">Orang</div>
                    </td>
                    <td colspan="3"></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    </form>
</div>

<!-- Modal Import PKWT -->
<div class="modal fade" id="modalImportPkwt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Impor Data PKWT dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('phi.import.pkwt') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Pilih Bulan Laporan <span class="text-danger">*</span></label>
                        <select name="bulan" class="form-select" required>
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Tahun Laporan <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Upload File Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".xls,.xlsx,.csv" required>
                    </div>
                    <div class="alert alert-info py-2">
                        <i class="fa fa-info-circle me-1"></i> Pastikan format header Excel sesuai template.
                        <a href="{{ route('phi.template.pkwt') }}" class="d-block mt-2 fw-bold text-success border-bottom border-success" style="width: fit-content;"><i class="fa fa-download me-1"></i> Download Template</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-upload me-1"></i> Mulai Impor</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($pkwts as $p)
<!-- Modal Edit PKWT -->
<div class="modal fade" id="modalEditPkwt{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data PKWT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('phi.update.pkwt', $p->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Bulan (1-12)</label>
                            <input type="number" name="bulan" class="form-control" value="{{ $p->bulan }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tahun</label>
                            <input type="number" name="tahun" class="form-control" value="{{ $p->tahun }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nomor Pencatatan</label>
                            <input type="text" name="no_pencatatan" class="form-control" value="{{ $p->no_pencatatan }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" class="form-control" value="{{ $p->nama_perusahaan }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Alamat / Pimpinan</label>
                        <textarea name="alamat_pimpinan" class="form-control" rows="2">{{ $p->alamat_pimpinan }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label>Nama Pekerja</label>
                            <input type="text" name="nama_pekerja" class="form-control" value="{{ $p->nama_pekerja }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total Pekerja</label>
                            <input type="number" name="total_pekerja" class="form-control" value="{{ $p->total_pekerja }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" value="{{ $p->jabatan }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Masa Kontrak</label>
                            <input type="text" name="masa_kontrak" class="form-control" value="{{ $p->masa_kontrak }}">
                        </div>
                    </div>
                    <div class="mb-3">
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

<!-- Modal Tambah PKWT -->
<div class="modal fade" id="modalTambahPkwt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data PKWT Secara Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('phi.store.pkwt') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Bulan (1-12)</label>
                            <input type="number" name="bulan" class="form-control" value="{{ date('n') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tahun</label>
                            <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nomor Pencatatan</label>
                            <input type="text" name="no_pencatatan" class="form-control" placeholder="Contoh: 560/123/PHI-PKWT/2024">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Alamat / Pimpinan</label>
                        <textarea name="alamat_pimpinan" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label>Nama Pekerja</label>
                            <input type="text" name="nama_pekerja" class="form-control" placeholder="Isi nama atau 'Terlampir'">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Total Pekerja</label>
                            <input type="number" name="total_pekerja" class="form-control" value="0" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Jabatan</label>
                            <input type="text" name="jabatan" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Masa Kontrak</label>
                            <input type="text" name="masa_kontrak" class="form-control" placeholder="Contoh: 1 Tahun">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2"></textarea>
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

@endsection