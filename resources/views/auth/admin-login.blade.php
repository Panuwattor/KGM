<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบผู้ดูแล | KGM Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --g900: #0d2818; --g800: #1a3a2a; --g700: #1e4d35;
            --gold: #c9a84c; --gold-l: #f0c040;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Sarabun', sans-serif;
            background: linear-gradient(135deg, var(--g900) 0%, var(--g700) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: white;
            border-radius: 24px;
            padding: 44px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.35);
        }
        .logo-wrap {
            text-align: center;
            margin-bottom: 32px;
        }
        .logo-icon {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, var(--gold), var(--gold-l));
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 28px;
            color: var(--g900);
            margin-bottom: 14px;
        }
        h1 { font-size: 22px; font-weight: 800; color: var(--g800); margin-bottom: 4px; }
        .subtitle { font-size: 13px; color: #999; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 6px; }
        input[type=email], input[type=password] {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            transition: border-color 0.2s;
            outline: none;
        }
        input:focus { border-color: var(--g700); }
        .is-invalid { border-color: #e74c3c !important; }
        .invalid-feedback { color: #e74c3c; font-size: 12px; margin-top: 4px; }
        .btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--g800), var(--g700));
            color: white;
            border: none;
            border-radius: 12px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
            margin-top: 6px;
        }
        .btn:hover { opacity: 0.92; transform: translateY(-1px); }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #aaa;
            text-decoration: none;
        }
        .back-link:hover { color: var(--g700); }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #fff8e8;
            border: 1px solid #f0d080;
            color: #8b6914;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 999px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo-wrap">
            <img src="{{ asset('images/kgm_logo.png') }}" alt="Verified" style="height:50px;">
            <h1>ระบบผู้ดูแล</h1>
            <p class="subtitle">KGM Back Office</p>
        </div>

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="form-group">
                <label><i class="bi bi-envelope"></i> อีเมล</label>
                <input type="email" name="email"
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email') }}" required autofocus autocomplete="email">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label><i class="bi bi-lock"></i> รหัสผ่าน</label>
                <input type="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn">
                <i class="bi bi-shield-check"></i> เข้าสู่ระบบ
            </button>
        </form>

        <a href="{{ route('home') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> กลับหน้าเว็บไซต์
        </a>
    </div>
</body>
</html>
