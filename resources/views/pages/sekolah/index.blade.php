@extends('layouts.app')

@section('title', 'Data Sekolah')
@section('active', 'data-sekolah')

@section('content')
<div
    x-data="{
        currentPage: 1,
        perPage: 10,
        total: {{ count($sekolahs) }},
        get totalPages() { return Math.ceil(this.total / this.perPage) || 1; },
        get pageNumbers() {
            const total = this.totalPages;
            const current = Math.min(this.currentPage, total);
            const start = Math.max(1, Math.min(current - 2, total - 4));
            const end = Math.min(total, start + 4);
            return Array.from({ length: end - start + 1 }, (_, i) => start + i);
        },
        goToPage(page) { if (page >= 1 && page <= this.totalPages) this.currentPage = page; },
        resetPage() { this.currentPage = 1; }
    }"
    class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded-xl shadow"
>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Data Sekolah</h1>
        <a href="{{ route('sekolah.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            + Tambah
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border rounded-lg overflow-hidden">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3 text-left">No</th>
                <th class="p-3 text-left">Nama Sekolah</th>
                <th class="p-3 text-left">Alamat</th>
                <th class="p-3 text-left">Telepon</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sekolahs as $s)
                <tr x-show="{{ $loop->index }} >= (currentPage - 1) * perPage && {{ $loop->index }} < currentPage * perPage" class="border-t hover:bg-gray-50">
                    <td class="p-3 font-semibold text-gray-500"><span x-text="{{ $loop->index }} + 1"></span></td>
                    <td class="p-3">{{ $s->nama_sekolah }}</td>
                    <td class="p-3">{{ $s->alamat }}</td>
                    <td class="p-3">{{ $s->telepon }}</td>
                    <td class="p-3">
                        <div class="flex gap-2">
                            <a href="{{ route('sekolah.edit', $s->id) }}" class="rounded bg-yellow-500 px-3 py-1 text-white hover:bg-yellow-600">
                                Edit
                            </a>
                            <form action="{{ route('sekolah.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus data sekolah ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded bg-red-500 px-3 py-1 text-white hover:bg-red-600">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Belum ada data sekolah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="border-t border-[#e2e8f0] px-6 py-4">
        <nav class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-wrap items-center gap-4 text-[13px] text-[#64748b]">
                <div class="flex items-center gap-2"><span>Tampilkan</span><select x-model.number="perPage" @change="resetPage()" class="h-9 rounded-[10px] border border-[#e2e8f0] bg-white px-3 font-bold text-[#0f172a] outline-none transition focus:border-[#3b82f6]"><option value="10">10</option><option value="25">25</option><option value="50">50</option></select><span>data</span></div>
                <span class="hidden text-[#cbd5e1] sm:inline">&bull;</span>
                <div>Menampilkan <span class="font-bold text-[#0f172a]" x-text="total ? (((currentPage - 1) * perPage) + 1) + '-' + Math.min(currentPage * perPage, total) : '0'"></span> dari <span class="font-bold text-[#0f172a]" x-text="total"></span></div>
            </div>
            <div class="flex items-center gap-1">
                <button @click="goToPage(1)" :disabled="currentPage <= 1" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#64748b] transition hover:bg-[#f1f5f9] disabled:cursor-not-allowed disabled:text-[#cbd5e1] disabled:hover:bg-transparent"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m11 17-5-5 5-5m7 10-5-5 5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                <button @click="goToPage(currentPage - 1)" :disabled="currentPage <= 1" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#64748b] transition hover:bg-[#f1f5f9] disabled:cursor-not-allowed disabled:text-[#cbd5e1] disabled:hover:bg-transparent"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                <template x-for="p in pageNumbers" :key="p"><button @click="goToPage(p)" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[13px] font-bold transition" :class="currentPage === p ? 'bg-[#3b82f6] text-white shadow-lg shadow-blue-500/30' : 'text-[#64748b] hover:bg-[#f1f5f9]'" x-text="p"></button></template>
                <button @click="goToPage(currentPage + 1)" :disabled="currentPage >= totalPages" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#64748b] transition hover:bg-[#f1f5f9] disabled:cursor-not-allowed disabled:text-[#cbd5e1] disabled:hover:bg-transparent"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                <button @click="goToPage(totalPages)" :disabled="currentPage >= totalPages" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#64748b] transition hover:bg-[#f1f5f9] disabled:cursor-not-allowed disabled:text-[#cbd5e1] disabled:hover:bg-transparent"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m13 17 5-5-5-5M6 17l5-5-5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
            </div>
        </nav>
    </div>
</div>
@endsection
