<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | SIP NAKER Disnaker Pekanbaru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1000px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            display: flex;
        }

        /* ================= LEFT SIDE ================= */
        .login-left {
            background: linear-gradient(180deg, #1e293b, #0f172a);
            color: white;
            padding: 60px 40px;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center; /* CENTER FIX */
            text-align: center;  /* CENTER FIX */
        }

        .login-left h2 {
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .login-left p {
            opacity: 0.85;
            font-size: 14px;
        }

        .logo-img {
            width: 110px;
            margin-bottom: 25px;
            background: white;
            padding: 15px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        /* ================= RIGHT SIDE ================= */
        .login-right {
            padding: 60px 40px;
            width: 50%;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
        }

        .btn-login {
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }

            .login-left, .login-right {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    {{-- LEFT SIDE --}}
    <div class="login-left">

        <img src="{{ asset('logo-pekanbaru.png') }}" class="logo-img img-fluid">

        <h2>SIP NAKER</h2>
        <h5 class="mb-3">Sistem Pelaporan</h5>

        <p>
            Dinas Tenaga Kerja Kota Pekanbaru<br>
            Sistem Informasi Pelaporan Bidang Penta, PHI dan Lattas
        </p>

        <hr class="w-75 text-light my-4">

        <small class="text-light">
            © {{ date('Y') }} Disnaker Kota Pekanbaru
        </small>

    </div>

    {{-- RIGHT SIDE --}}
    <div class="login-right">

        <h4 class="mb-4 fw-bold">Login Sistem</h4>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-login">
                <i class="fa fa-sign-in-alt me-2"></i> Masuk
            </button>
        </form>

        <div class="mt-4">
            <small class="text-muted">
                Demo Login:<br>
                admin / 123<br>
                penta / 123<br>
                phi / 123<br>
                lattas / 123
            </small>
        </div>

    </div>

</div>

</body>
</html>