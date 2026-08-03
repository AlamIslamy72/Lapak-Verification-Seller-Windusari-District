<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include("partials.pwa-head")
    <title>Lapak Windusari - Verifikasi UMKM Kecamatan Windusari</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-lg w-full text-center px-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Lapak Windusari</h1>
        <p class="text-gray-500 mb-10">Sistem Verifikasi Penjual UMKM Kecamatan Windusari</p>

        <div class="grid grid-cols-1 gap-4">
            <a href="{{ route('public.daftar') }}"
                class="bg-teal-700 text-white py-4 rounded-lg font-medium hover:bg-teal-800">
                Daftar UMKM Baru
            </a>
            <a href="{{ route('public.lacak') }}"
                class="bg-white border border-gray-300 text-gray-800 py-4 rounded-lg font-medium hover:bg-gray-50">
                Cek Status Pengajuan
            </a>
            <a href="{{ route('login') }}"
                class="bg-white border border-gray-300 text-gray-800 py-4 rounded-lg font-medium hover:bg-gray-50">
                Login Petugas Desa / Kecamatan
            </a>
        </div>
    </div>
</body>

</html>