<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIP NAKER | Disnaker Pekanbaru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body { background-color: #f1f5f9; font-family: 'Segoe UI', sans-serif; }

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

        .sidebar a:hover,
        .sidebar a.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            padding-left: 30px;
        }

        .submenu a { 
            font-size: 13px; 
            padding-left: 55px; 
        }

        .content { margin-left: 270px; padding: 30px; }

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
    </style>
</head>
<body>

@php
$role = session('role');
@endphp

<div class="sidebar">

    <div class="text-center mb-4">
        <img src="{{ asset('logo-pekanbaru.png') }}" width="80" class="mb-3 bg-white p-2 rounded">
        <h6 class="fw-bold text-white mb-0">SIP NAKER</h6>
        <small class="text-light">Disnaker Kota Pekanbaru</small>
    </div>

    <hr class="text-secondary">

    {{-- DASHBOARD --}}
    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">
        <i class="fa fa-chart-line me-2"></i> Dashboard
    </a>

    {{-- ================= PENTA ================= --}}
    @if(in_array($role, ['admin','penta']))
    <a data-bs-toggle="collapse" href="#pentaMenu" role="button"
       aria-expanded="{{ request()->is('penta/*') ? 'true' : 'false' }}"
       class="{{ request()->is('penta/*') ? 'active' : '' }}">
        <i class="fa fa-users me-2"></i> Bidang Penta
    </a>

    <div class="collapse {{ request()->is('penta/*') ? 'show' : '' }}" id="pentaMenu">
        <div class="submenu">
            <a href="/penta/lowongan"
               class="{{ request()->is('penta/lowongan') ? 'active' : '' }}">
                <i class="fa fa-briefcase me-2"></i> Lowongan
            </a>

            <a href="/penta/tenaga-kerja"
               class="{{ request()->is('penta/tenaga-kerja') ? 'active' : '' }}">
                <i class="fa fa-user-check me-2"></i> Tenaga Kerja
            </a>

            <a href="/penta/rekap"
               class="{{ request()->is('penta/rekap') ? 'active' : '' }}">
                <i class="fa fa-chart-pie me-2"></i> Rekap Pendaftaran
            </a>
        </div>
    </div>
    @endif

    {{-- ================= PHI ================= --}}
    @if(in_array($role, ['admin','phi']))
    <a data-bs-toggle="collapse" href="#phiMenu" role="button"
       aria-expanded="{{ request()->is('phi/*') ? 'true' : 'false' }}"
       class="{{ request()->is('phi/*') ? 'active' : '' }}">
        <i class="fa fa-file-contract me-2"></i> Bidang PHI
    </a>

    <div class="collapse {{ request()->is('phi/*') ? 'show' : '' }}" id="phiMenu">
        <div class="submenu">

            <a href="/phi/pkwt"
               class="{{ request()->is('phi/pkwt') ? 'active' : '' }}">
                <i class="fa fa-file-signature me-2"></i> Rekap PKWT
            </a>

            <a href="/phi/pengaduan"
               class="{{ request()->is('phi/pengaduan') ? 'active' : '' }}">
                <i class="fa fa-gavel me-2"></i> Rekap Pengaduan Kasus
            </a>

            <a href="/phi/peraturan"
               class="{{ request()->is('phi/peraturan') ? 'active' : '' }}">
                <i class="fa fa-book me-2"></i> Rekap Peraturan Perusahaan
            </a>

        </div>
    </div>
    @endif

    {{-- ================= LATTAS ================= --}}
    {{-- ================= LATTAS ================= --}}
@if(in_array($role, ['admin','lattas']))
<a data-bs-toggle="collapse" href="#lattasMenu" role="button"
   aria-expanded="{{ request()->is('lattas/*') ? 'true' : 'false' }}"
   class="{{ request()->is('lattas/*') ? 'active' : '' }}">
    <i class="fa fa-graduation-cap me-2"></i> Bidang Lattas
</a>

<div class="collapse {{ request()->is('lattas/*') ? 'show' : '' }}" id="lattasMenu">
    <div class="submenu">

        <a href="/lattas/pelatihan"
           class="{{ request()->is('lattas/pelatihan') ? 'active' : '' }}">
            <i class="fa fa-chalkboard-teacher me-2"></i> Data Pelatihan
        </a>

        <a href="/lattas/lpk-aktif"
           class="{{ request()->is('lattas/lpk-aktif') ? 'active' : '' }}">
            <i class="fa fa-building me-2"></i> Rekap LPK Aktif
        </a>

        <a href="/lattas/lpk-nonaktif"
           class="{{ request()->is('lattas/lpk-nonaktif') ? 'active' : '' }}">
            <i class="fa fa-building-circle-xmark me-2"></i> Rekap LPK Non Aktif
        </a>

    </div>
</div>
@endif

</div>

<div class="content">

    <div class="navbar-custom shadow-sm d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Sistem Pelaporan Disnaker Kota Pekanbaru</h5>
            <small class="text-muted" id="tanggal"></small>
        </div>
        <div>
            @if($role)
                <span class="badge bg-primary px-3 py-2 me-2">
                    {{ strtoupper($role) }}
                </span>
            @endif
            <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>

    @yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function updateClock() {
    const now = new Date();
    const tanggal = now.toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    document.getElementById('tanggal').innerText = tanggal;
}
updateClock();
</script>

</body>
</html>