<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIP DISNAKER | Disnaker Pekanbaru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        /* ── CSS VARIABLES ──────────────────────────────── */
        :root {
            --sb-width: 255px;
            --sb-bg: #0d1b35;
            --sb-active-bg: rgba(37,99,235,.14);
            --sb-active-border: #3b82f6;
            --sb-text: #94a3b8;
            --sb-text-hover: #e2e8f0;
            --topbar-h: 64px;
            --page-bg: #eef2f7;
            --card-bg: #ffffff;
            --border: #e2e8f0;
            --shadow-xs: 0 1px 3px rgba(0,0,0,.07);
            --shadow-sm: 0 4px 12px rgba(0,0,0,.09);
            --shadow-md: 0 8px 24px rgba(0,0,0,.11);
            --radius: 12px;
            --ease: all .22s ease;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            background: var(--page-bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #1e293b;
            margin: 0;
            font-size: 14px;
        }

        /* ── SIDEBAR ─────────────────────────────────────── */
        .sidebar {
            width: var(--sb-width);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: var(--sb-bg);
            display: flex;
            flex-direction: column;
            overflow: hidden;           /* no visible scrollbar */
            z-index: 1050;
            box-shadow: 3px 0 24px rgba(0,0,0,.18);
        }

        .sidebar-brand {
            padding: 22px 18px 18px;
            flex-shrink: 0;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        /* Scrollable nav — scrollbar hidden */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 8px 0 16px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .sidebar-nav::-webkit-scrollbar { display: none; }

        .sidebar-label {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: rgba(148,163,184,.38);
            padding: 18px 18px 5px;
        }

        .sidebar a {
            color: var(--sb-text);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: var(--ease);
        }
        .sidebar a i {
            width: 17px;
            font-size: 13px;
            text-align: center;
            opacity: .7;
            flex-shrink: 0;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,.05);
            color: var(--sb-text-hover);
            border-left-color: rgba(59,130,246,.35);
            padding-left: 22px;
        }
        .sidebar a:hover i { opacity: 1; }

        .sidebar a.active {
            background: var(--sb-active-bg);
            color: #fff;
            border-left-color: var(--sb-active-border);
            font-weight: 600;
        }
        .sidebar a.active i { opacity: 1; color: #60a5fa; }

        /* Collapse arrow */
        .sidebar a[data-bs-toggle="collapse"]::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 10.5px;
            opacity: .45;
            transition: transform .22s ease;
        }
        .sidebar a[data-bs-toggle="collapse"][aria-expanded="true"]::after {
            transform: rotate(180deg);
            opacity: .8;
        }

        /* Submenu */
        .submenu { background: rgba(0,0,0,.12); }
        .submenu a {
            padding: 7.5px 18px 7.5px 40px;
            font-size: 12.5px;
            font-weight: 400;
            color: rgba(148,163,184,.8);
        }
        .submenu a:hover {
            padding-left: 44px;
            color: #fff;
            border-left-color: rgba(59,130,246,.25);
        }
        .submenu a.active {
            color: #93c5fd;
            font-weight: 500;
            background: rgba(37,99,235,.1);
            border-left-color: #60a5fa;
        }

        .sidebar-footer {
            padding: 12px 18px;
            border-top: 1px solid rgba(255,255,255,.06);
            flex-shrink: 0;
            text-align: center;
            font-size: 10px;
            color: rgba(148,163,184,.3);
            letter-spacing: .3px;
        }

        /* ── CONTENT AREA ───────────────────────────────── */
        .content-wrapper {
            margin-left: var(--sb-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Bar */
        .topbar {
            height: var(--topbar-h);
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            padding: 0 26px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-xs);
        }
        .topbar-title {
            font-size: 14.5px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            letter-spacing: -.15px;
        }
        .topbar-date {
            font-size: 11.5px;
            color: #64748b;
            margin-top: 2px;
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 15px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            color: #64748b;
            font-size: 12.5px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--ease);
        }
        .btn-logout:hover {
            border-color: #fca5a5;
            background: #fff5f5;
            color: #dc2626;
        }

        .role-badge {
            display: inline-block;
            padding: 4px 13px;
            background: linear-gradient(135deg, #1e40af, #2563eb);
            color: #fff;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 1px;
            border-radius: 20px;
            text-transform: uppercase;
        }

        /* Main content */
        .main-content { padding: 26px; flex: 1; }

        /* ── CARDS ──────────────────────────────────────── */
        .card-modern {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--card-bg);
            box-shadow: var(--shadow-xs);
            transition: var(--ease);
        }
        .card-modern:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: transparent;
        }

        /* ── MOBILE ─────────────────────────────────────── */
        .sidebar-toggle {
            display: none;
            background: transparent;
            border: none;
            padding: 4px 8px;
            font-size: 19px;
            color: #475569;
            cursor: pointer;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1049;
        }
        .sidebar-overlay.show { display: block; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); transition: transform .28s ease; }
            .sidebar.show { transform: translateX(0); }
            .content-wrapper { margin-left: 0; }
            .sidebar-toggle { display: block; }
            .main-content { padding: 18px 14px; }
        }
    </style>
