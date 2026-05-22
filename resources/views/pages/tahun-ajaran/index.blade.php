@extends('layouts.app')

@section('title', 'Data Tahun Ajaran')
@section('active', 'tahun-ajaran')

@section('content')
<div
    x-data="{
        currentPage: 1,
        perPage: 10,
        total: {{ $tahunAjarans->count() }},
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
        <div>
            <h1 class="text-2xl font-bold">Data Tahun Ajaran</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola periode tahun ajaran dan semester aktif.</p>
        </div>
        <a href="{{ route('tahun-ajaran.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            + Tambah
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <x-data-table :headers="['No', 'Tahun Ajaran', 'Semester', 'Status']">
            @forelse ($tahunAjarans as $tahunAjaran)
                <tr x-show="{{ $loop->index }} >= (currentPage - 1) * perPage && {{ $loop->index }} < currentPage * perPage" class="border-b dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 font-semibold text-gray-500">
                        <span x-text="{{ $loop->index }} + 1"></span>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $tahunAjaran->tahun_mulai }}/{{ $tahunAjaran->tahun_selesai }}
                    </td>
                    <td class="px-6 py-4">{{ $tahunAjaran->semester }}</td>
                    <td class="px-6 py-4">
                        @if ($tahunAjaran->is_active)
                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">Aktif</span>
                        @else
                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">Tidak aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('tahun-ajaran.edit', $tahunAjaran->id) }}" class="font-medium text-yellow-600 dark:text-yellow-500 hover:underline">
                                Edit
                            </a>
                            <form action="{{ route('tahun-ajaran.destroy', $tahunAjaran->id) }}" method="POST" onsubmit="return confirm('Hapus tahun ajaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data tahun ajaran.</td>
                </tr>
            @endforelse
    </x-data-table>
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
