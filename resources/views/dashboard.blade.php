@extends('layouts.app')

@section('content')

<style>
.card-clickable {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
}
.card-clickable:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="m-0 fw-bold">Dashboard Statistik</h4>
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm shadow-sm d-print-none">
        <i class="fa fa-print me-1"></i> Cetak Laporan
    </button>
</div>

{{-- ===================== BIDANG PENTA ===================== --}}
<div class="d-flex align-items-center mb-3">
    <span class="badge rounded-pill me-2" style="background:#0f172a; font-size:13px; padding:7px 14px;">
        <i class="fa fa-users me-1"></i> Bidang Penta
    </span>
    <hr class="flex-grow-1 m-0">
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="card card-modern card-clickable shadow-sm p-4 border-start border-4 border-primary" onclick="updateChart('pencariKerja', 'Total Pencari Kerja', '#3b82f6')">
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
        <div class="card card-modern card-clickable shadow-sm p-4 border-start border-4 border-info" onclick="updateChart('lowongan', 'Total Lowongan Kerja', '#06b6d4')">
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
        <div class="card card-modern card-clickable shadow-sm p-4 border-start border-4" style="border-color:#6366f1!important;" onclick="updateChart('penempatan', 'Total Penempatan', '#6366f1')">
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
        <div class="card card-modern card-clickable shadow-sm p-4 border-start border-4 border-warning" onclick="updateChart('laporanPkwt', 'Total Laporan PKWT', '#eab308')">
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
        <div class="card card-modern card-clickable shadow-sm p-4 border-start border-4" style="border-color:#f97316!important;" onclick="updateChart('pekerjaKwt', 'Total Pekerja PKWT', '#f97316')">
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
        <div class="card card-modern card-clickable shadow-sm p-4 border-start border-4 border-danger" onclick="updateChart('kasusPhi', 'Total Kasus PHI Masuk', '#ef4444')">
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
        <div class="card card-modern card-clickable shadow-sm p-4 border-start border-4 border-success" onclick="updateChart('lpkAktif', 'Total LPK Aktif', '#22c55e')">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total LPK Aktif</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($totalLpkAktif) }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:rgba(34,197,94,0.1);">
                    <i class="fa fa-building fa-lg text-success"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card card-modern card-clickable shadow-sm p-4 border-start border-4" style="border-color:#0d9488!important;" onclick="updateChart('lpkNonaktif', 'Total LPK Tidak Aktif', '#0d9488')">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total LPK Tidak Aktif</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($totalLpkNonaktif) }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:rgba(13,148,136,0.1);">
                    <i class="fa fa-building-circle-xmark fa-lg" style="color:#0d9488;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card card-modern card-clickable shadow-sm p-4 border-start border-4" style="border-color:#7c3aed!important;" onclick="updateChart('pelatihan', 'Total Program Pelatihan', '#7c3aed')">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Program Pelatihan</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($totalPelatihan) }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:rgba(124,58,237,0.1);">
                    <i class="fa fa-chalkboard-teacher fa-lg" style="color:#7c3aed;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== GRAFIK STATISTIK BULANAN ===================== --}}
<div id="chartContainerArea" class="card card-modern shadow-sm p-4 mb-5" style="page-break-before: always;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-3">
            <h6 id="chartTitle" class="mb-0 fw-bold">Statistik Bulanan (Ikhtisar)</h6>
            <button id="btnViewDetail" class="btn btn-sm btn-info text-white shadow-sm d-none d-print-none" data-type="" onclick="viewDetail(this.getAttribute('data-type'))">
                <i class="fa fa-list me-1"></i> Rincian Data
            </button>
        </div>
        <button onclick="resetChart()" class="btn btn-sm btn-outline-primary shadow-sm d-print-none">
            <i class="fa fa-sync-alt me-1"></i> Tampilkan Semua
        </button>
    </div>
    <div style="position: relative; height: 350px; width: 100%;">
        <canvas id="myChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
// Data Mentah dari Backend
const rawData = @json($chartData);

let myChartInstance = null;
let currentCtx = null;

