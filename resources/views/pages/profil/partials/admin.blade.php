{{-- Profil Partial: Admin TU --}}

@php
    $user = auth()->user();
@endphp
<div x-data="{
    showEditProfil: false,
    showUbahSandi: false,
    showUploadFoto: false,
    fotoUploaded: false,
    profilForm: { 
        nama: '{{ $user->name }}', 
        username: '{{ $user->username ?? 'admin' }}',
        jabatan: 'Admin TU', 
        email: '{{ $user->email ?? 'admin@sekolah.sch.id' }}', 
        telepon: '+62 812-3456-7890', 
        unit: 'SD Negeri 01 Indonesia' 
    },
    sandiForm: { lama: '', baru: '', konfirmasi: '' },
    submitProfil() { this.showEditProfil = false; $dispatch('toast',{message:'Profil berhasil diperbarui!',type:'success'}); },
    submitSandi() { if (!this.sandiForm.lama || !this.sandiForm.baru) { $dispatch('toast',{message:'Semua field harus diisi!',type:'error'}); return; } if (this.sandiForm.baru !== this.sandiForm.konfirmasi) { $dispatch('toast',{message:'Konfirmasi password tidak cocok!',type:'error'}); return; } this.sandiForm = {lama:'',baru:'',konfirmasi:''}; this.showUbahSandi = false; $dispatch('toast',{message:'Kata sandi berhasil diubah!',type:'success'}); },
}" class="space-y-6">

    {{-- ─── TOP GRID: LEFT PROFILE + RIGHT BIODATA ──────── --}}
    <div class="grid gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">

        {{-- LEFT: Avatar + Actions + Account Info --}}
        <div class="space-y-4">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6 text-center">
                <div class="relative mx-auto h-[100px] w-[100px]">
                    <div class="flex h-full w-full items-center justify-center rounded-full bg-[#f1f5f9]">
                        <template x-if="!fotoUploaded"><svg class="h-12 w-12 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></template>
                        <template x-if="fotoUploaded"><svg class="h-12 w-12 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"></path></svg></template>
                    </div>
                    <button @click="showUploadFoto = true" class="absolute bottom-0 right-0 flex h-7 w-7 items-center justify-center rounded-full bg-[#e2e8f0] text-[#475569] shadow transition hover:bg-[#cbd5e1]">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-width="2"></path><circle cx="12" cy="13" r="3" stroke-width="2"></circle></svg>
                    </button>
                </div>
                <h2 class="mt-4 text-[18px] font-black text-[#0f172a]" x-text="profilForm.nama">{{ $user->name }}</h2>
                <p class="mt-0.5 text-[13px] text-[#64748b]" x-text="profilForm.jabatan">Admin TU</p>

                <button @click="showEditProfil = true" class="mt-5 flex h-[42px] w-full items-center justify-center gap-2 rounded-[8px] bg-[#1d4ed8] text-[13px] font-bold text-white transition hover:bg-[#1e40af]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    Ubah Profil
                </button>
                <button @click="showUbahSandi = true" class="mt-2 flex w-full items-center justify-center gap-2 py-2 text-[13px] font-semibold text-[#475569] transition hover:text-[#1d4ed8]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    Ubah Kata Sandi
                </button>
            </div>

            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Informasi Akun</p>
                <div class="mt-4 space-y-3">
                    <div class="flex items-center justify-between text-[13px]"><span class="text-[#64748b]">Status Akun</span><span class="inline-flex items-center gap-1 rounded-md border border-[#a7f3d0] bg-[#ecfdf5] px-2 py-0.5 text-[10px] font-bold text-[#059669]">AKTIF</span></div>
                    <div class="flex items-center justify-between text-[13px]"><span class="text-[#64748b]">Tahun Ajaran</span><span class="font-bold text-[#0f172a]">2026/2027 Genap</span></div>
                    <div class="flex items-center justify-between text-[13px]"><span class="text-[#64748b]">Login Terakhir</span><span class="font-bold text-[#0f172a]">Hari ini, 08:24</span></div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Biodata + Riwayat --}}
        <div class="space-y-6">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]"><svg class="h-5 w-5 text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>Biodata Lengkap</h3>
                <div class="mt-5 grid gap-x-8 gap-y-5 sm:grid-cols-2">
                    <div><p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Username Admin</p><p class="mt-1 text-[14px] font-bold text-[#0f172a]" x-text="profilForm.username"></p></div>
                    <div><p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Jabatan</p><p class="mt-1 text-[14px] font-bold text-[#0f172a]" x-text="profilForm.jabatan"></p></div>
                    <div><p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email Instansi</p><p class="mt-1 text-[14px] font-bold text-[#0f172a]" x-text="profilForm.email"></p></div>
                    <div><p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nomor Telepon</p><p class="mt-1 text-[14px] font-bold text-[#0f172a]" x-text="profilForm.telepon"></p></div>
                    <div><p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Unit Kerja</p><p class="mt-1 text-[14px] font-bold text-[#0f172a]" x-text="profilForm.unit"></p></div>
                </div>
            </div>

            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]"><svg class="h-5 w-5 text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><path d="M12 6v6l4 2" stroke-width="2" stroke-linecap="round"></path></svg>Riwayat Peran & Penugasan</h3>
                <div class="mt-6 relative">
                    <div class="absolute left-[7px] top-2 bottom-2 w-[2px] bg-[#e2e8f0]"></div>
                    <div class="space-y-7">
                        @foreach ([['year'=>'2026 - Sekarang','title'=>'Admin TU','desc'=>'Bertanggung jawab penuh atas administrasi, pengelolaan data, dan pelaporan nilai rapor siswa.','active'=>true],['year'=>'2021 - 2023','title'=>'Guru Mata Pelajaran Fisika','desc'=>'Pengampu utama mata pelajaran Fisika untuk seluruh jenjang kelas XI dan XII.','active'=>false],['year'=>'2015 - 2021','title'=>'Staf Kurikulum Bidang Akademik','desc'=>'Membantu perencanaan jadwal belajar mengajar dan koordinasi sistem penilaian sekolah.','active'=>false]] as $riwayat)
                            <div class="relative flex gap-5 pl-6">
                                <div class="absolute left-0 top-1 flex h-[16px] w-[16px] items-center justify-center rounded-full {{ $riwayat['active'] ? 'bg-[#1d4ed8]' : 'bg-[#e2e8f0]' }}">@if ($riwayat['active'])<div class="h-[6px] w-[6px] rounded-full bg-white"></div>@endif</div>
                                <div><p class="text-[11px] font-bold uppercase tracking-[0.12em] {{ $riwayat['active'] ? 'text-[#1d4ed8]' : 'text-[#94a3b8]' }}">{{ $riwayat['year'] }}</p><p class="mt-1 text-[15px] font-black text-[#0f172a]">{{ $riwayat['title'] }}</p><p class="mt-1 text-[13px] leading-[1.7] text-[#64748b]">{{ $riwayat['desc'] }}</p></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>



    <p class="text-center text-[11px] font-semibold tracking-[0.1em] text-[#94a3b8]">SIRAPI &copy; {{ date('Y') }} &bull; SISTEM RAPOR PINTAR</p>

    {{-- ═══ MODAL: Ubah Profil ═══ --}}
    <div x-show="showEditProfil" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showEditProfil = false">
        <div class="w-[90%] max-w-lg rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4"><h3 class="text-[18px] font-black text-[#0f172a]">Ubah Profil</h3><button @click="showEditProfil = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button></div>
            <div class="space-y-4 px-6 py-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Lengkap</label><input x-model="profilForm.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Username Login</label><input x-model="profilForm.username" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none" readonly></div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Jabatan</label><input x-model="profilForm.jabatan" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email</label><input x-model="profilForm.email" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" type="email"></div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nomor Telepon</label><input x-model="profilForm.telepon" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Unit Kerja</label><input x-model="profilForm.unit" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></div>
                </div>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showEditProfil = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                <button @click="submitProfil()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Simpan</button>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Ubah Kata Sandi ═══ --}}
    <div x-show="showUbahSandi" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showUbahSandi = false">
        <div class="w-[90%] max-w-sm rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4"><h3 class="text-[18px] font-black text-[#0f172a]">Ubah Kata Sandi</h3><button @click="showUbahSandi = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button></div>
            <div class="space-y-4 px-6 py-5">
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Password Lama</label><input x-model="sandiForm.lama" type="password" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="••••••••"></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Password Baru</label><input x-model="sandiForm.baru" type="password" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="••••••••"></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Konfirmasi Password</label><input x-model="sandiForm.konfirmasi" type="password" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="••••••••"></div>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showUbahSandi = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                <button @click="submitSandi()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Ubah Sandi</button>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Upload Foto ═══ --}}
    <div x-show="showUploadFoto" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showUploadFoto = false">
        <div class="w-[90%] max-w-sm rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4"><h3 class="text-[18px] font-black text-[#0f172a]">Upload Foto Profil</h3><button @click="showUploadFoto = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button></div>
            <div class="p-6">
                <div class="flex h-[160px] flex-col items-center justify-center rounded-xl border-2 border-dashed border-[#cbd5e1] bg-[#f8fafc] transition hover:border-[#3b82f6] cursor-pointer">
                    <svg class="h-10 w-10 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    <p class="mt-2 text-[13px] font-bold text-[#475569]">Klik atau seret foto ke sini</p>
                    <p class="mt-1 text-[11px] text-[#94a3b8]">PNG, JPG (Maks 2MB)</p>
                </div>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showUploadFoto = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                <button @click="fotoUploaded = true; showUploadFoto = false; $dispatch('toast',{message:'Foto profil berhasil diupload!',type:'success'})" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Upload</button>
            </div>
        </div>
    </div>

</div>
