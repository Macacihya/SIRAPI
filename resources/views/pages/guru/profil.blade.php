<x-guru-shell
    :user="auth()->user()"
    active="profil"
    title="Profil Pengguna"
    subtitle="Panel Guru Mata Pelajaran"
>
    @php
        $user = auth()->user();
    @endphp

    <div x-data="{
        editProfileModal: false,
        passwordModal: false,
        toastVisible: false,
        toastMessage: '',
        
        userProfile: {
            name: '{{ $user->name ?? 'Drs. M. Taufik' }}',
            username: '{{ $user->username ?? 'taufik01' }}',
            nip: '{{ $user->nip ?? '197003121995031002' }}',
            email: 'taufik@sekolah.sch.id',
            phone: '+62 812-9876-5432'
        },
        
        formProfile: { name: '', username: '', nip: '', email: '', phone: '' },
        formPassword: { old: '', new: '', confirm: '' },

        openEditProfile() {
            this.formProfile = { ...this.userProfile };
            this.editProfileModal = true;
        },

        saveProfile() {
            this.userProfile = { ...this.formProfile };
            this.editProfileModal = false;
            this.showToast('Profil berhasil diperbarui!');
        },

        savePassword() {
            if(this.formPassword.new !== this.formPassword.confirm) {
                alert('Konfirmasi kata sandi baru tidak cocok!');
                return;
            }
            this.passwordModal = false;
            this.formPassword = { old: '', new: '', confirm: '' };
            this.showToast('Kata sandi berhasil diubah!');
        },

        triggerUpload() {
            this.$refs.fileInput.click();
        },

        handleFileUpload(e) {
            if(e.target.files.length > 0) {
                this.showToast('Foto profil berhasil diunggah');
            }
        },

        showToast(msg) {
            this.toastMessage = msg;
            this.toastVisible = true;
            setTimeout(() => { this.toastVisible = false }, 3000);
        }
    }" class="space-y-8 relative">

        <!-- Notifikasi Sukses -->
        <div x-show="toastVisible" style="display: none;" class="fixed bottom-6 right-6 z-[200] flex items-center gap-3 rounded-xl bg-[#0f172a] px-5 py-3.5 text-white shadow-2xl" x-transition>
            <svg class="h-5 w-5 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="text-[13px] font-bold" x-text="toastMessage"></span>
        </div>

        <div class="grid gap-6 lg:grid-cols-[320px_1fr]">
            {{-- Left: Profile Card --}}
            <div class="space-y-4">
                {{-- Photo + Name --}}
                <div class="rounded-xl bg-white p-6 text-center ring-1 ring-[#e2e8f0]">
                    <div class="relative mx-auto h-32 w-32 rounded-2xl bg-[#eff6ff] flex items-center justify-center overflow-hidden">
                        <svg class="h-16 w-16 text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        
                        <!-- Tombol Ubah Foto -->
                        <button @click="triggerUpload()" class="absolute bottom-2 right-2 flex h-7 w-7 items-center justify-center rounded-full bg-[#1d4ed8] text-white shadow-lg hover:bg-[#1e40af] transition">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-width="2"/><circle cx="12" cy="13" r="3" stroke-width="2"/></svg>
                        </button>
                        <input type="file" x-ref="fileInput" class="hidden" accept="image/*" @change="handleFileUpload">
                    </div>
                    <h2 class="mt-4 text-[20px] font-black text-[#0f172a]" x-text="userProfile.name"></h2>
                    <p class="mt-0.5 text-[13px] text-[#64748b]">Guru Mapel Bahasa Indonesia</p>

                    <button @click="openEditProfile()" class="mt-5 flex w-full items-center justify-center gap-2 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white transition hover:bg-[#1e40af]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Update Biodata
                    </button>
                    <button @click="passwordModal = true" class="mt-2 flex w-full items-center justify-center gap-2 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Ubah Kata Sandi
                    </button>
                </div>

                {{-- Info Akun --}}
                <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Status Kinerja</p>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[13px] text-[#475569]">Jam Mengajar</span>
                            <span class="text-[13px] font-bold text-[#0f172a]">24 Jam / Minggu</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[13px] text-[#475569]">Kelas Diampu</span>
                            <span class="text-[13px] font-bold text-[#0f172a]">6 Kelas</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[13px] text-[#475569]">Kehadiran Bulan Ini</span>
                            <span class="rounded px-2 py-0.5 text-[10px] font-extrabold uppercase bg-[#f0fdf4] text-[#16a34a]">100%</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Biodata + History --}}
            <div class="space-y-6">
                {{-- Biodata --}}
                <div class="rounded-xl bg-white p-6 ring-1 ring-[#e2e8f0]">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <h3 class="text-[18px] font-black text-[#0f172a]">Informasi Guru</h3>
                    </div>
                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nama Lengkap / Gelar</p>
                            <p class="mt-1 text-[14px] font-semibold text-[#0f172a]" x-text="userProfile.name"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">NIP / NUPTK</p>
                            <p class="mt-1 text-[14px] font-semibold text-[#0f172a]" x-text="userProfile.nip"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Username Akun</p>
                            <p class="mt-1 text-[14px] font-semibold text-[#0f172a]" x-text="userProfile.username"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Jabatan Kategori</p>
                            <p class="mt-1 text-[14px] font-semibold text-[#0f172a]">Guru Ahli Madya</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nama Sekolah</p>
                            <p class="mt-1 text-[14px] font-semibold text-[#0f172a]">SD Negeri 01 Indonesia</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Email Kontak</p>
                            <p class="mt-1 text-[14px] font-semibold text-[#0f172a]" x-text="userProfile.email"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nomor WhatsApp/Telepon</p>
                            <p class="mt-1 text-[14px] font-semibold text-[#0f172a]" x-text="userProfile.phone"></p>
                        </div>
                    </div>
                </div>

                {{-- Riwayat --}}
                <div class="rounded-xl bg-white p-6 ring-1 ring-[#e2e8f0]">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <h3 class="text-[18px] font-black text-[#0f172a]">Riwayat Mengajar & Lintas Kelas</h3>
                    </div>
                    <div class="mt-6 space-y-6">
                        @foreach ([
                            ['year' => 'TA 2026/2027', 'role' => 'Bahasa Indonesia (I, II, III, IV, V, VI)', 'desc' => 'Mengampu mata pelajaran Bahasa Indonesia untuk kelas atas maupun bawah sebagai implementasi Kurikulum Merdeka secara menyeluruh.', 'active' => true],
                            ['year' => 'TA 2025/2026', 'role' => 'Bahasa Indonesia & Muatan Lokal', 'desc' => 'Menjadi guru inti dalam penyusunan RPP Bahasa Indonesia tingkat kota dan pengampu Muatan Lokal Kesenian.', 'active' => false],
                            ['year' => 'TA 2020 - 2024', 'role' => 'Guru Kelas VI', 'desc' => 'Pernah menjabat sebagai Wali Kelas VI dan guru kelas borongan sebelum sistem Subject Teacher (Guru Mapel) diterapkan optimal.', 'active' => false],
                        ] as $r)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="h-3 w-3 rounded-full {{ $r['active'] ? 'bg-[#1d4ed8]' : 'bg-[#cbd5e1]' }}"></div>
                                    <div class="w-px flex-1 bg-[#e2e8f0]"></div>
                                </div>
                                <div class="pb-6">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] {{ $r['active'] ? 'text-[#1d4ed8]' : 'text-[#64748b]' }}">{{ $r['year'] }}</p>
                                    <p class="mt-1 text-[15px] font-bold text-[#0f172a]">{{ $r['role'] }}</p>
                                    <p class="mt-1 text-[13px] leading-relaxed text-[#475569]">{{ $r['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>


            </div>
        </div>

        {{-- Footer --}}
        <div class="border-t border-[#e2e8f0] pt-6 text-center">
            <p class="text-[11px] font-semibold uppercase tracking-[0.15em] text-[#94a3b8]">SIRAPI © {{ date('Y') }} • Sistem Rapor Pintar</p>
        </div>

        <!-- ======================= -->
        <!-- MODALS                    -->
        <!-- ======================= -->

        <!-- Modal Edit Profil -->
        <div x-show="editProfileModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" x-transition>
            <div @click.outside="editProfileModal = false" class="bg-white rounded-2xl w-[450px] shadow-xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-[#e2e8f0] bg-[#f8fafc]">
                    <div>
                        <h3 class="text-[16px] font-black text-[#0f172a]">Ubah Biodata</h3>
                        <p class="text-[12px] text-[#64748b]">Perbarui data kontak Anda.</p>
                    </div>
                    <button @click="editProfileModal = false" class="text-[#94a3b8] hover:text-[#0f172a] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-[#64748b] mb-1">Nama Lengkap / Gelar</label>
                        <input type="text" x-model="formProfile.name" class="w-full h-11 rounded-lg border border-[#e2e8f0] px-3 font-semibold text-[13px] text-[#0f172a] focus:ring-2 focus:ring-[#3b82f6]/20 outline-none transition">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-[#64748b] mb-1">Username Akun</label>
                            <input type="text" x-model="formProfile.username" class="w-full h-11 rounded-lg border border-[#e2e8f0] bg-[#f8fafc] px-3 font-semibold text-[13px] text-[#0f172a] outline-none" readonly>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-[#64748b] mb-1">NIP / NUPTK</label>
                            <input type="text" x-model="formProfile.nip" class="w-full h-11 rounded-lg border border-[#e2e8f0] bg-[#f8fafc] px-3 font-semibold text-[13px] text-[#0f172a] outline-none" readonly>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-[#64748b] mb-1">Email Sekolah</label>
                        <input type="email" x-model="formProfile.email" class="w-full h-11 rounded-lg border border-[#e2e8f0] px-3 font-semibold text-[13px] text-[#0f172a] focus:ring-2 focus:ring-[#3b82f6]/20 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-[#64748b] mb-1">Nomor WhatsApp/Telepon</label>
                        <input type="text" x-model="formProfile.phone" class="w-full h-11 rounded-lg border border-[#e2e8f0] px-3 font-semibold text-[13px] text-[#0f172a] focus:ring-2 focus:ring-[#3b82f6]/20 outline-none transition">
                    </div>
                </div>
                <div class="px-6 py-4 bg-[#f8fafc] border-t border-[#e2e8f0] flex justify-end gap-3">
                    <button @click="editProfileModal = false" class="px-4 py-2 rounded-lg text-[13px] font-bold text-[#64748b] hover:bg-[#f1f5f9] transition">Batal</button>
                    <button @click="saveProfile()" class="px-4 py-2 rounded-lg bg-[#1d4ed8] text-[13px] font-bold text-white hover:bg-[#1e40af] transition">Simpan</button>
                </div>
            </div>
        </div>

        <!-- Modal Ubah Sandi -->
        <div x-show="passwordModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" x-transition>
            <div @click.outside="passwordModal = false" class="bg-white rounded-2xl w-[400px] shadow-xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-[#e2e8f0] bg-[#f8fafc]">
                    <div>
                        <h3 class="text-[16px] font-black text-[#0f172a]">Keamanan Sandi</h3>
                        <p class="text-[12px] text-[#64748b]">Jaga keamanan akun Anda.</p>
                    </div>
                    <button @click="passwordModal = false" class="text-[#94a3b8] hover:text-[#0f172a] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-[#64748b] mb-1">Kata Sandi Saat Ini</label>
                        <input type="password" x-model="formPassword.old" class="w-full h-11 rounded-lg border border-[#e2e8f0] px-3 text-[13px] text-[#0f172a] focus:ring-2 focus:ring-[#3b82f6]/20 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-[#64748b] mb-1">Kata Sandi Baru</label>
                        <input type="password" x-model="formPassword.new" class="w-full h-11 rounded-lg border border-[#e2e8f0] px-3 text-[13px] text-[#0f172a] focus:ring-2 focus:ring-[#3b82f6]/20 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-[#64748b] mb-1">Ulangi Sandi Baru</label>
                        <input type="password" x-model="formPassword.confirm" class="w-full h-11 rounded-lg border border-[#e2e8f0] px-3 text-[13px] text-[#0f172a] focus:ring-2 focus:ring-[#3b82f6]/20 outline-none transition">
                    </div>
                </div>
                <div class="px-6 py-4 bg-[#f8fafc] border-t border-[#e2e8f0] flex justify-end gap-3">
                    <button @click="passwordModal = false" class="px-4 py-2 rounded-lg text-[13px] font-bold text-[#64748b] hover:bg-[#f1f5f9] transition">Batal</button>
                    <button @click="savePassword()" class="px-4 py-2 rounded-lg bg-[#1d4ed8] text-[13px] font-bold text-white hover:bg-[#1e40af] transition">Ganti Sandi</button>
                </div>
            </div>
        </div>

    </div>
</x-guru-shell>
