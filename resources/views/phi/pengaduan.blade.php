@extends('layouts.app')

@section('content')

<h4 class="mb-4">Rekap Pengaduan Kasus (PHI)</h4>

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
            <a href="{{ route('phi.export.pengaduan', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="btn btn-outline-success shadow-sm">
                <i class="fa fa-file-excel me-1"></i> Export Excel
            </a>
        </form>

        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPengaduan">
                <i class="fa fa-plus me-1"></i> Tambah Manual
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light align-middle">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Periode</th>
                    <th rowspan="2">Sisa Bulan Lalu</th>
                    <th rowspan="2">Kasus Masuk</th>
                    <th colspan="4">Penyelesaian</th>
                    <th rowspan="2">Sisa Kasus Akhir</th>
                    <th rowspan="2">File Lampiran</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Bipartit</th>
                    <th>PB</th>
                    <th>Anjuran</th>
                    <th>Lainnya</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengaduans as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ DateTime::createFromFormat('!m', $p->bulan)->format('M') }} {{ $p->tahun }}</td>
                    <td>{{ $p->sisa_bulan_lalu }}</td>
                    <td>{{ $p->kasus_masuk }}</td>
                    <td>{{ $p->selesai_bipartit }}</td>
                    <td>{{ $p->selesai_pb }}</td>
                    <td>{{ $p->selesai_anjuran }}</td>
                    <td>{{ $p->selesai_lainnya }}</td>
                    <td>{{ $p->sisa_kasus_akhir }}</td>
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
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditPengaduan{{ $p->id }}" title="Edit Data">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form action="{{ route('phi.destroy.pengaduan', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                    <td colspan="11" class="text-center">Belum ada data rekapan</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@foreach($pengaduans as $p)
<!-- Modal Edit Pengaduan -->
<div class="modal fade" id="modalEditPengaduan{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Rekap Pengaduan Kasus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('phi.update.pengaduan', $p->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body text-start">
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
                            <label>Sisa Kasus Bulan Lalu</label>
                            <input type="number" name="sisa_bulan_lalu" class="form-control" value="{{ $p->sisa_bulan_lalu }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Kasus Masuk Bulan Ini</label>
                            <input type="number" name="kasus_masuk" class="form-control" value="{{ $p->kasus_masuk }}" required>
                        </div>
                    </div>

                    <h6 class="mt-3">Penyelesaian Kasus:</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Bipartit</label>
                            <input type="number" name="selesai_bipartit" class="form-control" value="{{ $p->selesai_bipartit }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>PB</label>
                            <input type="number" name="selesai_pb" class="form-control" value="{{ $p->selesai_pb }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Anjuran</label>
                            <input type="number" name="selesai_anjuran" class="form-control" value="{{ $p->selesai_anjuran }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Lainnya</label>
                            <input type="number" name="selesai_lainnya" class="form-control" value="{{ $p->selesai_lainnya }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Sisa Kasus di Akhir Bulan</label>
                            <input type="number" name="sisa_kasus_akhir" class="form-control bg-light calc-result" value="{{ $p->sisa_kasus_akhir }}" readonly>
                            <small class="text-info">Terisi otomatis sesuai kalkulasi</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Update File Lampiran (Opsional)</label>
                            <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file.</small>
                        </div>
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
                <h5 class="modal-title">Tambah Rekap Pengaduan Kasus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('phi.store.pengaduan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-start">
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
                            <label>Sisa Kasus Bulan Lalu</label>
                            <input type="number" name="sisa_bulan_lalu" class="form-control calc-input" value="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Kasus Masuk Bulan Ini</label>
                            <input type="number" name="kasus_masuk" class="form-control calc-input" value="0" required>
                        </div>
                    </div>

                    <h6 class="mt-3">Penyelesaian Kasus:</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Bipartit</label>
                            <input type="number" name="selesai_bipartit" class="form-control calc-input" value="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>PB</label>
                            <input type="number" name="selesai_pb" class="form-control calc-input" value="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Anjuran</label>
                            <input type="number" name="selesai_anjuran" class="form-control calc-input" value="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Lainnya</label>
                            <input type="number" name="selesai_lainnya" class="form-control calc-input" value="0" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Sisa Kasus di Akhir Bulan</label>
                            <input type="number" name="sisa_kasus_akhir" class="form-control bg-light calc-result" readonly>
                            <small class="text-info">Terisi otomatis sesuai kalkulasi</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>File Lampiran</label>
                            <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv" required>
                            <small class="text-muted">Max: 5MB</small>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    function calculateEndBalance(modal) {
        const sisaLalu = parseInt(modal.querySelector('[name="sisa_bulan_lalu"]').value) || 0;
        const masuk = parseInt(modal.querySelector('[name="kasus_masuk"]').value) || 0;
        const bipartit = parseInt(modal.querySelector('[name="selesai_bipartit"]').value) || 0;
        const pb = parseInt(modal.querySelector('[name="selesai_pb"]').value) || 0;
        const anjuran = parseInt(modal.querySelector('[name="selesai_anjuran"]').value) || 0;
        const lainnya = parseInt(modal.querySelector('[name="selesai_lainnya"]').value) || 0;

        const totalSelesai = bipartit + pb + anjuran + lainnya;
        const hasil = (sisaLalu + masuk) - totalSelesai;
        
        modal.querySelector('.calc-result').value = hasil;
    }

    // Attach listeners to all modals
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        const inputs = modal.querySelectorAll('.form-control[type="number"]');
        inputs.forEach(input => {
            if (!input.readOnly) {
                input.addEventListener('input', () => calculateEndBalance(modal));
            }
        });
    });
});
</script>

@endsection