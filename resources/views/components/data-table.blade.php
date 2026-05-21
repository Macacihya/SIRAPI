@props(['headers'])

<div class="overflow-hidden rounded-[14px] border border-[#e2e8f0] bg-white">
    <table class="w-full text-[13px]">
        <thead>
            <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                @foreach ($headers as $header)
                    <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">
                        {{ $header }}
                    </th>
                @endforeach
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">
                    Aksi
                </th>
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
