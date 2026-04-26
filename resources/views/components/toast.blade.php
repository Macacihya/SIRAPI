{{-- ============================================================
     Komponen: Toast Notification (Global)
     Deskripsi: Listener toast notification yang menampilkan
     notifikasi success/error/info di pojok kanan atas.

     Di-include SEKALI di master layout (app.blade.php).
     Halaman cukup dispatch: $dispatch('toast', {message: '...', type: 'success'})

     Output HTML IDENTIK dengan toast di layouts/admin.blade.php.
     ============================================================ --}}

<div
    x-data="{
        toasts: [],
        add(e) {
            const id = Date.now();
            this.toasts.push({ id, message: e.message, type: e.type || 'success', visible: true });
            setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 3200);
        }
    }"
    @toast.window="add($event.detail)"
    class="fixed top-20 right-4 z-[200] space-y-2 pointer-events-none"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-8"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 translate-x-8"
            class="pointer-events-auto flex items-center gap-3 rounded-xl border px-5 py-3.5 shadow-lg backdrop-blur"
            :class="{
                'bg-white border-[#a7f3d0] text-[#059669]': toast.type === 'success',
                'bg-white border-[#fecaca] text-[#dc2626]': toast.type === 'error',
                'bg-white border-[#93c5fd] text-[#1d4ed8]': toast.type === 'info',
            }"
        >
            <template x-if="toast.type === 'success'">
                <svg class="h-5 w-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </template>
            <template x-if="toast.type === 'error'">
                <svg class="h-5 w-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"></path></svg>
            </template>
            <template x-if="toast.type === 'info'">
                <svg class="h-5 w-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><path d="M12 16v-4m0-4h.01" stroke-width="2" stroke-linecap="round"></path></svg>
            </template>
            <span class="text-[13px] font-bold" x-text="toast.message"></span>
        </div>
    </template>
</div>