// Helper: Konversi Hex ke RGBA untuk Efek Gradient
function hexToRgbA(hex, alpha){
    let c;
    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
        c = hex.substring(1).split('');
        if(c.length === 3){
            c = [c[0], c[0], c[1], c[1], c[2], c[2]];
        }
        c = '0x' + c.join('');
        return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+','+alpha+')';
    }
    return `rgba(59,130,246,${alpha})`;
}

// Fungsi Utama: Menggambar Ulang Grafik
function renderChart(datasets) {
    if (myChartInstance) myChartInstance.destroy();
    
    myChartInstance = new Chart(currentCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, padding: 20, font: { size: 13, weight: '500' } } },
                tooltip: { backgroundColor: 'rgba(15, 23, 42, 0.9)', titleFont: { size: 14, weight: 'bold' }, bodyFont: { size: 13 }, padding: 12, cornerRadius: 8, displayColors: true, boxPadding: 6, usePointStyle: true, borderColor: 'rgba(255,255,255,0.1)', borderWidth: 1 }
            },
            scales: {
                x: { grid: { display: false, drawBorder: false }, ticks: { font: { weight: '500' } } },
                y: { grid: { color: '#f1f5f9', drawBorder: false, borderDash: [5, 5] }, beginAtZero: true }
            }
        }
    });
}

// Fungsi Aksi: Saat Kartu Diklik
window.updateChart = function(key, label, colorHex) {
    // Ubah Judul Grafik
    document.getElementById('chartTitle').innerText = 'Tren Bulanan: ' + label;
    
    // Tampilkan tombol lihat detail
    const btnDetail = document.getElementById('btnViewDetail');
    btnDetail.classList.remove('d-none');
    btnDetail.setAttribute('data-type', key);
    
    // Buat Gradient Khusus untuk Warna yang Dipilih
    const gradient = currentCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, hexToRgbA(colorHex, 0.5));
    gradient.addColorStop(1, hexToRgbA(colorHex, 0.0));

    const dataset = {
        label: label,
        data: rawData[key],
        borderColor: colorHex,
        backgroundColor: gradient,
        borderWidth: 3,
        pointRadius: 0,
        pointHoverRadius: 6,
        pointHoverBackgroundColor: '#ffffff',
        pointHoverBorderWidth: 3,
        pointHoverBorderColor: colorHex,
        tension: 0.4,
        fill: true
    };
    
    renderChart([dataset]);
    
    // Scroll mulus ke area grafik
    document.getElementById('chartContainerArea').scrollIntoView({ behavior: 'smooth', block: 'center' });
};

// Fungsi Aksi: Reset ke Ikhtisar (3 Garis)
window.resetChart = function() {
    document.getElementById('chartTitle').innerText = 'Statistik Bulanan (Ikhtisar)';
    document.getElementById('btnViewDetail').classList.add('d-none');
    
    const gradPenta = currentCtx.createLinearGradient(0, 0, 0, 400);
    gradPenta.addColorStop(0, 'rgba(59, 130, 246, 0.5)'); // Blue
    gradPenta.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

    const gradPhi = currentCtx.createLinearGradient(0, 0, 0, 400);
    gradPhi.addColorStop(0, 'rgba(249, 115, 22, 0.5)'); // Orange
    gradPhi.addColorStop(1, 'rgba(249, 115, 22, 0.0)');

    const gradLattas = currentCtx.createLinearGradient(0, 0, 0, 400);
    gradLattas.addColorStop(0, 'rgba(124, 58, 237, 0.5)'); // Purple
    gradLattas.addColorStop(1, 'rgba(124, 58, 237, 0.0)');

    const datasets = [
        {
            label: 'Pencari Kerja (PENTA)',
            data: rawData['pencariKerja'],
            borderColor: '#3b82f6',
            backgroundColor: gradPenta,
            borderWidth: 3, pointRadius: 0, pointHoverRadius: 6, pointHoverBackgroundColor: '#ffffff', pointHoverBorderWidth: 3, pointHoverBorderColor: '#3b82f6', tension: 0.4, fill: true
        },
        {
            label: 'Laporan PKWT (PHI)',
            data: rawData['laporanPkwt'],
            borderColor: '#f97316',
            backgroundColor: gradPhi,
            borderWidth: 3, pointRadius: 0, pointHoverRadius: 6, pointHoverBackgroundColor: '#ffffff', pointHoverBorderWidth: 3, pointHoverBorderColor: '#f97316', tension: 0.4, fill: true
        },
        {
            label: 'Program Pelatihan (LATTAS)',
            data: rawData['pelatihan'],
            borderColor: '#7c3aed',
            backgroundColor: gradLattas,
            borderWidth: 3, pointRadius: 0, pointHoverRadius: 6, pointHoverBackgroundColor: '#ffffff', pointHoverBorderWidth: 3, pointHoverBorderColor: '#7c3aed', tension: 0.4, fill: true
        }
    ];
    
    renderChart(datasets);
};

