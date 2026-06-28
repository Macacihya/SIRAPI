@extends('layouts.app')
@section('title', 'Data Sekolah')
@section('subtitle', 'Informasi institusi pendidikan')
@section('active', 'data-sekolah')

@section('content')
<script>
    window.initGoogleMap = function() {
        window.dispatchEvent(new CustomEvent('google-maps-loaded'));
    };

    document.addEventListener('alpine:init', () => {
        Alpine.data('dataSekolahData', () => ({
            showBatalkan: false,
            logoUploaded: false,
            logoPreviewUrl: '{{ $sekolah->logo ? asset("storage/" . $sekolah->logo) : "" }}',
            isSaving: false,
            errors: {},

            // Koordinat peta
            latitude: '{{ old("latitude", $sekolah->latitude) }}' || -6.2088,
            longitude: '{{ old("longitude", $sekolah->longitude) }}' || 106.8456,
            map: null,
            marker: null,

            init() {
                if (window.google && window.google.maps) {
                    this.initMap();
                } else {
                    window.addEventListener('google-maps-loaded', () => {
                        this.initMap();
                    });
                }
            },

            initMap() {
                const myLatLng = { lat: parseFloat(this.latitude), lng: parseFloat(this.longitude) };

                this.map = new google.maps.Map(document.getElementById("google-map"), {
                    center: myLatLng,
                    zoom: 15,
                    mapTypeControl: false,
                    streetViewControl: false,
                    fullscreenControl: true,
                });

                this.marker = new google.maps.Marker({
                    position: myLatLng,
                    map: this.map,
                    draggable: true,
                    title: "{{ $sekolah->nama_sekolah }}"
                });

                // Event ketika marker selesai digeser (drag)
                google.maps.event.addListener(this.marker, 'dragend', () => {
                    const position = this.marker.getPosition();
                    this.latitude = position.lat().toFixed(8);
                    this.longitude = position.lng().toFixed(8);
                });

                // Event ketika peta diklik untuk pindah marker
                google.maps.event.addListener(this.map, 'click', (event) => {
                    const clickedLocation = event.latLng;
                    this.marker.setPosition(clickedLocation);
                    this.latitude = clickedLocation.lat().toFixed(8);
                    this.longitude = clickedLocation.lng().toFixed(8);
                });

                // Konfigurasi Autocomplete pencarian
                const input = document.getElementById("pac-input");
                const autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.bindTo("bounds", this.map);

                autocomplete.addListener("place_changed", () => {
                    const place = autocomplete.getPlace();
                    if (!place.geometry || !place.geometry.location) {
                        return;
                    }

                    if (place.geometry.viewport) {
                        this.map.fitBounds(place.geometry.viewport);
                    } else {
                        this.map.setCenter(place.geometry.location);
                        this.map.setZoom(17);
                    }

                    this.marker.setPosition(place.geometry.location);
                    this.latitude = place.geometry.location.lat().toFixed(8);
                    this.longitude = place.geometry.location.lng().toFixed(8);
                });
            },

            updateMarkerFromInputs() {
                const lat = parseFloat(this.latitude);
                const lng = parseFloat(this.longitude);
                if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    const latLng = new google.maps.LatLng(lat, lng);
                    if (this.marker) this.marker.setPosition(latLng);
                    if (this.map) this.map.setCenter(latLng);
                }
            },

            async submitSekolah() {
                this.isSaving = true;
                this.errors = {};

                const form = document.getElementById('formDataSekolah');
                const formData = new FormData(form);

                try {
                    const response = await fetch('{{ route("data-sekolah.update-ajax") }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        if (response.status === 422 && result.errors) {
                            this.errors = result.errors;
                            $dispatch('toast', { message: 'Periksa kembali data yang diisi.', type: 'error' });
                        } else {
                            throw new Error(result.message || 'Gagal menyimpan data sekolah.');
                        }
                        return;
                    }

                    if (result.logo_url) {
                        this.logoPreviewUrl = result.logo_url;
                    }
                    this.logoUploaded = false;
                    $dispatch('toast', { message: result.message, type: 'success' });

                } catch (e) {
                    $dispatch('toast', { message: e.message || 'Terjadi kesalahan.', type: 'error' });
                } finally {
                    this.isSaving = false;
                }
            },

            selectLogo() {
                document.getElementById('logoInput').click();
            },

            onLogoChange(e) {
                const file = e.target.files[0];
                if (file) {
                    this.logoUploaded = true;
                    this.logoPreviewUrl = URL.createObjectURL(file);
                }
            }
        }));
    });
