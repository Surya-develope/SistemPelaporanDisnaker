@extends('layouts.app')

@section('content')

<h4 class="mb-4">Bidang Penta - Rekap Pendaftaran</h4>

<div class="card card-modern shadow-sm p-4 mb-4">
    <h6 class="mb-3">Statistik Pendaftaran</h6>
    <canvas id="pendaftaranChart" height="100"></canvas>
</div>

<div class="card card-modern shadow-sm p-4">
    <h6 class="mb-3">Data Pendaftar</h6>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Lowongan</th>
                    <th>Tanggal Daftar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Budi Santoso</td>
                    <td>Admin - PT Maju Jaya</td>
                    <td>12 Jan 2026</td>
                    <td><span class="badge bg-warning">Proses</span></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Siti Rahma</td>
                    <td>Operator - PT Sejahtera</td>
                    <td>15 Jan 2026</td>
                    <td><span class="badge bg-success">Diterima</span></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Andi Pratama</td>
                    <td>Marketing - CV Nusantara</td>
                    <td>18 Jan 2026</td>
                    <td><span class="badge bg-danger">Ditolak</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('pendaftaranChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'],
            datasets: [{
                label: 'Jumlah Pendaftar',
                data: [50, 80, 65, 100, 90],
                backgroundColor: '#3b82f6'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>

@endsection