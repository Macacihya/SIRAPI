<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('style/style_didit.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <h1>judul merah</h1>
    <img src="{{ asset('images/didit1.png') }}" alt="">
    <img src="{{ asset('images/didit2.png') }}" alt="">
    <div class="bg-blue-500 p-4 m-4 rounded-lg text-white">
    Ini pakai Tailwind
    </div>

</body>
</html>