<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Kata Sandi - SIRAPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; margin: 0; }

        .input-field {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            font-size: 14px;
            color: #1e293b;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .input-field:focus { border-color: #1a3a6b; box-shadow: 0 0 0 3px rgba(26,58,107,0.1); }
        .input-field::placeholder { color: #94a3b8; }

        .btn-primary {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1a3a6b 0%, #1e4d9b 100%);
            color: white;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(26,58,107,0.35);
            transition: transform .2s, box-shadow .2s;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(26,58,107,0.4); }
        .btn-primary:disabled { background: #475569; box-shadow: none; cursor: not-allowed; transform: none; }

        .role-btn {
            padding: 10px 8px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
            cursor: pointer;
            transition: all .2s;
            text-align: center;
        }
        .role-btn.active {
            background: #1a3a6b;
            color: white;
            border-color: #1a3a6b;
            box-shadow: 0 2px 8px rgba(26,58,107,0.3);
        }

        .left-panel { background: linear-gradient(145deg, #1a3a6b 0%, #0f2347 40%, #0a1a38 100%); }
        .spinner { width:16px;height:16px;border:2px solid rgba(255,255,255,.3);border-top-color:white;border-radius:50%;animation:spin .7s linear infinite;display:inline-block;vertical-align:middle;margin-right:6px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .back-link { display:inline-flex;align-items:center;gap:6px;font-size:14px;font-weight:500;color:#64748b;text-decoration:none;margin-bottom:24px;transition:color .2s; }
        .back-link:hover { color:#1a3a6b; }
        .success-box { background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:14px;margin-bottom:24px; }
        .auth-card {
            width:100%;
            max-width:440px;
            padding:28px 24px;
            border:1px solid #dbe4f0;
            border-radius:24px;
            background:rgba(255,255,255,0.95);
            box-shadow:0 18px 44px rgba(15,23,42,0.08);
        }

        @media (max-width: 1023px) {
            .auth-card {
                padding: 24px 20px;
                box-shadow: 0 16px 40px rgba(15,23,42,0.08);
            }
        }
    </style>
</head>
<body class="min-h-screen flex">

    <!-- Left Panel -->
    <div class="left-panel hidden lg:flex flex-col justify-between p-12 relative overflow-hidden" style="width:42%">
        <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 20% 20%,rgba(59,130,246,.15) 0%,transparent 50%),radial-gradient(circle at 80% 80%,rgba(99,102,241,.1) 0%,transparent 50%)"></div>
        <div style="position:absolute;top:0;right:0;width:288px;height:288px;border-radius:50%;background:#60a5fa;opacity:.05;transform:translate(30%,-30%)"></div>
        <div style="position:absolute;bottom:0;left:0;width:384px;height:384px;border-radius:50%;background:#818cf8;opacity:.05;transform:translate(-30%,30%)"></div>
        
        <div style="position:relative;z-index:10;display:flex;align-items:center;gap:12px">
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(96,165,250,.2);border:1px solid rgba(96,165,250,.3);display:flex;align-items:center;justify-content:center">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            </div>
            <div>
                <p style="color:white;font-weight:700;font-size:20px;letter-spacing:.03em">SIRAPI</p>
                <p style="color:#93c5fd;font-size:11px;letter-spacing:.05em">SISTEM RAPOR PINTAR</p>
            </div>
        </div>

        <div style="position:relative;z-index:10">
            <h1 style="color:white;font-weight:700;font-size:38px;line-height:1.2;margin-bottom:16px">
                Kelola Rapor<br><span style="color:#60a5fa">Lebih Cerdas</span>
            </h1>
            <p style="color:#94a3b8;line-height:1.7;font-size:15px">
                Platform digital terpadu untuk mengelola nilai, absensi, dan laporan akademik siswa secara efisien dan akurat.
            </p>
        </div>

        <div style="position:relative;z-index:10">
            <p style="color:#475569;font-size:12px">&copy; {{ date('Y') }} SIRAPI &middot; Sistem Rapor Pintar</p>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="flex-1 flex items-center justify-center p-8" style="background:#f8fafc">
        <div class="auth-card">

            <!-- Back link -->
            <a href="{{ route('login') }}" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke halaman login
            </a>

            @if(session('status'))
            <!-- Success state -->
            <div style="text-align:center">
                <div style="width:80px;height:80px;border-radius:50%;background:rgba(34,197,94,.1);border:2px solid rgba(34,197,94,.2);display:flex;align-items:center;justify-content:center;margin:0 auto 24px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <h3 style="font-size:20px;font-weight:700;color:#0f172a;margin-bottom:8px">Email Terkirim!</h3>
                <p style="color:#64748b;font-size:14px;margin-bottom:8px;line-height:1.6">{{ session('status') }}</p>
                <div class="success-box">
                    <p style="font-size:13px;color:#15803d;margin:0">
                        Periksa folder <strong>Inbox</strong> atau <strong>Spam</strong> email Anda.
                        Tautan reset berlaku selama <strong>60 menit</strong>.
                    </p>
                </div>
                <a href="{{ route('login') }}" class="btn-primary" style="display:block;text-align:center;text-decoration:none;padding:14px;margin-top:8px">
                    Kembali ke Login
                </a>
            </div>
            @else
            <!-- Form state -->
            <div style="margin-bottom:28px">
                <div style="width:56px;height:56px;border-radius:16px;background:rgba(26,58,107,.08);border:1px solid rgba(26,58,107,.12);display:flex;align-items:center;justify-content:center;margin-bottom:20px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#1a3a6b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </div>
                <h2 style="font-size:24px;font-weight:700;color:#0f172a;margin-bottom:8px">Lupa Kata Sandi?</h2>
                <p style="color:#64748b;font-size:14px;line-height:1.6">
                    Masukkan email yang terdaftar. Kami akan mengirimkan tautan untuk mereset kata sandi Anda.
                </p>
            </div>

            @if($errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;color:#dc2626;font-size:14px;margin-bottom:18px">
                {{ $errors->first('email') }}
            </div>
            @endif

            <!-- Role selector -->
            <div style="margin-bottom:20px">
                <p style="font-size:14px;font-weight:500;color:#4b5563;margin-bottom:10px">Role akun:</p>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                    <button type="button" class="role-btn active" onclick="setRole(this,'admin')">Admin</button>
                    <button type="button" class="role-btn" onclick="setRole(this,'guru')">Guru</button>
                    <button type="button" class="role-btn" onclick="setRole(this,'walikelas')">Wali Kelas</button>
                </div>
            </div>

            <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
                @csrf
                <input type="hidden" name="role" id="roleInput" value="admin">

                <div style="margin-bottom:18px">
                    <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:6px">Alamat Email</label>
                    <input type="email" name="email" class="input-field"
                        placeholder="Masukkan email terdaftar"
                        value="{{ old('email') }}" required autocomplete="email">
                </div>

                <button type="submit" class="btn-primary" id="submitBtn">
                    Kirim Tautan Reset
                </button>
            </form>
            @endif

            <p style="text-align:center;font-size:12px;color:#94a3b8;margin-top:24px">
                SIRAPI &middot; Sistem Rapor Pintar &middot; {{ date('Y') }}
            </p>
        </div>
    </div>

    <script>
        function setRole(btn, role) {
            document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('roleInput').value = role;
        }
        const form = document.getElementById('forgotForm');
        if (form) {
            form.addEventListener('submit', function() {
                const btn = document.getElementById('submitBtn');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner"></span> Mengirim...';
            });
        }
    </script>
</body>
</html>
