<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SIRAPI</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <style>
        :root {
            --page-bg: #f4f7fb;
            --panel-bg: #eff4fb;
            --text-main: #0f172a;
            --text-soft: #475569;
            --text-muted: #64748b;
            --field-bg: #eef2f7;
            --field-border: #d9e2ec;
            --line-soft: rgba(15, 23, 42, 0.06);
            --brand-1: #1a3a6b;
            --brand-2: #1e4d9b;
            --brand-3: #0f2347;
        }

        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .brand-diamond::before {
            content: "";
            position: absolute;
            inset: 0;
            border: 1px solid var(--line-soft);
            transform: rotate(45deg);
            border-radius: 6px;
        }
        .ghost-block {
            position: absolute;
            border-radius: 18px;
            background: rgba(26, 58, 107, 0.055);
        }
        select { background-image: none !important; }
    </style>
</head>
<body class="min-h-screen bg-[var(--page-bg)] text-[var(--text-main)]">
<div class="mx-auto flex min-h-screen max-w-[1500px] flex-col justify-center px-4 py-4 sm:px-6 sm:py-6 lg:px-10 lg:py-10">
    <main class="mx-auto w-full max-w-[1260px] overflow-hidden rounded-[18px] bg-white shadow-[0_24px_60px_rgba(15,23,42,0.14)]">
        <div class="grid min-h-[760px] lg:grid-cols-[1.08fr_0.92fr]">
            <section class="relative overflow-hidden bg-[var(--panel-bg)] px-6 py-8 sm:px-8 sm:py-10 md:px-12 md:py-12 lg:px-16 lg:py-14">
                <div class="relative z-10">
                    <div class="relative mb-8 h-12 w-12 sm:mb-10 sm:h-14 sm:w-14 md:h-16 md:w-16 brand-diamond"></div>

                    <div class="mb-10 sm:mb-12 md:mb-14">
                        <h1 class="text-[28px] font-black uppercase tracking-[-0.05em] text-[var(--brand-1)] sm:text-[34px] md:text-[42px] lg:text-[58px] lg:leading-[0.92]">
                            SIRAPI
                        </h1>
                        <p class="mt-2 text-[10px] font-semibold uppercase tracking-[0.22em] text-[var(--text-muted)] sm:text-[11px] md:text-[13px] md:tracking-[0.24em]">
                            Sistem Rapor Pintar
                        </p>
                    </div>

                    <div class="max-w-[460px]">
                        <h2 class="text-[36px] font-black leading-[1.02] tracking-[-0.055em] text-[var(--brand-3)] sm:text-[44px] md:text-[52px] lg:text-[64px]">
                            Manajemen<br>
                            Rapor dengan<br>
                            Presisi.
                        </h2>
                        <p class="mt-6 max-w-[420px] text-[15px] leading-[1.75] text-[var(--text-soft)] sm:mt-8 sm:text-[16px] md:mt-10 md:text-[18px]">
                            Platform terintegrasi untuk pengelolaan nilai, kehadiran, dan pelaporan hasil belajar siswa secara efisien dan transparan.
                        </p>

                        <div class="mt-10 flex items-center gap-4 sm:mt-12 md:mt-14 md:gap-5">
                            <div class="relative h-14 w-14 flex-none rounded-[12px] bg-white/60 sm:h-16 sm:w-16 brand-diamond"></div>
                            <div class="min-w-0">
                                <p class="text-[18px] font-bold tracking-[-0.04em] text-[var(--brand-3)] sm:text-[20px] md:text-[24px]">sekolah dasar terpadu</p>
                                <p class="mt-1 text-[14px] text-[var(--text-soft)] sm:text-[15px] md:text-[17px]">Tahun Ajaran 2026/2027</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ghost-block bottom-[110px] right-[-80px] hidden h-[220px] w-[360px] md:block lg:bottom-[150px] lg:right-[-40px] lg:h-[230px] lg:w-[420px]"></div>
                <div class="ghost-block bottom-0 left-[48%] hidden h-[220px] w-[36px] rounded-b-none rounded-t-[20px] md:block lg:left-[45%] lg:h-[250px] lg:w-[42px]"></div>
                <div class="ghost-block bottom-[214px] left-[54%] hidden h-[36px] w-[320px] rounded-l-[20px] rounded-r-none md:block lg:bottom-[245px] lg:h-[42px] lg:w-[390px]"></div>
            </section>

            <section class="flex items-start justify-center px-6 py-8 sm:px-8 sm:py-10 md:px-12 md:py-12 lg:px-16 lg:py-14">
                <div class="w-full max-w-[520px] pt-0 sm:pt-2 md:pt-6 lg:pt-10">
                    <div class="mb-8 inline-flex items-center rounded-[4px] bg-[rgba(26,58,107,0.08)] px-3 py-2 text-[11px] font-bold uppercase tracking-[0.16em] text-[var(--brand-1)] sm:mb-10 sm:px-4 sm:text-[13px]">
                        Akses Sistem
                    </div>

                    <h3 class="text-[34px] font-extrabold tracking-[-0.05em] text-[var(--brand-3)] sm:text-[42px] md:text-[50px] lg:text-[58px]">Selamat Datang</h3>
                    <p class="mt-3 max-w-[470px] text-[15px] leading-[1.65] text-[var(--text-soft)] sm:mt-4 sm:text-[16px] md:text-[18px]">
                        Silakan masukkan kredensial Anda untuk melanjutkan ke dashboard.
                    </p>

                    @if ($errors->any())
                        <div class="mt-6 rounded-[8px] border border-red-200 bg-red-50 px-4 py-3 text-[14px] text-red-700 sm:mt-8">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="mt-6 rounded-[8px] border border-blue-200 bg-blue-50 px-4 py-3 text-[14px] text-blue-700 sm:mt-8">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" class="mt-8 space-y-6 sm:mt-10 sm:space-y-7 md:space-y-8" id="loginForm" method="POST">
                        @csrf

                        <div>
                            <label class="mb-2 block text-[12px] font-extrabold uppercase tracking-[0.08em] text-[var(--brand-1)] sm:mb-3 sm:text-[14px]" for="role">Peran Pengguna</label>
                            <div class="relative">
                                <select
                                    class="h-[58px] w-full appearance-none rounded-[8px] border border-[var(--field-border)] bg-[var(--field-bg)] px-4 pr-12 text-[15px] text-[var(--text-main)] focus:border-[var(--brand-2)] focus:ring-4 focus:ring-[rgba(30,77,155,0.12)] sm:h-[62px] sm:px-5 sm:pr-14 sm:text-[17px] md:h-[68px] md:text-[18px]"
                                    id="role"
                                    name="role"
                                    required
                                >
                                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Peran Anda</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="guru" {{ old('role') === 'guru' ? 'selected' : '' }}>Guru</option>
                                    <option value="walikelas" {{ old('role') === 'walikelas' ? 'selected' : '' }}>Wali Kelas</option>
                                </select>
                                <span class="material-symbols-outlined pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[22px] text-[var(--text-muted)] sm:text-[24px]">keyboard_arrow_down</span>
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-[12px] font-extrabold uppercase tracking-[0.08em] text-[var(--brand-1)] sm:mb-3 sm:text-[14px]" for="username">Username / NIP</label>
                            <div class="relative">
                                <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[20px] text-[var(--text-muted)] sm:text-[22px]">person</span>
                                <input
                                    autocomplete="username"
                                    class="h-[58px] w-full rounded-[8px] border border-[var(--field-border)] bg-[var(--field-bg)] pl-12 pr-4 text-[15px] text-[var(--text-main)] placeholder:text-[#8f9bab] focus:border-[var(--brand-2)] focus:ring-4 focus:ring-[rgba(30,77,155,0.12)] sm:h-[62px] sm:pl-14 sm:pr-5 sm:text-[17px] md:h-[68px] md:text-[18px]"
                                    id="username"
                                    name="username"
                                    placeholder="Masukkan ID Pegawai"
                                    required
                                    type="text"
                                    value="{{ old('username') }}"
                                >
                            </div>
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between gap-4 sm:mb-3">
                                <label class="block text-[12px] font-extrabold uppercase tracking-[0.08em] text-[var(--brand-1)] sm:text-[14px]" for="password">Kata Sandi</label>
                                <a class="text-[11px] font-extrabold uppercase tracking-[0.01em] text-[var(--brand-2)] hover:opacity-75 sm:text-[12px]" href="{{ route('password.request') }}">
                                    Lupa Sandi?
                                </a>
                            </div>
                            <div class="relative">
                                <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[20px] text-[var(--text-muted)] sm:text-[22px]">lock</span>
                                <input
                                    autocomplete="current-password"
                                    class="h-[58px] w-full rounded-[8px] border border-[var(--field-border)] bg-[var(--field-bg)] pl-12 pr-14 text-[15px] text-[var(--text-main)] placeholder:text-[#8f9bab] focus:border-[var(--brand-2)] focus:ring-4 focus:ring-[rgba(30,77,155,0.12)] sm:h-[62px] sm:pl-14 sm:pr-16 sm:text-[17px] md:h-[68px] md:text-[18px]"
                                    id="password"
                                    name="password"
                                    placeholder="........"
                                    required
                                    type="password"
                                >
                                <button class="absolute right-4 top-1/2 -translate-y-1/2 text-[var(--text-muted)] hover:text-[var(--brand-1)]" id="togglePassword" type="button">
                                    <span class="material-symbols-outlined text-[20px] sm:text-[22px]">visibility</span>
                                </button>
                            </div>
                        </div>

                        <label class="flex items-center gap-3 text-[14px] text-[var(--text-soft)] sm:text-[15px]" for="remember">
                            <input class="h-[18px] w-[18px] rounded-[4px] border-[var(--field-border)] text-[var(--brand-1)] focus:ring-[rgba(30,77,155,0.15)]" id="remember" name="remember" type="checkbox">
                            Tetap masuk selama 30 hari
                        </label>

                        <button class="flex h-[58px] w-full items-center justify-center gap-3 rounded-[8px] bg-gradient-to-r from-[var(--brand-1)] to-[var(--brand-2)] text-[18px] font-extrabold uppercase tracking-[-0.04em] text-white shadow-[0_14px_26px_rgba(26,58,107,0.22)] transition hover:translate-y-[-1px] hover:opacity-95 disabled:translate-y-0 disabled:opacity-70 sm:h-[64px] sm:text-[22px] md:h-[74px] md:text-[30px]" id="submitBtn" type="submit">
                            <span>Masuk</span>
                            <span class="material-symbols-outlined text-[22px] sm:text-[24px] md:text-[30px]">arrow_right_alt</span>
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <footer class="px-2 pt-6 text-center text-[13px] text-[var(--text-muted)] sm:pt-8 sm:text-[14px] md:pt-10 md:text-[15px]">
        Butuh bantuan teknis?
        <a class="text-[var(--brand-2)] underline underline-offset-2 transition hover:opacity-75" href="#">Hubungi Admin Sekolah</a>
    </footer>
</div>

<script>
    const passwordField = document.getElementById('password');
    const togglePasswordButton = document.getElementById('togglePassword');
    const submitButton = document.getElementById('submitBtn');
    const loginForm = document.getElementById('loginForm');

    togglePasswordButton.addEventListener('click', function () {
        const hidden = passwordField.type === 'password';
        passwordField.type = hidden ? 'text' : 'password';
        this.querySelector('span').textContent = hidden ? 'visibility_off' : 'visibility';
    });

    loginForm.addEventListener('submit', function () {
        submitButton.disabled = true;
    });
</script>
</body>
</html>
