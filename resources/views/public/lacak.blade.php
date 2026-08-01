<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cek Status Pengajuan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white shadow-sm rounded-lg p-8">
        <h1 class="text-xl font-bold mb-4">Cek Status Pengajuan</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded text-sm">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('public.lacak.result') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Nomor Registrasi</label>
                <input type="text" name="registration_number" required class="w-full border-gray-300 rounded-md" placeholder="LPW-2026-000001">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">NIK</label>
                <input type="text" name="nik" maxlength="16" required class="w-full border-gray-300 rounded-md">
            </div>
            <button class="w-full bg-gray-800 text-white py-2 rounded-md font-medium">Cek Status</button>
        </form>
    </div>
</body>
</html>