</head>
<body>

@php
    $role = \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->role : null;
@endphp

<!-- Mobile overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- ═══════════════════════ SIDEBAR ═══════════════════════ -->
<aside class="sidebar" id="sidebar">

    <!-- Brand -->
    <div class="sidebar-brand d-flex align-items-center gap-3">
        <img src="{{ asset('logo-pekanbaru.png') }}"
             width="42" height="42"
             class="bg-white p-1 rounded-2 shadow-sm flex-shrink-0"
             alt="Logo Pekanbaru">
        <div>
            <div class="fw-bold text-white" style="font-size:13.5px;line-height:1.2">SIP DISNAKER</div>
            <div style="font-size:10px;color:rgba(148,163,184,.55);letter-spacing:.2px">Disnaker Kota Pekanbaru</div>
        </div>
    </div>

    <!-- Scrollable nav -->
    <nav class="sidebar-nav">

        <div class="sidebar-label">Menu Utama</div>

        <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">
            <i class="fa fa-chart-line"></i> Dashboard
        </a>

        {{-- ── PENTA ── --}}
        @if(in_array($role, ['admin','penta']))
        <div class="sidebar-label">Bidang Penempatan</div>
        <a data-bs-toggle="collapse" href="#pentaMenu" role="button"
           aria-expanded="{{ request()->is('penta/*') ? 'true' : 'false' }}"
           class="{{ request()->is('penta/*') ? 'active' : '' }}">
            <i class="fa fa-users"></i> Bidang Penta
        </a>
        <div class="collapse {{ request()->is('penta/*') ? 'show' : '' }}" id="pentaMenu">
            <div class="submenu">
                <a href="/penta/lowongan" class="{{ request()->is('penta/lowongan') ? 'active' : '' }}">
                    <i class="fa fa-briefcase"></i> Lowongan
                </a>
                <a href="/penta/tenaga-kerja" class="{{ request()->is('penta/tenaga-kerja') ? 'active' : '' }}">
                    <i class="fa fa-user-check"></i> Tenaga Kerja
                </a>
                <a href="/penta/rekap" class="{{ request()->is('penta/rekap') ? 'active' : '' }}">
                    <i class="fa fa-chart-pie"></i> Rekap Pendaftaran
                </a>
                <a href="/penta/import" class="{{ request()->is('penta/import') ? 'active' : '' }}">
                    <i class="fa fa-file-excel"></i> Import Data Excel
                </a>
            </div>
        </div>
        @endif

        {{-- ── PHI ── --}}
        @if(in_array($role, ['admin','phi']))
        <div class="sidebar-label">Hubungan Industrial</div>
        <a data-bs-toggle="collapse" href="#phiMenu" role="button"
           aria-expanded="{{ request()->is('phi/*') ? 'true' : 'false' }}"
           class="{{ request()->is('phi/*') ? 'active' : '' }}">
            <i class="fa fa-file-contract"></i> Bidang PHI
        </a>
        <div class="collapse {{ request()->is('phi/*') ? 'show' : '' }}" id="phiMenu">
            <div class="submenu">
                <a href="/phi/pkwt" class="{{ request()->is('phi/pkwt') ? 'active' : '' }}">
                    <i class="fa fa-file-signature"></i> Rekap PKWT
                </a>
                <a href="/phi/pengaduan" class="{{ request()->is('phi/pengaduan') ? 'active' : '' }}">
                    <i class="fa fa-gavel"></i> Rekap Pengaduan Kasus
                </a>
                <a href="/phi/peraturan" class="{{ request()->is('phi/peraturan') ? 'active' : '' }}">
                    <i class="fa fa-book"></i> Rekap Peraturan Perusahaan
                </a>
            </div>
        </div>
        @endif

        {{-- ── LATTAS ── --}}
        @if(in_array($role, ['admin','lattas']))
        <div class="sidebar-label">Pelatihan & LPK</div>
        <a data-bs-toggle="collapse" href="#lattasMenu" role="button"
           aria-expanded="{{ request()->is('lattas/*') ? 'true' : 'false' }}"
           class="{{ request()->is('lattas/*') ? 'active' : '' }}">
            <i class="fa fa-graduation-cap"></i> Bidang Lattas
        </a>
        <div class="collapse {{ request()->is('lattas/*') ? 'show' : '' }}" id="lattasMenu">
            <div class="submenu">
                <a href="/lattas/pelatihan" class="{{ request()->is('lattas/pelatihan') ? 'active' : '' }}">
                    <i class="fa fa-chalkboard-teacher"></i> Data Pelatihan
                </a>
                <a href="/lattas/lpk-aktif" class="{{ request()->is('lattas/lpk-aktif') ? 'active' : '' }}">
                    <i class="fa fa-building"></i> Rekap LPK Aktif
                </a>
                <a href="/lattas/lpk-nonaktif" class="{{ request()->is('lattas/lpk-nonaktif') ? 'active' : '' }}">
                    <i class="fa fa-building-circle-xmark"></i> Rekap LPK Non Aktif
                </a>
                <a href="/lattas/import" class="{{ request()->is('lattas/import') ? 'active' : '' }}">
                    <i class="fa fa-file-excel"></i> Upload Data Excel
                </a>
            </div>
        </div>
        @endif

        {{-- ── KELOLA PENGGUNA ── --}}
        @if($role === 'admin')
        <div class="sidebar-label" style="margin-top: 15px;">Pengaturan</div>
        <a href="{{ route('users.index') }}" class="{{ request()->is('users') ? 'active' : '' }}">
            <i class="fa fa-users-cog"></i> Kelola Pengguna
        </a>
        @endif

    </nav>

    <div class="sidebar-footer">© {{ date('Y') }} Disnaker Kota Pekanbaru</div>
</aside>

<!-- ═══════════════════════ CONTENT ═══════════════════════ -->
<div class="content-wrapper">

    <!-- Top Bar -->
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                <i class="fa fa-bars"></i>
            </button>
            <div>
                <p class="topbar-title mb-0">Sistem Pelaporan Disnaker Kota Pekanbaru</p>
                <p class="topbar-date mb-0" id="tanggal"></p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($role)
                <span class="role-badge">{{ strtoupper($role) }}</span>
            @endif
            <a href="/logout" class="btn-logout">
                <i class="fa fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </header>

    <!-- Page Content -->
    <main class="main-content">
        @yield('content')
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Live date
    (function () {
        const el = document.getElementById('tanggal');
        if (el) {
            el.textContent = new Date().toLocaleDateString('id-ID', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
        }
    })();

    // Mobile sidebar toggle
    function toggleSidebar() {
        const sb = document.getElementById('sidebar');
        const ov = document.getElementById('sidebarOverlay');
        sb.classList.toggle('show');
        ov.classList.toggle('show');
    }
</script>

</body>
</html>