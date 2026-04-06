@extends('layouts.app')

@section('content')

<h4 class="mb-4">Data Pengaduan Kasus (PHI)</h4>

@if(session('success'))
<div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<div class="card card-modern p-4 shadow-sm">
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <form method="GET" action="{{ url('/phi/pengaduan') }}" class="d-flex gap-2">
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
            <select name="status_kasus" class="form-select w-auto shadow-sm border-primary-subtle" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="berjalan" {{ request('status_kasus') == 'berjalan' ? 'selected' : '' }}>Masih Berjalan</option>
                <option value="selesai" {{ request('status_kasus') == 'selesai' ? 'selected' : '' }}>Sudah Selesai</option>
            </select>
            <a href="{{ route('phi.export.pengaduan', ['bulan' => request('bulan'), 'tahun' => request('tahun'), 'status_kasus' => request('status_kasus')]) }}" class="btn btn-outline-success shadow-sm">
                <i class="fa fa-file-excel me-1"></i> Export Excel
            </a>
        </form>

        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPengaduan">
                <i class="fa fa-plus me-1"></i> Tambah Kasus
            </button>
        </div>
    </div>

    <form id="formBulkDelete" action="{{ route('phi.bulk-delete.pengaduan') }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="mb-2">
            <button type="submit" id="btnBulkDelete" class="btn btn-danger btn-sm" style="display: none;">
                <i class="fa fa-trash me-1"></i> Hapus Terpilih
            </button>
        </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center table-sm">
            <thead class="table-light align-middle">
                <tr>
                    <th width="3%"><input type="checkbox" id="checkAll"></th>
                    <th width="3%">No</th>
                    <th>Nama Perusahaan / Sektor</th>
                    <th>Pekerja</th>
                    <th>Mediator</th>
                    <th>Tanggal Diterima</th>
                    <th>Status Kasus</th>
                    <th>Penyelesaian Kasus</th>
                    <th>Lampiran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengaduans as $index => $p)
                <tr>
                    <td class="align-middle"><input type="checkbox" class="checkItem" value="{{ $p->id }}"></td>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-start">
                        <strong>{{ $p->nama_perusahaan }}</strong><br>
                        <small class="text-muted">{{ $p->sektor }}</small>
                    </td>
                    <td>
                        {{ $p->nama_pekerja ?? '-' }}
                        @if($p->jml_org)
                        <br><span class="badge bg-secondary">{{ $p->jml_org }} Orang</span>
                        @endif
                    </td>
                    <td>{{ $p->mediator ?? '-' }}</td>
                    <td>{{ $p->tanggal_diterima ? \Carbon\Carbon::parse($p->tanggal_diterima)->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($p->status_kasus === 'selesai')
                            <span class="badge bg-success">Selesai</span><br>
                            <small>{{ $p->tanggal_diselesaikan ? \Carbon\Carbon::parse($p->tanggal_diselesaikan)->format('d/m/Y') : '-' }}</small>
                        @else
                            <span class="badge bg-warning text-dark">Berjalan</span>
                        @endif
                    </td>
                    <td>{{ $p->metode_penyelesaian ?? '-' }}</td>
                    <td class="text-center">
                        @if($p->file_path)
                        <a href="{{ asset('storage/' . $p->file_path) }}" target="_blank" class="btn btn-sm btn-outline-success" title="Lihat Lampiran">
                            <i class="fa fa-file-alt"></i>
                        </a>
                        @else
                        -
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditPengaduan{{ $p->id }}" title="Edit Kasus">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form action="{{ route('phi.destroy.pengaduan', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data kasus ini?')">
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

                @if($pengaduans->isEmpty())
                <tr>
                    <td colspan="10" class="text-center">Belum ada data pengaduan kasus</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    </form>
</div>

@foreach($pengaduans as $p)
<!-- Modal Edit Pengaduan -->
<div class="modal fade" id="modalEditPengaduan{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Kasus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('phi.update.pengaduan', $p->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body text-start">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nama Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_perusahaan" class="form-control" value="{{ $p->nama_perusahaan }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Sektor</label>
                            <input type="text" name="sektor" class="form-control" value="{{ $p->sektor }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label>Nama Pekerja</label>
                            <input type="text" name="nama_pekerja" class="form-control" value="{{ $p->nama_pekerja }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Jumlah Org</label>
                            <input type="number" name="jml_org" class="form-control" value="{{ $p->jml_org }}" min="1">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nomor Agenda</label>
                            <input type="text" name="nomor_agenda" class="form-control" value="{{ $p->nomor_agenda }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Jenis Perselisihan</label>
                            <input type="text" name="jenis_perselisihan" class="form-control" value="{{ $p->jenis_perselisihan }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Mediator</label>
                            <input type="text" name="mediator" class="form-control" value="{{ $p->mediator }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tanggal Kasus Diterima</label>
                            <input type="date" name="tanggal_diterima" class="form-control" value="{{ $p->tanggal_diterima }}">
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Status Kasus <span class="text-danger">*</span></label>
                            <select name="status_kasus" class="form-select status-select" required>
                                <option value="berjalan" {{ $p->status_kasus == 'berjalan' ? 'selected' : '' }}>Masih Berjalan</option>
                                <option value="selesai" {{ $p->status_kasus == 'selesai' ? 'selected' : '' }}>Sudah Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 metode-container" style="display: {{ $p->status_kasus == 'selesai' ? 'block' : 'none' }};">
                            <label>Penyelesaian Kasus</label>
                            <select name="metode_penyelesaian" class="form-select">
                                <option value="">Pilih Metode...</option>
                                <option value="Bipartit" {{ $p->metode_penyelesaian == 'Bipartit' ? 'selected' : '' }}>Bipartit</option>
                                <option value="Perjanjian Bersama" {{ $p->metode_penyelesaian == 'Perjanjian Bersama' ? 'selected' : '' }}>Perjanjian Bersama</option>
                                <option value="Anjuran" {{ $p->metode_penyelesaian == 'Anjuran' ? 'selected' : '' }}>Anjuran</option>
                                <option value="Lainnya" {{ !in_array($p->metode_penyelesaian, ['Bipartit', 'Perjanjian Bersama', 'Anjuran', '']) ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 metode-container" style="display: {{ $p->status_kasus == 'selesai' ? 'block' : 'none' }};">
                            <label>Tanggal Diselesaikan</label>
                            <input type="date" name="tanggal_diselesaikan" class="form-control" value="{{ $p->tanggal_diselesaikan }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Update Lampiran Berkas (Opsional)</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file lampiran.</small>
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

<!-- Modal Tambah Pengaduan -->
<div class="modal fade" id="modalTambahPengaduan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kasus Terperinci</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('phi.store.pengaduan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-start">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nama Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_perusahaan" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Sektor</label>
                            <input type="text" name="sektor" class="form-control" placeholder="Contoh: Manufaktur">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label>Nama Pekerja</label>
                            <input type="text" name="nama_pekerja" class="form-control" placeholder="Isi nama perwakilan / semua">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Jumlah Org</label>
                            <input type="number" name="jml_org" class="form-control" placeholder="0" min="1">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nomor Agenda</label>
                            <input type="text" name="nomor_agenda" class="form-control" placeholder="No Surat/Agenda">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Jenis Perselisihan</label>
                            <input type="text" name="jenis_perselisihan" class="form-control" placeholder="Contoh: PHK, Hak, dll">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Mediator</label>
                            <input type="text" name="mediator" class="form-control" placeholder="Nama Mediator">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tanggal Kasus Diterima</label>
                            <input type="date" name="tanggal_diterima" class="form-control">
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Status Kasus <span class="text-danger">*</span></label>
                            <select name="status_kasus" class="form-select status-select" required>
                                <option value="berjalan" selected>Masih Berjalan</option>
                                <option value="selesai">Sudah Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 metode-container" style="display: none;">
                            <label>Penyelesaian Kasus</label>
                            <select name="metode_penyelesaian" class="form-select">
                                <option value="">Pilih Metode...</option>
                                <option value="Bipartit">Bipartit</option>
                                <option value="Perjanjian Bersama">Perjanjian Bersama</option>
                                <option value="Anjuran">Anjuran</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 metode-container" style="display: none;">
                            <label>Tanggal Diselesaikan</label>
                            <input type="date" name="tanggal_diselesaikan" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Lampiran Berkas Detail (Opsional)</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                        <small class="text-muted">Max: 5MB</small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Kasus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide Metode Penyelesaian & Tanggal Selesai based on Status Kasus
    const statusSelects = document.querySelectorAll('.status-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const containers = this.closest('.modal-body').querySelectorAll('.metode-container');
            containers.forEach(container => {
                if (this.value === 'selesai') {
                    container.style.display = 'block';
                } else {
                    container.style.display = 'none';
                    if(container.querySelector('input')) {
                        container.querySelector('input').value = '';
                    }
                }
            });
        });
    });
});
</script>

@endsection