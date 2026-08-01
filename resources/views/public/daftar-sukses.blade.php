<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md bg-white shadow-sm rounded-lg p-8 text-center">
        <h1 class="text-2xl font-bold text-green-600 mb-2">Pendaftaran Berhasil!</h1>
        <p class="text-gray-600 mb-4">Simpan data berikut untuk mengecek status pengajuan Anda.</p>
        <div class="bg-gray-100 rounded-lg p-4 mb-4 text-left space-y-2">
            <div>
                <p class="text-xs text-gray-500">Nomor Registrasi</p>
                <p class="text-xl font-bold font-mono">{{ $submission->registration_number }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">NIK</p>
                <p class="text-lg font-bold font-mono">{{ $submission->nik }}</p>
            </div>
        </div>
        <p class="text-xs text-gray-400 mb-4">Tips: screenshot halaman ini agar mudah saat cek status nanti.</p>
        <a href="{{ route('public.lacak') }}" class="text-blue-600 hover:underline text-sm">Cek Status Sekarang</a>
        <br>
        <a href="{{ route('public.daftar') }}" class="text-gray-500 hover:underline text-sm mt-2 inline-block">Daftar Produk Lain</a>
    </div>
</body>
</html>
