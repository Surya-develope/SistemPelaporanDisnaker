<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | SIP DISNAKER Disnaker Pekanbaru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: #eef2f7;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            /* Soft diagonal pattern overlay */
            background-image:
                linear-gradient(135deg, rgba(13,27,53,.92) 0%, rgba(30,64,175,.82) 100%),
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /* ── CARD WRAPPER ─────────────────────────── */
        .login-wrapper {
            width: 100%;
            max-width: 960px;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,.28), 0 8px 24px rgba(0,0,0,.12);
            display: flex;
            min-height: 540px;
        }

        /* ── LEFT PANEL ───────────────────────────── */
        .login-left {
            background: linear-gradient(160deg, #0d1b35 0%, #0f2554 55%, #1e3a8a 100%);
            color: #fff;
            width: 44%;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 52px 36px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles */
        .login-left::before,
        .login-left::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.04);
            pointer-events: none;
        }
        .login-left::before { width: 320px; height: 320px; top: -110px; right: -110px; }
        .login-left::after  { width: 240px; height: 240px; bottom: -90px; left: -80px; }

        .logo-img {
            width: 96px;
            height: 96px;
            object-fit: contain;
            background: #fff;
            padding: 14px;
            border-radius: 18px;
            box-shadow: 0 12px 32px rgba(0,0,0,.32);
            margin-bottom: 26px;
            position: relative;
            z-index: 1;
        }

        .login-left .app-name {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1.5px;
            line-height: 1.1;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }
        .login-left .app-sub {
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(147,197,253,.7);
            margin-bottom: 22px;
            position: relative;
            z-index: 1;
        }
        .login-left .app-desc {
            font-size: 12.5px;
            color: rgba(203,213,225,.75);
            line-height: 1.65;
            position: relative;
            z-index: 1;
        }

        .divider-line {
            width: 48px;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            border-radius: 2px;
            margin: 22px auto;
            position: relative;
            z-index: 1;
        }

        .login-left .copy {
            font-size: 10.5px;
            color: rgba(148,163,184,.4);
            margin-top: 28px;
            position: relative;
            z-index: 1;
        }

        /* ── RIGHT PANEL ──────────────────────────── */
        .login-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 52px 48px;
        }

        .login-heading {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            letter-spacing: -.3px;
        }
        .login-sub {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 28px;
        }

        /* Input group with icon */
        .input-group-icon {
            position: relative;
            margin-bottom: 18px;
        }
        .input-group-icon label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 7px;
            letter-spacing: .2px;
        }
        .input-group-icon .field-wrap {
            position: relative;
        }
        .input-group-icon .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 13px;
            pointer-events: none;
            transition: color .2s;
        }
        .input-group-icon input.form-control {
            padding: 11px 14px 11px 40px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 13.5px;
            color: #1e293b;
            background: #f8fafc;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .input-group-icon input.form-control:focus {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 3.5px rgba(37,99,235,.12);
            outline: none;
        }
        .input-group-icon input.form-control:focus + .field-icon,
        .input-group-icon .field-wrap:focus-within .field-icon {
            color: #2563eb;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 11.5px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .3px;
            cursor: pointer;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 14px rgba(37,99,235,.35);
            margin-top: 8px;
        }
        .btn-login:hover {
            opacity: .93;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37,99,235,.45);
        }
        .btn-login:active { transform: translateY(0); opacity: 1; }

        /* Error alert */
        .alert-danger {
            font-size: 13px;
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid #fecaca;
            background: #fff5f5;
            color: #dc2626;
            margin-bottom: 18px;
        }

        /* Demo credentials */
        .demo-box {
            margin-top: 22px;
            padding: 12px 16px;
            background: #f1f5f9;
            border-radius: 10px;
            border-left: 3px solid #3b82f6;
        }
        .demo-box p {
            font-size: 11.5px;
            color: #64748b;
            line-height: 1.7;
            margin: 0;
        }
        .demo-box strong {
            color: #334155;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* ── RESPONSIVE ───────────────────────────── */
        @media (max-width: 767.98px) {
            .login-wrapper { flex-direction: column; max-width: 420px; min-height: unset; }
            .login-left {
                width: 100%;
                padding: 36px 28px 30px;
                flex-direction: row;
                text-align: left;
                gap: 18px;
            }
            .login-left::before { width: 200px; height: 200px; top: -70px; right: -60px; }
            .login-left::after  { display: none; }
            .logo-img { width: 60px; height: 60px; margin-bottom: 0; flex-shrink: 0; padding: 9px; border-radius: 12px; }
            .login-left .app-sub { margin-bottom: 4px; }
            .login-left .divider-line,
            .login-left .app-desc,
            .login-left .copy { display: none; }
            .login-right { padding: 32px 28px 36px; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    {{-- ── LEFT PANEL ── --}}
    <div class="login-left">
        <img src="{{ asset('logo-pekanbaru.png') }}" class="logo-img" alt="Logo Pekanbaru">
        <div>
            <div class="app-name">SIP DISNAKER</div>
            <div class="app-sub">Sistem Pelaporan</div>
            <div class="divider-line"></div>
            <p class="app-desc">
                Dinas Tenaga Kerja<br>Kota Pekanbaru<br><br>
                Sistem Informasi Pelaporan<br>Bidang Penta, PHI &amp; Lattas
            </p>
            <p class="copy">© {{ date('Y') }} Disnaker Kota Pekanbaru</p>
        </div>
    </div>

    {{-- ── RIGHT PANEL ── --}}
    <div class="login-right">

        <h4 class="login-heading">Selamat Datang</h4>
        <p class="login-sub">Masuk ke akun Anda untuk melanjutkan</p>

        @if(session('error'))
            <div class="alert-danger">
                <i class="fa fa-circle-exclamation me-1"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <div class="input-group-icon">
                <label for="username">Username</label>
                <div class="field-wrap">
                    <input type="text" id="username" name="username"
                           class="form-control" placeholder="Masukkan username" required autocomplete="username">
                    <i class="fa fa-user field-icon"></i>
                </div>
            </div>

            <div class="input-group-icon">
                <label for="password">Password</label>
                <div class="field-wrap">
                    <input type="password" id="password" name="password"
                           class="form-control" placeholder="Masukkan password" required autocomplete="current-password">
                    <i class="fa fa-lock field-icon"></i>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fa fa-right-to-bracket me-2"></i> Masuk ke Sistem
            </button>
        </form>

        <div class="demo-box">
            <p><strong>Demo Login</strong><br>
                admin / 123 &nbsp;·&nbsp; penta / 123<br>
                phi / 123 &nbsp;·&nbsp; lattas / 123
            </p>
        </div>

    </div>

</div>

</body>
</html>