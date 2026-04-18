<x-admin-shell :user="auth()->user()" active="aturan-nilai" title="Aturan Nilai" subtitle="Konfigurasi sistem penilaian akademik">
<div x-data="{
    showHapus: false,
    showRiwayat: false,
    hapusTarget: null,
    pembulatan: 'Terdekat',
    komponen: [
        { id: 2, nama: 'Ulangan Harian (UH)', bobot: 50, kode: 'UH' },
        { id: 3, nama: 'Ujian Tengah Semester', bobot: 25, kode: 'UTS' },
        { id: 4, nama: 'Ujian Akhir Semester', bobot: 25, kode: 'UAS' },
    ],
    get totalBobot() { return this.komponen.reduce((s, k) => s + Number(k.bobot), 0); },
    get isValid() { return this.totalBobot === 100; },
    get previewNilai() {
        let sample = {TH: 85, UH: 78, PTS: 80, PAS: 90};
        let raw = this.komponen.reduce((s, k) => s + (sample[k.kode]||80) * (Number(k.bobot)/100), 0);
        if (this.pembulatan === 'Ke Atas') return Math.ceil(raw);
        if (this.pembulatan === 'Ke Bawah') return Math.floor(raw);
        return Math.round(raw);
    },
    addKomponen() { this.komponen.push({id:Date.now(), nama:'Komponen Baru', bobot:0, kode:'KB'}); },
    confirmHapus(k) { this.hapusTarget = k; this.showHapus = true; },
    doHapus() { this.komponen = this.komponen.filter(k => k.id !== this.hapusTarget.id); this.showHapus = false; $dispatch('toast',{message:'Komponen berhasil dihapus',type:'error'}); },
}" class="space-y-6">

    {{-- HEADING --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div><h1 class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Aturan Nilai</h1><p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">Konfigurasi bobot penilaian, aturan pembulatan, dan KKM untuk setiap mata pelajaran.</p></div>
        <button @click="$dispatch('toast',{message:'Konfigurasi bobot berhasil disimpan!',type:'success'})" :disabled="!isValid" class="flex h-[44px] items-center gap-2 rounded-[8px] px-5 text-[13px] font-bold text-white transition" :class="isValid ? 'bg-[#1d4ed8] hover:bg-[#1e40af]' : 'bg-[#94a3b8] cursor-not-allowed'">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            Simpan Konfigurasi
        </button>
    </div>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
        {{-- LEFT: Komponen Bobot --}}
        <div class="space-y-6">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white overflow-hidden">
                <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4">
                    <h3 class="text-[16px] font-bold text-[#0f172a]">Komponen Penilaian & Bobot</h3>
                    <button @click="addKomponen()" class="flex h-[34px] items-center gap-1.5 rounded-[6px] bg-[#1d4ed8] px-3 text-[11px] font-bold text-white hover:bg-[#1e40af]"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>Tambah</button>
                </div>
                <table class="w-full text-[13px]">
                    <thead><tr class="bg-[#f8fafc]"><th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Komponen</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b] w-[120px]">Bobot (%)</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Bar</th><th class="px-4 py-3 w-12"></th></tr></thead>
                    <tbody>
                        <template x-for="k in komponen" :key="k.id">
                            <tr class="border-t border-[#f1f5f9]">
                                <td class="px-6 py-3"><input x-model="k.nama" class="w-full bg-transparent text-[14px] font-bold text-[#0f172a] outline-none border-b border-transparent focus:border-[#3b82f6] pb-0.5"></td>
                                <td class="px-4 py-3"><input x-model.number="k.bobot" type="number" min="0" max="100" class="h-[36px] w-[80px] rounded-[6px] border border-[#e2e8f0] bg-[#f8fafc] px-3 text-center text-[14px] font-bold outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></td>
                                <td class="px-4 py-3"><div class="h-[6px] w-full overflow-hidden rounded-full bg-[#e2e8f0]"><div class="h-full rounded-full transition-all duration-300" :class="totalBobot > 100 ? 'bg-[#dc2626]' : 'bg-[#1d4ed8]'" :style="'width:'+Math.min(k.bobot,100)+'%'"></div></div></td>
                                <td class="px-4 py-3"><button @click="confirmHapus(k)" class="rounded-lg p-1.5 text-[#94a3b8] transition hover:bg-[#fef2f2] hover:text-[#dc2626]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="flex items-center justify-between border-t border-[#e2e8f0] px-6 py-3">
                    <span class="text-[12px] font-bold text-[#64748b]">Total Bobot</span>
                    <span class="text-[20px] font-black" :class="isValid ? 'text-[#059669]' : 'text-[#dc2626]'" x-text="totalBobot + '%'"></span>
                </div>
                <div x-show="!isValid" class="bg-[#fef2f2] border-t border-[#fecaca] px-6 py-2.5">
                    <p class="text-[12px] font-bold text-[#dc2626]" x-text="totalBobot > 100 ? 'Total bobot melebihi 100%! Kurangi bobot.' : 'Total bobot kurang dari 100%! Tambahkan bobot.'"></p>
                </div>
            </div>

            {{-- PEMBULATAN --}}
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="text-[16px] font-bold text-[#0f172a]">Aturan Pembulatan</h3>
                <div class="mt-4 flex flex-wrap gap-3">
                    <template x-for="opt in ['Terdekat', 'Ke Atas', 'Ke Bawah']">
                        <label class="flex cursor-pointer items-center gap-2 rounded-[8px] border px-4 py-2.5 text-[13px] font-semibold transition" :class="pembulatan === opt ? 'bg-[#0f172a] text-white border-[#0f172a]' : 'border-[#e2e8f0] text-[#475569] hover:bg-[#f1f5f9]'">
                            <input type="radio" :value="opt" x-model="pembulatan" class="hidden"><span x-text="opt"></span>
                        </label>
                    </template>
                </div>
            </div>

            {{-- KKM --}}
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="text-[16px] font-bold text-[#0f172a]">Kriteria Ketuntasan Minimal (KKM)</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">KKM Pengetahuan</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-bold text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="75"></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">KKM Keterampilan</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-bold text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="75"></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Predikat Minimum</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-bold text-[#0f172a] outline-none" value="C" readonly></div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Preview + Riwayat --}}
        <div class="space-y-4">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Preview Nilai Akhir</p>
                <p class="mt-3 text-center text-[64px] font-black tracking-[-0.06em]" :class="previewNilai >= 75 ? 'text-[#059669]' : 'text-[#dc2626]'" x-text="previewNilai"></p>
                <p class="text-center text-[12px] font-semibold text-[#64748b]">Pembulatan: <span class="text-[#0f172a]" x-text="pembulatan"></span></p>
                <div class="mt-4 space-y-2">
                    <template x-for="k in komponen" :key="k.id">
                        <div class="flex items-center justify-between text-[12px]"><span class="text-[#64748b]" x-text="k.nama"></span><span class="font-bold text-[#0f172a]" x-text="k.bobot + '%'"></span></div>
                    </template>
                </div>
                <div class="mt-4 flex items-center justify-center gap-2">
                    <span class="inline-flex rounded-md border px-2.5 py-1 text-[11px] font-bold" :class="previewNilai >= 75 ? 'border-[#a7f3d0] bg-[#ecfdf5] text-[#059669]' : 'border-[#fecaca] bg-[#fef2f2] text-[#dc2626]'" x-text="previewNilai >= 75 ? 'TUNTAS' : 'BELUM TUNTAS'"></span>
                </div>
            </div>
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Riwayat Perubahan</p>
                <div class="mt-4 space-y-3 divide-y divide-[#f1f5f9]">
                    @foreach ([['date'=>'12 Jan 2024','desc'=>'Bobot PAS diubah 35% → 30%','by'=>'Admin TU'],['date'=>'05 Des 2023','desc'=>'Tambah komponen Tugas Harian','by'=>'Admin TU']] as $r)
                        <div class="pt-3 first:pt-0"><p class="text-[12px] font-bold text-[#0f172a]">{{ $r['desc'] }}</p><p class="mt-0.5 text-[10px] text-[#94a3b8]">{{ $r['date'] }} · {{ $r['by'] }}</p></div>
                    @endforeach
                </div>
                <button @click="showRiwayat = true" class="mt-4 text-[11px] font-bold uppercase tracking-[0.08em] text-[#1d4ed8] hover:text-[#1e40af]">Lihat Riwayat Lengkap</button>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Hapus Komponen ═══ --}}
    <div x-show="showHapus" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showHapus = false">
        <div class="w-[90%] max-w-sm rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="p-6 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#fef2f2] text-[#dc2626] ring-4 ring-[#fee2e2]"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></div>
                <h3 class="mt-4 text-[18px] font-black text-[#0f172a]">Hapus Komponen?</h3>
                <p class="mt-2 text-[13px] text-[#64748b]">Komponen "<strong x-text="hapusTarget?.nama"></strong>" akan dihapus.</p>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showHapus = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                <button @click="doHapus()" class="flex-1 rounded-lg bg-[#dc2626] py-2.5 text-[12px] font-bold text-white">Ya, Hapus</button>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Riwayat Lengkap ═══ --}}
    <div x-show="showRiwayat" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showRiwayat = false">
        <div class="w-[90%] max-w-lg rounded-2xl bg-white shadow-2xl max-h-[80vh] flex flex-col" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4"><h3 class="text-[18px] font-black text-[#0f172a]">Riwayat Perubahan Lengkap</h3><button @click="showRiwayat = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button></div>
            <div class="flex-1 overflow-y-auto px-6 py-4 divide-y divide-[#f1f5f9]">
                @foreach ([['date'=>'12 Jan 2024','desc'=>'Bobot PAS diubah 35% → 30%','by'=>'Admin TU'],['date'=>'05 Des 2023','desc'=>'Tambah komponen Tugas Harian (20%)','by'=>'Admin TU'],['date'=>'10 Nov 2023','desc'=>'KKM diubah 70 → 75','by'=>'Kepsek'],['date'=>'01 Sep 2023','desc'=>'Pembulatan diubah ke Terdekat','by'=>'Admin TU'],['date'=>'15 Jul 2023','desc'=>'Konfigurasi awal semester ganjil','by'=>'System']] as $r)
                    <div class="py-3"><p class="text-[13px] font-bold text-[#0f172a]">{{ $r['desc'] }}</p><p class="mt-0.5 text-[11px] text-[#94a3b8]">{{ $r['date'] }} · {{ $r['by'] }}</p></div>
                @endforeach
            </div>
        </div>
    </div>

</div>
</x-admin-shell>
