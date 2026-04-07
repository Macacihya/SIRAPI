<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SIRAPI</title>
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
        .btn-primary:active { transform: translateY(0); }
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

        .eye-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .left-panel {
            background: linear-gradient(145deg, #1a3a6b 0%, #0f2347 40%, #0a1a38 100%);
        }

        .spinner {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            display: inline-block;
            vertical-align: middle;
            margin-right: 6px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 16px;
            color: #dc2626;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 12px 16px;
            color: #1d4ed8;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .field-error {
            margin-top: 6px;
            font-size: 12px;
            color: #dc2626;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            padding: 28px 24px;
            border: 1px solid #dbe4f0;
            border-radius: 24px;
            background: rgba(255,255,255,0.95);
            box-shadow: 0 18px 44px rgba(15,23,42,0.08);
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

    <!-- Left Panel - Branding -->
    <div class="left-panel hidden lg:flex flex-col justify-between p-12 relative overflow-hidden" style="width:42%">
        <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 20% 20%,rgba(59,130,246,.15) 0%,transparent 50%),radial-gradient(circle at 80% 80%,rgba(99,102,241,.1) 0%,transparent 50%)"></div>
        <div style="position:absolute;top:0;right:0;width:288px;height:288px;border-radius:50%;background:#60a5fa;opacity:.05;transform:translate(30%,-30%)"></div>
        <div style="position:absolute;bottom:0;left:0;width:384px;height:384px;border-radius:50%;background:#818cf8;opacity:.05;transform:translate(-30%,30%)"></div>
        
        <!-- Logo -->
        <div style="position:relative;z-index:10;display:flex;align-items:center;gap:12px">
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(96,165,250,.2);border:1px solid rgba(96,165,250,.3);display:flex;align-items:center;justify-content:center">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            </div>
            <div>
                <p style="color:white;font-weight:700;font-size:20px;line-height:1.2;letter-spacing:.03em">SIRAPI</p>
                <p style="color:#93c5fd;font-size:11px;letter-spacing:.05em">SISTEM RAPOR PINTAR</p>
            </div>
        </div>

        <!-- Center -->
        <div style="position:relative;z-index:10;max-width:320px;margin-top:40px">
            <h1 style="color:white;font-weight:700;font-size:38px;line-height:1.2;margin-bottom:16px">
                Kelola Rapor<br><span style="color:#60a5fa">Lebih Cerdas</span>
            </h1>
            <p style="color:#94a3b8;line-height:1.7;font-size:15px;margin-bottom:32px">
                Platform digital terpadu untuk mengelola nilai, absensi, dan laporan akademik siswa secara efisien dan akurat.
            </p>
            <div style="display:flex;flex-direction:column;gap:14px">
                @foreach([
                    'Pengolahan nilai rapor otomatis & akurat',
                    'Cetak rapor digital dengan satu klik',
                    'Multi-role: Admin, Guru & Wali Kelas',
                ] as $fitur)
                <div style="display:flex;align-items:center;gap:12px">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(96,165,250,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span style="color:#cbd5e1;font-size:14px">{{ $fitur }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div style="position:relative;z-index:10">
            <p style="color:#475569;font-size:12px">&copy; {{ date('Y') }} SIRAPI &middot; Sistem Rapor Pintar</p>
        </div>
    </div>

    <!-- Right Panel - Login Form -->
    <div class="flex-1 flex items-center justify-center p-8" style="background:#f8fafc">
        <div class="auth-card">

            <!-- Header -->
            <div style="margin-bottom:28px">
                <h2 style="font-size:28px;font-weight:700;color:#0f172a;margin-bottom:6px">Selamat Datang</h2>
                <p style="color:#64748b;font-size:14px">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            @if(session('status'))
            <div class="alert-success">{{ session('status') }}</div>
            @endif

            <!-- Errors -->
            @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
            @endif
            @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
            @endif

            <!-- Role Selector -->
            <div style="margin-bottom:22px">
                <p style="font-size:14px;font-weight:500;color:#4b5563;margin-bottom:10px">Masuk sebagai:</p>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                    <button type="button" class="role-btn active" onclick="setRole(this,'admin')" id="role-admin">Admin</button>
                    <button type="button" class="role-btn" onclick="setRole(this,'guru')" id="role-guru">Guru</button>
                    <button type="button" class="role-btn" onclick="setRole(this,'walikelas')" id="role-walikelas">Wali Kelas</button>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf
                <input type="hidden" name="role" id="roleInput" value="admin">

                <div style="display:flex;flex-direction:column;gap:18px">
                    <!-- Username -->
                    <div>
                        <label id="usernameLabel" style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:6px">Username</label>
                        <input type="text" name="username" id="usernameField" class="input-field"
                            placeholder="Masukkan username"
                            value="{{ old('username') }}" required autocomplete="username">
                        @error('username')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:6px">Kata Sandi</label>
                        <div style="position:relative">
                            <input type="password" name="password" id="passwordField" class="input-field"
                                style="padding-right:48px"
                                placeholder="Masukkan kata sandi" required autocomplete="current-password">
                            <button type="button" class="eye-btn" onclick="togglePassword()">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember + Forgot -->
                    <div style="display:flex;align-items:center;justify-content:space-between">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;color:#4b5563">
                            <input type="checkbox" name="remember" style="width:15px;height:15px;accent-color:#1a3a6b;cursor:pointer">
                            Ingat saya
                        </label>
                        <a href="{{ route('password.request') }}"
                            style="font-size:14px;font-weight:500;color:#1a3a6b;text-decoration:none"
                            onmouseover="this.style.color='#1e4d9b'" onmouseout="this.style.color='#1a3a6b'">
                            Lupa kata sandi?
                        </a>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-primary" id="submitBtn">Masuk</button>
                </div>
            </form>

            <p style="text-align:center;font-size:12px;color:#94a3b8;margin-top:20px">
                SIRAPI &middot; Sistem Rapor Pintar &middot; {{ date('Y') }}
            </p>
        </div>
    </div>

    <script>
        const roleConfig = {
            admin:     { label: 'Username',       placeholder: 'Masukkan username' },
            guru:      { label: 'NIP / Username', placeholder: 'Masukkan NIP atau username' },
            walikelas: { label: 'NIP / Username', placeholder: 'Masukkan NIP atau username' },
        };

        function setRole(btn, role) {
            document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('roleInput').value = role;
            const cfg = roleConfig[role];
            document.getElementById('usernameLabel').textContent = cfg.label;
            document.getElementById('usernameField').placeholder = cfg.placeholder;
            document.getElementById('usernameField').value = '';
        }

        function togglePassword() {
            const f = document.getElementById('passwordField');
            const icon = document.getElementById('eyeIcon');
            const hide = f.type === 'password';
            f.type = hide ? 'text' : 'password';
            icon.innerHTML = hide
                ? '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/>'
                : '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Memproses...';
        });
    </script>
</body>
</html>
