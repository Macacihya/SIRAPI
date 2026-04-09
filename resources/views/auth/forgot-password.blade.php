<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Kata Sandi - SIRAPI</title>
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
    </style>
</head>
<body class="min-h-screen bg-[var(--page-bg)] text-[var(--text-main)]">
<div class="mx-auto flex min-h-screen max-w-[1500px] flex-col justify-center px-4 py-4 sm:px-6 sm:py-6 lg:px-10 lg:py-10">
    <main class="mx-auto w-full max-w-[1260px] overflow-hidden rounded-[18px] bg-white shadow-[0_24px_60px_rgba(15,23,42,0.14)]">
        <div class="grid min-h-[760px] lg:grid-cols-[1fr_1fr]">
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
                <div class="ghost-block bottom-0 left-[48%] hidden h-[220px] w-[36px] rounded-t-[20px] rounded-b-none md:block lg:left-[45%] lg:h-[250px] lg:w-[42px]"></div>
                <div class="ghost-block bottom-[214px] left-[54%] hidden h-[36px] w-[320px] rounded-l-[20px] rounded-r-none md:block lg:bottom-[245px] lg:h-[42px] lg:w-[390px]"></div>
            </section>

            <section class="flex items-start justify-center px-6 py-8 sm:px-8 sm:py-10 md:px-12 md:py-12 lg:px-16 lg:py-14">
                <div class="w-full max-w-[520px] pt-0 text-center sm:pt-2 md:pt-6 lg:pt-10">
                    <div class="mx-auto flex h-[72px] w-[72px] items-center justify-center rounded-[16px] bg-[rgba(26,58,107,0.08)] sm:h-[78px] sm:w-[78px] md:h-[86px] md:w-[86px]">
                        <span class="material-symbols-outlined text-[34px] text-[var(--brand-1)] sm:text-[38px] md:text-[42px]">history</span>
                    </div>

                    <p class="mt-6 text-[13px] font-semibold uppercase tracking-[0.22em] text-[var(--text-muted)] sm:mt-8 sm:text-[15px] md:text-[18px] md:tracking-[0.28em]">
                        Pemulihan Akun SIRAPI
                    </p>

                    <h2 class="mt-10 text-[32px] font-extrabold tracking-[-0.05em] text-[var(--brand-3)] sm:mt-12 sm:text-[38px] md:mt-16 md:text-[44px] lg:text-[50px]">
                        Lupa Kata Sandi
                    </h2>
                    <p class="mx-auto mt-4 max-w-[360px] text-[15px] leading-[1.65] text-[var(--text-soft)] sm:text-[16px] md:text-[18px]">
                        Masukkan email Anda untuk mengatur ulang kata sandi
                    </p>

                    @if ($errors->any())
                        <div class="mt-6 rounded-[8px] border border-red-200 bg-red-50 px-4 py-3 text-left text-[14px] text-red-700 sm:mt-8">
                            {{ $errors->first('email') }}
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="mt-6 rounded-[8px] border border-blue-200 bg-blue-50 px-4 py-3 text-left text-[14px] text-blue-700 sm:mt-8">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('password.email') }}" class="mx-auto mt-8 max-w-[520px] text-left sm:mt-10 md:mt-12" id="forgotForm" method="POST">
                        @csrf

                        <label class="mb-2 block text-[12px] font-extrabold uppercase tracking-[0.08em] text-[var(--brand-1)] sm:mb-3 sm:text-[14px]" for="email">Email</label>
                        <div class="relative">
                            <input
                                autocomplete="email"
                                class="h-[58px] w-full rounded-[8px] border border-[var(--field-border)] bg-[var(--field-bg)] px-4 pr-14 text-[15px] text-[var(--text-main)] placeholder:text-[#8f9bab] focus:border-[var(--brand-2)] focus:ring-4 focus:ring-[rgba(30,77,155,0.12)] sm:h-[62px] sm:px-5 sm:pr-16 sm:text-[17px] md:h-[68px] md:text-[18px]"
                                id="email"
                                name="email"
                                placeholder="nama@email.com"
                                required
                                type="email"
                                value="{{ old('email') }}"
                            >
                            <span class="material-symbols-outlined pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-[22px] text-[var(--text-muted)] sm:text-[24px]">mail</span>
                        </div>

                        <button class="mt-8 flex h-[58px] w-full items-center justify-center gap-3 rounded-[8px] bg-gradient-to-r from-[var(--brand-1)] to-[var(--brand-2)] text-[18px] font-extrabold tracking-[-0.04em] text-white shadow-[0_14px_26px_rgba(26,58,107,0.22)] transition hover:translate-y-[-1px] hover:opacity-95 disabled:translate-y-0 disabled:opacity-70 sm:mt-10 sm:h-[64px] sm:text-[20px] md:h-[74px] md:text-[26px]" id="submitBtn" type="submit">
                            <span>Kirim Tautan Pemulihan</span>
                            <span class="material-symbols-outlined text-[22px] sm:text-[24px] md:text-[28px]">arrow_right_alt</span>
                        </button>
                    </form>

                    <div class="mx-auto mt-10 max-w-[520px] border-t border-[rgba(26,58,107,0.08)] pt-6 sm:mt-12 sm:pt-7 md:mt-14">
                        <a class="inline-flex items-center gap-2 text-[15px] font-medium text-[var(--text-soft)] hover:text-[var(--brand-2)]" href="{{ route('login') }}">
                            <span class="material-symbols-outlined text-[20px] sm:text-[22px]">arrow_back</span>
                            Kembali ke Login
                        </a>
                    </div>
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
    const forgotForm = document.getElementById('forgotForm');
    const submitButton = document.getElementById('submitBtn');

    forgotForm.addEventListener('submit', function () {
        submitButton.disabled = true;
    });
</script>
</body>
</html>