</script>

<form id="formDataSekolah" class="space-y-6" x-data="dataSekolahData" @submit.prevent="submitSekolah">
    @csrf

    {{-- ─── HEADING ─────────────────────────────────────── --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Profil Lembaga</p>
            <h1 class="mt-1 text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Identitas Sekolah</h1>
            <p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">Perbarui informasi dasar sekolah dan detail kepala sekolah.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="showBatalkan = true"
                class="flex h-[42px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-5 text-[13px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">
                Batalkan Perubahan
            </button>
            <button type="submit" :disabled="isSaving"
                class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e40af] disabled:opacity-60 disabled:cursor-not-allowed">
                <template x-if="isSaving">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                </template>
                <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Identitas'"></span>
            </button>
        </div>
    </div>

    {{-- Error Summary --}}
    <template x-if="Object.keys(errors).length > 0">
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-[13px] font-semibold text-rose-800 space-y-1">
            <template x-for="(msgs, field) in errors" :key="field">
                <template x-for="msg in msgs" :key="msg">
                    <p x-text="'• ' + msg"></p>
                </template>
            </template>
        </div>
    </template>

    {{-- Hidden File Input --}}
    <input type="file" name="logo" id="logoInput" class="hidden" @change="onLogoChange($event)">

    {{-- ─── LOGO + INFO ────────────────────────────────── --}}
    <div class="grid gap-6 lg:grid-cols-[260px_minmax(0,1fr)]">
        <div class="space-y-4">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6 text-center">
                <div class="mx-auto flex h-[120px] w-[120px] items-center justify-center rounded-[12px] bg-[#f1f5f9] overflow-hidden">
                    <template x-if="logoPreviewUrl">
                        <img :src="logoPreviewUrl" class="h-full w-full object-cover">
                    </template>
                    <template x-if="!logoPreviewUrl">
                        <svg class="h-10 w-10 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </template>
                </div>
                <p class="mt-3 text-[13px] font-bold text-[#0f172a]" x-text="logoUploaded ? 'Logo Baru Terpilih ✓' : 'Logo Sekolah'"></p>
                <p class="mt-0.5 text-[11px] text-[#94a3b8]">Format PNG/JPG, Maks 2MB</p>
                <button type="button" @click="selectLogo()"
                    class="mt-3 flex h-[36px] w-full items-center justify-center rounded-[6px] border border-[#e2e8f0] bg-white text-[12px] font-bold uppercase tracking-[0.08em] text-[#475569] transition hover:bg-[#f1f5f9]">
                    Pilih File
                </button>
            </div>
        </div>
        <div class="space-y-6">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]">
                    <span class="text-[#1d4ed8]">|</span> Informasi Utama
                </h3>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Sekolah</label>
                        <input name="nama_sekolah"
                            class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"
                            :class="errors.nama_sekolah ? 'border-rose-400' : ''"
                            value="{{ old('nama_sekolah', $sekolah->nama_sekolah) }}">
                        <p x-show="errors.nama_sekolah" x-text="errors.nama_sekolah ? errors.nama_sekolah[0] : ''" class="mt-1 text-[11px] text-rose-500 font-semibold"></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NPSN</label>
                        <input name="npsn"
                            class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"
                            :class="errors.npsn ? 'border-rose-400' : ''"
                            value="{{ old('npsn', $sekolah->npsn) }}">
                        <p x-show="errors.npsn" x-text="errors.npsn ? errors.npsn[0] : ''" class="mt-1 text-[11px] text-rose-500 font-semibold"></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Bentuk Pendidikan</label>
                        <input name="bentuk_pendidikan"
                            class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"
                            value="{{ old('bentuk_pendidikan', $sekolah->bentuk_pendidikan) }}">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Status Sekolah</label>
                        <div class="mt-2 flex items-center gap-4">
                            <label class="flex items-center gap-2 text-[14px] font-medium text-[#0f172a] cursor-pointer">
                                <input type="radio" name="status_sekolah" value="Swasta"
                                    {{ old('status_sekolah', $sekolah->status_sekolah) === 'Swasta' ? 'checked' : '' }}
                                    class="h-4 w-4 accent-[#0f172a]"> Swasta
                            </label>
                            <label class="flex items-center gap-2 text-[14px] font-medium text-[#64748b] cursor-pointer">
                                <input type="radio" name="status_sekolah" value="Negeri"
                                    {{ old('status_sekolah', $sekolah->status_sekolah) === 'Negeri' ? 'checked' : '' }}
                                    class="h-4 w-4 accent-[#0f172a]"> Negeri
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]">
                    <span class="text-[#1d4ed8]">|</span> Data Kepala Sekolah
                </h3>
                <div class="mt-5 grid gap-4 sm:grid-cols-[120px_1fr_1fr]">
                    <div class="flex flex-col items-center gap-2 justify-center">
                        <div class="flex h-[80px] w-[80px] items-center justify-center rounded-full bg-[#f1f5f9] overflow-hidden">
                            <svg class="h-8 w-8 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama & Gelar</label>
                        <input name="nama_kepala_sekolah"
                            class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"
                            value="{{ old('nama_kepala_sekolah', $sekolah->nama_kepala_sekolah) }}">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP/NIY</label>
                        <input name="nip_kepsek"
                            class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"
                            value="{{ old('nip_kepsek', $sekolah->nip_kepsek) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── ALAMAT & KONTAK ─────────────────────────────── --}}
    <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
        <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]">
            <span class="text-[#1d4ed8]">|</span> Alamat & Kontak
        </h3>
        <div class="mt-5 grid gap-4 sm:grid-cols-[1fr_200px]">
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Alamat Lengkap</label>
                <input name="alamat"
                    class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"
                    :class="errors.alamat ? 'border-rose-400' : ''"
                    value="{{ old('alamat', $sekolah->alamat) }}">
                <p x-show="errors.alamat" x-text="errors.alamat ? errors.alamat[0] : ''" class="mt-1 text-[11px] text-rose-500 font-semibold"></p>
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kode Pos</label>
                <input name="kode_pos"
                    class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"
                    value="{{ old('kode_pos', $sekolah->kode_pos) }}">
            </div>
        </div>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email Sekolah</label>
                <input name="email" type="email"
                    class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"
                    :class="errors.email ? 'border-rose-400' : ''"
                    value="{{ old('email', $sekolah->email) }}">
                <p x-show="errors.email" x-text="errors.email ? errors.email[0] : ''" class="mt-1 text-[11px] text-rose-500 font-semibold"></p>
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nomor Telepon</label>
                <input name="telepon" type="tel"
                    class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"
                    value="{{ old('telepon', $sekolah->telepon) }}">
            </div>
        </div>
        <div class="mt-5 space-y-4">
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Cari Lokasi / Alamat</label>
                <div class="relative mt-1">
                    <input id="pac-input" type="text" placeholder="Masukkan nama tempat, gedung, atau jalan..." 
                        class="flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20 shadow-sm"
                        @keydown.enter.prevent>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div id="google-map" class="h-[350px] w-full rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] shadow-inner overflow-hidden"></div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Latitude (Lintang)</label>
                    <input name="latitude" type="text" x-model="latitude" @input="updateMarkerFromInputs"
                        class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"
                        :class="errors.latitude ? 'border-rose-400' : ''">
                    <p x-show="errors.latitude" x-text="errors.latitude ? errors.latitude[0] : ''" class="mt-1 text-[11px] text-rose-500 font-semibold"></p>
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Longitude (Bujur)</label>
                    <input name="longitude" type="text" x-model="longitude" @input="updateMarkerFromInputs"
                        class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"
                        :class="errors.longitude ? 'border-rose-400' : ''">
                    <p x-show="errors.longitude" x-text="errors.longitude ? errors.longitude[0] : ''" class="mt-1 text-[11px] text-rose-500 font-semibold"></p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Konfirmasi Batalkan ═══ --}}
    <div x-show="showBatalkan" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm"
        style="display:none" x-transition @click.self="showBatalkan = false">
        <div class="w-[90%] max-w-sm rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="p-6 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#fef2f2] text-[#dc2626] ring-4 ring-[#fee2e2]">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h3 class="mt-4 text-[18px] font-black text-[#0f172a]">Batalkan Perubahan?</h3>
                <p class="mt-2 text-[13px] text-[#64748b]">Semua perubahan yang belum disimpan akan hilang.</p>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button type="button" @click="showBatalkan = false"
                    class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Kembali</button>
                <button type="button" @click="window.location.reload()"
                    class="flex-1 rounded-lg bg-[#dc2626] py-2.5 text-[12px] font-bold text-white">Ya, Batalkan</button>
            </div>
        </div>
    </div>
</form>

@if (config('services.google.maps_api_key'))
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initGoogleMap" async defer></script>
@else
    <script>
        console.warn("Google Maps API Key belum terkonfigurasi di file .env");
    </script>
@endif
@endsection
