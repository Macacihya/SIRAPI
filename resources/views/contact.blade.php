<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - SIRAPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between fixed top-0 left-0 right-0 z-10">
        <div>
            <p class="text-base font-semibold text-gray-800">SIRAPI</p>
            <p class="text-xs text-gray-400">Management System</p>
        </div>
        <div class="flex items-center gap-1 text-sm">
            <a href="/home" class="px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">Home</a>
            <a href="/about" class="px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">About</a>
            <a href="/features" class="px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">Features</a>
            <a href="/services" class="px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors">Services</a>
            <a href="/contact" class="px-3 py-2 rounded-lg font-medium bg-gray-100 text-gray-900">Contact</a>
            <a href="{{ route('login') }}" class="ml-2 bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition font-medium">Login</a>
        </div>
    </nav>

    {{-- MAIN --}}
    <main class="flex-1 mt-16 p-8 max-w-5xl mx-auto w-full">

        <div class="bg-white border border-gray-200 rounded-xl p-8 mb-6">
            <span class="inline-block bg-gray-100 text-gray-500 text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-md mb-4">Hubungi Kami</span>
            <h1 class="text-3xl font-black text-gray-800 mb-2">Kontak & Dukungan</h1>
            <p class="text-sm text-gray-500">Kami siap membantu Anda. Hubungi tim SIRAPI melalui kontak berikut.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Email</p>
                <p class="text-sm font-medium text-gray-800">admin@sirapi.com</p>
                <p class="text-xs text-gray-400 mt-1">Respon dalam 1x24 jam</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Telepon</p>
                <p class="text-sm font-medium text-gray-800">+62 812 3456 7890</p>
                <p class="text-xs text-gray-400 mt-1">Senin - Jumat, 08.00 - 17.00</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Alamat</p>
                <p class="text-sm font-medium text-gray-800">Jl. Pendidikan No. 1</p>
                <p class="text-xs text-gray-400 mt-1">Jakarta, Indonesia</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Dukungan Teknis</p>
                <p class="text-sm font-medium text-gray-800">support@sirapi.com</p>
                <p class="text-xs text-gray-400 mt-1">Untuk kendala teknis sistem</p>
            </div>
        </div>

    </main>
</body>
</html>