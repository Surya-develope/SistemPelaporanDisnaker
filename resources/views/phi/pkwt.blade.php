@extends('layouts.app')

@section('content')

<h3 class="mb-4">Bidang PHI - Rekap PKWT</h3>

@if(session('success'))
<div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<div class="card shadow p-3">
    <div class="d-flex justify-content-between mb-3">
        <form method="GET" action="{{ url('/phi/pkwt') }}" class="d-flex gap-2 w-50">
            <select name="bulan" class="form-select w-auto">
                <option value="">Semua Bulan</option>
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endforeach
            </select>
            <select name="tahun" class="form-select w-auto">
                <option value="">Semua Tahun</option>
                @for ($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-outline-primary">Filter</button>
        </form>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPkwt">+ Tambah Data</button>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Total Perusahaan</th>
                <th>Total Pekerja</th>
                <th>File Lampiran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pkwts as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ DateTime::createFromFormat('!m', $p->bulan)->format('F') }}</td>
                <td>{{ $p->tahun }}</td>
                <td>{{ $p->total_perusahaan }}</td>
                <td>{{ $p->total_pekerja }}</td>
                <td class="text-center">
                    @if($p->file_path)
                    <a href="{{ asset('storage/' . $p->file_path) }}" target="_blank" class="btn btn-sm btn-outline-success" title="Lihat File">
                        <i class="fa fa-file-alt"></i>
                    </a>
                    @else
                    -
                    @endif
                </td>
                <td class="text-center">
                    <div class="d-flex gap-1 justify-content-center">
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditPkwt{{ $p->id }}" title="Edit Data">
                            <i class="fa fa-edit"></i>
                        </button>
                        <form action="{{ route('phi.destroy.pkwt', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Data">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            
            @if($pkwts->isEmpty())
            <tr>
                <td colspan="7" class="text-center">Belum ada data rekapan</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

@foreach($pkwts as $p)
<!-- Modal Edit PKWT -->
<div class="modal fade" id="modalEditPkwt{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data PKWT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('phi.update.pkwt', $p->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Bulan (1-12)</label>
                        <input type="number" name="bulan" class="form-control" value="{{ $p->bulan }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Tahun</label>
                        <input type="number" name="tahun" class="form-control" value="{{ $p->tahun }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Total Perusahaan</label>
                        <input type="number" name="total_perusahaan" class="form-control" value="{{ $p->total_perusahaan }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Total Pekerja</label>
                        <input type="number" name="total_pekerja" class="form-control" value="{{ $p->total_pekerja }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Update File Lampiran (Opsional)</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file.</small>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data PKWT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('phi.store.pkwt') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Bulan (1-12)</label>
                        <input type="number" name="bulan" class="form-control" value="{{ date('n') }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Tahun</label>
                        <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Total Perusahaan</label>
                        <input type="number" name="total_perusahaan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Total Pekerja</label>
                        <input type="number" name="total_pekerja" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>File Lampiran</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv" required>
                        <small class="text-muted">Max: 5MB</small>
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