document.addEventListener("DOMContentLoaded", function () {
    currentCtx = document.getElementById('myChart').getContext('2d');
    
    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
    Chart.defaults.color = '#64748b';

    // Gambar grafik ikhtisar saat halaman pertama kali dimuat
    resetChart();
});

// Fungsi Fetch Detail JSON dan Tampilkan Modal
window.viewDetail = function(type) {
    const tahun = {{ $tahun }};
    const modalTitle = document.getElementById('detailModalTitle');
    const tbody = document.getElementById('detailModalBody');
    
    modalTitle.innerText = "Memuat data...";
    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x text-primary"></i></td></tr>';
    
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    modal.show();

    fetch(`/dashboard/detail/${type}?tahun=${tahun}`)
        .then(response => response.json())
        .then(res => {
            modalTitle.innerText = res.title + " (" + tahun + ")";
            tbody.innerHTML = '';
            
            if (res.headers) {
                const thead = document.querySelector('#detailModal table thead tr');
                thead.innerHTML = `
                    <th width="5%" class="text-center">${res.headers[0]}</th>
                    <th width="30%">${res.headers[1]}</th>
                    <th width="25%">${res.headers[2]}</th>
                    <th width="20%" class="text-center">${res.headers[3]}</th>
                    <th width="20%" class="text-center">${res.headers[4]}</th>
                `;
            }
            
            if(res.data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">Tidak ada data untuk tahun ${tahun}</td></tr>`;
                return;
            }

            res.data.forEach((item, index) => {
                let statusBadge = '';
                if(item.status !== null && item.status !== undefined && item.status !== '') {
                    if (res.title === 'Rekapitulasi Kasus PHI' || res.title === 'Daftar Program Pelatihan') {
                        statusBadge = `<span class="fw-bold">${item.status}</span>`;
                    } else {
                        let badgeClass = 'bg-secondary';
                        if(['DITERIMA', 'aktif', 'TERTUTUP'].includes(item.status)) badgeClass = 'bg-success';
                        if(['DITOLAK', 'tidak aktif', 'MENUNGGU VERIFIKASI'].includes(item.status)) badgeClass = 'bg-danger';
                        if(['TERBUKA', 'BELUM DIVERIFIKASI', 'PROSES'].includes(item.status)) badgeClass = 'bg-warning text-dark';
                        statusBadge = `<span class="badge ${badgeClass}">${item.status}</span>`;
                    }
                }

                tbody.innerHTML += `
                    <tr>
                        <td class="text-center">${index + 1}</td>
                        <td class="fw-bold">${item.nama || '-'}</td>
                        <td>${item.detail_1 || '-'}</td>
                        <td class="text-center">${item.detail_2 || '-'}</td>
                        <td class="text-center">${statusBadge}</td>
                    </tr>
                `;
            });
        })
        .catch(error => {
            console.error('Error fetching detail:', error);
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Gagal memuat data. Silakan coba lagi.</td></tr>';
        });
}
</script>

<!-- Modal Menampilkan Rincian Detail dari Dashboard -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="detailModalTitle"><i class="fa fa-list me-2"></i> Rincian Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive m-0">
                    <table class="table table-hover table-striped align-middle m-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="30%">Nama / Entitas Utama</th>
                                <th width="25%">Informasi 1</th>
                                <th width="20%" class="text-center">Informasi 2</th>
                                <th width="20%" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="detailModalBody">
                            <!-- Data rows injected by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection