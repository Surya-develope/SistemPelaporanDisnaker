@extends('layouts.app')

@section('content')

<h4 class="mb-4">Bidang Penta - Lowongan Kerja</h4>

<div class="row g-4 mb-4">

    <div class="col-md-4">
        <div class="card card-modern shadow-sm p-4">
            <h6 class="text-muted">Total Lowongan Aktif</h6>
            <h3 id="totalLowongan">3</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-modern shadow-sm p-4">
            <h6 class="text-muted">Perusahaan Terdaftar</h6>
            <h3>18</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-modern shadow-sm p-4">
            <h6 class="text-muted">Total Pelamar</h6>
            <h3>340</h3>
        </div>
    </div>

</div>

<div class="card card-modern shadow-sm p-4">

    <div class="d-flex justify-content-between mb-3">
        <h6>Daftar Lowongan</h6>
        <div>
            <button class="btn btn-success btn-sm" onclick="exportExcel()">Export Excel</button>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                + Tambah
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle" id="lowonganTable">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Perusahaan</th>
                    <th>Posisi</th>
                    <th>Kuota</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>PT Maju Jaya</td>
                    <td>Admin</td>
                    <td>5</td>
                    <td><span class="badge bg-success">Aktif</span></td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="hapusData(this)">Hapus</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>PT Sejahtera Abadi</td>
                    <td>Operator Produksi</td>
                    <td>10</td>
                    <td><span class="badge bg-success">Aktif</span></td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="hapusData(this)">Hapus</button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>CV Nusantara</td>
                    <td>Marketing</td>
                    <td>3</td>
                    <td><span class="badge bg-danger">Tutup</span></td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="hapusData(this)">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Lowongan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="perusahaan" class="form-control mb-2" placeholder="Nama Perusahaan">
                <input type="text" id="posisi" class="form-control mb-2" placeholder="Posisi">
                <input type="number" id="kuota" class="form-control mb-2" placeholder="Kuota">
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="tambahData()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
function tambahData() {
    let perusahaan = document.getElementById('perusahaan').value;
    let posisi = document.getElementById('posisi').value;
    let kuota = document.getElementById('kuota').value;

    let table = document.querySelector("#lowonganTable tbody");
    let rowCount = table.rows.length + 1;

    let row = table.insertRow();
    row.innerHTML = `
        <td>${rowCount}</td>
        <td>${perusahaan}</td>
        <td>${posisi}</td>
        <td>${kuota}</td>
        <td><span class="badge bg-success">Aktif</span></td>
        <td><button class="btn btn-danger btn-sm" onclick="hapusData(this)">Hapus</button></td>
    `;

    document.getElementById('totalLowongan').innerText = table.rows.length;

    bootstrap.Modal.getInstance(document.getElementById('modalTambah')).hide();
}

function hapusData(btn) {
    btn.closest('tr').remove();
    let table = document.querySelector("#lowonganTable tbody");
    document.getElementById('totalLowongan').innerText = table.rows.length;
}

function exportExcel() {
    var table = document.getElementById("lowonganTable");
    var wb = XLSX.utils.table_to_book(table, {sheet:"Lowongan"});
    XLSX.writeFile(wb, "data-lowongan.xlsx");
}
</script>

@endsection