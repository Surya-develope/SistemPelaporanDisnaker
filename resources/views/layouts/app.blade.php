<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIP NAKER | Disnaker Pekanbaru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            width: 270px;
            height: 100vh;
            position: fixed;
            background: linear-gradient(180deg, #0f172a, #1e293b);
            color: white;
            padding: 20px 0;
            overflow-y: auto;
        }

        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
            display: block;
            padding: 12px 25px;
            transition: 0.3s;
            font-size: 14px;
        }

        .sidebar a:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            padding-left: 30px;
        }

        .sidebar .submenu a {
            font-size: 13px;
            padding-left: 45px;
        }

        .logo-img {
            background: white;
            padding: 10px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        }

        .content {
            margin-left: 270px;
            padding: 30px;
        }

        .navbar-custom {
            background: white;
            border-radius: 15px;
            padding: 15px 25px;
        }

        .card-modern {
            border: none;
            border-radius: 15px;
            transition: 0.3s;
        }

        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0,0,0,0.1);
        }

        hr { opacity: 0.2; }
    </style>
</head>
<body>

@php
$role = 'admin';
@endphp

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="text-center mb-4">
        <img src="{{ asset('logo-pekanbaru.png') }}" width="80" class="logo-img mb-3">
        <h6 class="fw-bold text-white mb-0">SIP NAKER</h6>
        <small class="text-light">Disnaker Kota Pekanbaru</small>
    </div>

    <hr class="text-secondary">

    <a href="/"><i class="fa fa-chart-line me-2"></i> Dashboard</a>

    <!-- PENTA -->
    <a data-bs-toggle="collapse" href="#pentaMenu">
        <i class="fa fa-users me-2"></i> Bidang Penta
    </a>
    <div class="collapse submenu" id="pentaMenu">
        <a href="/penta/tenaga-kerja">Jumlah Tenaga Kerja</a>
        <a href="#">Lowongan Kerja</a>
        <a href="#">Rekap Pendaftaran</a>
    </div>

    <!-- PHI -->
    <a data-bs-toggle="collapse" href="#phiMenu">
        <i class="fa fa-file-contract me-2"></i> Bidang PHI
    </a>
    <div class="collapse submenu" id="phiMenu">
        <a href="/phi/pkwt">Rekap PKWT</a>
        <a href="#">Rekap PP</a>
        <a href="#">Rekap Pengaduan</a>
    </div>

    <!-- LATTAS -->
    <a data-bs-toggle="collapse" href="#lattasMenu">
        <i class="fa fa-graduation-cap me-2"></i> Bidang Lattas
    </a>
    <div class="collapse submenu" id="lattasMenu">
        <a href="/lattas/pelatihan">Rekap Pelatihan</a>
        <a href="#">LPK Aktif</a>
        <a href="#">LPK Non Aktif</a>
    </div>

</div>

<!-- CONTENT -->
<div class="content">

    <div class="navbar-custom shadow-sm d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Sistem Pelaporan Disnaker Kota Pekanbaru</h5>
            <small class="text-muted" id="tanggal"></small>
        </div>
        <div>
            <span class="badge bg-primary px-3 py-2 me-2">{{ strtoupper($role) }}</span>
            <span class="badge bg-dark px-3 py-2" id="jam"></span>
        </div>
    </div>

    @yield('content')

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script Jam & Tanggal -->
<script>
function updateClock() {
    const now = new Date();
    const jam = now.toLocaleTimeString('id-ID');
    const tanggal = now.toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    document.getElementById('jam').innerText = jam;
    document.getElementById('tanggal').innerText = tanggal;
}
setInterval(updateClock, 1000);
updateClock();
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>