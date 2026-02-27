@extends('layouts.app')

@section('content')

<h4 class="mb-4 fw-bold">Dashboard Statistik</h4>

{{-- ===================== BIDANG PENTA ===================== --}}
<div class="d-flex align-items-center mb-3">
    <span class="badge rounded-pill me-2" style="background:#0f172a; font-size:13px; padding:7px 14px;">
        <i class="fa fa-users me-1"></i> Bidang Penta
    </span>
    <hr class="flex-grow-1 m-0">
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="card card-modern shadow-sm p-4 border-start border-4 border-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Pencari Kerja</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($totalPencariKerja) }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:rgba(59,130,246,0.1);">
                    <i class="fa fa-user-check fa-lg text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card card-modern shadow-sm p-4 border-start border-4 border-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Lowongan Kerja</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($totalLowongan) }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:rgba(6,182,212,0.1);">
                    <i class="fa fa-briefcase fa-lg text-info"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card card-modern shadow-sm p-4 border-start border-4" style="border-color:#6366f1!important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Penempatan</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($totalPenempatan) }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:rgba(99,102,241,0.1);">
                    <i class="fa fa-chart-pie fa-lg" style="color:#6366f1;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== BIDANG PHI ===================== --}}
<div class="d-flex align-items-center mb-3">
    <span class="badge rounded-pill me-2" style="background:#b45309; font-size:13px; padding:7px 14px;">
        <i class="fa fa-file-contract me-1"></i> Bidang PHI
    </span>
    <hr class="flex-grow-1 m-0">
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="card card-modern shadow-sm p-4 border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Laporan PKWT</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($totalLaporanPkwt) }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:rgba(234,179,8,0.1);">
                    <i class="fa fa-file-signature fa-lg text-warning"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card card-modern shadow-sm p-4 border-start border-4" style="border-color:#f97316!important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Pekerja PKWT</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($totalPekerjaKwt) }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:rgba(249,115,22,0.1);">
                    <i class="fa fa-users fa-lg" style="color:#f97316;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card card-modern shadow-sm p-4 border-start border-4 border-danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Kasus PHI Masuk</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($totalKasusPhi) }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:rgba(239,68,68,0.1);">
                    <i class="fa fa-gavel fa-lg text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== BIDANG LATTAS ===================== --}}
<div class="d-flex align-items-center mb-3">
    <span class="badge rounded-pill me-2" style="background:#065f46; font-size:13px; padding:7px 14px;">
        <i class="fa fa-graduation-cap me-1"></i> Bidang Lattas
    </span>
    <hr class="flex-grow-1 m-0">
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="card card-modern shadow-sm p-4 border-start border-4 border-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Program Pelatihan</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($totalPelatihan) }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:rgba(34,197,94,0.1);">
                    <i class="fa fa-chalkboard-teacher fa-lg text-success"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card card-modern shadow-sm p-4 border-start border-4" style="border-color:#0d9488!important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total LPK Aktif</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($totalLpkAktif) }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:rgba(13,148,136,0.1);">
                    <i class="fa fa-building fa-lg" style="color:#0d9488;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card card-modern shadow-sm p-4 border-start border-4" style="border-color:#7c3aed!important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Peserta Pelatihan</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($totalPeserta) }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:rgba(124,58,237,0.1);">
                    <i class="fa fa-user-graduate fa-lg" style="color:#7c3aed;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== GRAFIK STATISTIK BULANAN ===================== --}}
<div class="card card-modern shadow-sm p-4">
    <h6 class="mb-3">Statistik Bulanan</h6>
    <canvas id="myChart" height="100"></canvas>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('myChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [
                {
                    label: 'Tenaga Kerja',
                    data: [120, 150, 180, 200, 170, 220],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'PKWT',
                    data: [40, 60, 75, 90, 70, 100],
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34,197,94,0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
});
</script>

@endsection