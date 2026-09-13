<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include("partials.pwa-head")
    @include("partials.pwa-head")
    <title>Daftar UMKM - Lapak Windusari</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-2xl mx-auto bg-white shadow-sm rounded-lg p-8">
        <h1 class="text-2xl font-bold mb-1">Pendaftaran Verifikasi UMKM</h1>
        <p class="text-gray-500 mb-6">Kecamatan Windusari</p>

        {{-- Kotak error biasa tetap ada, sebagai cadangan kalau JS dimatikan --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('public.daftar.store') }}" enctype="multipart/form-data" class="space-y-4" id="daftar-form">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full border-gray-300 rounded-md">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">NIK (16 digit)</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" required class="w-full border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">No. KK (16 digit)</label>
                    <input type="text" name="kk_number" value="{{ old('kk_number') }}" maxlength="16" required class="w-full border-gray-300 rounded-md">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Alamat Lengkap</label>
                <textarea name="address" required class="w-full border-gray-300 rounded-md">{{ old('address') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email (opsional, untuk notifikasi status)</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nomor WhatsApp Aktif</label>
                <input type="tel" name="whatsapp_number" value="{{ old('whatsapp_number') }}" placeholder="Contoh: 081234567890" required class="w-full border-gray-300 rounded-md">
                <p class="text-xs text-gray-400 mt-1">Nomor ini akan ditampilkan di katalog produk supaya calon pembeli bisa menghubungi Anda langsung.</p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Desa</label>
                <select name="village_id" required class="w-full border-gray-300 rounded-md">
                    <option value="">-- Pilih Desa --</option>
                    @foreach ($villages as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nama Produk</label>
                <input type="text" name="product_name" value="{{ old('product_name') }}" required class="w-full border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Deskripsi Produk & Harga Produk</label>
                <textarea name="product_description" class="w-full border-gray-300 rounded-md">{{ old('product_description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Foto Produk</label>
                <input type="file" name="product_photo" accept="image/*" class="w-full text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Upload NIB (opsional)</label>
                <input type="file" name="nib_file" class="w-full text-sm">
            </div>
            <button type="submit" id="daftar-submit-btn" class="w-full bg-teal-700 text-white py-2 rounded-md font-medium">Daftar Sekarang</button>
        </form>

        <script>
            // Cegah klik ganda / double-tap mengirim form dua kali sekaligus.
            document.getElementById('daftar-form').addEventListener('submit', function () {
                var btn = document.getElementById('daftar-submit-btn');
                btn.disabled = true;
                btn.textContent = 'Mengirim...';
                // form tetap lanjut submit normal, cuma tombolnya dikunci
            });
        </script>

        <p class="text-center text-sm mt-4">
            <a href="{{ route('public.lacak') }}" class="text-blue-600 hover:underline">Sudah daftar? Cek status di sini</a>
        </p>
    </div>
</body>
</html>

{{--
    Modal gelembung notifikasi. Sebelumnya modal ini menunggu session('error'),
    padahal tidak ada satupun kode yang pernah mengisi session('error') itu —
    jadi modal ini sebenarnya tidak pernah muncul. Sekarang dihubungkan ke
    $errors (termasuk pesan "sudah terdaftar sebelumnya" dari duplicate check),
    supaya benar-benar muncul saat pendaftaran ditolak.
--}}
@if ($errors->any())
<div id="modalDuplikat" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);display:flex;align-items:center;justify-content:center;z-index:9999;">
    <div style="background:#fff;border-radius:12px;padding:32px;max-width:400px;width:90%;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,0.2);">
        <p style="font-size:32px;margin-bottom:8px;">⚠️</p>
        <p style="font-size:18px;font-weight:700;color:#b91c1c;margin-bottom:12px;">
            Pendaftaran Ditolak
        </p>
        <p style="font-size:15px;color:#374151;margin-bottom:24px;line-height:1.5;">
            @foreach ($errors->all() as $e)
                {{ $e }}@if(!$loop->last)<br><br>@endif
            @endforeach
        </p>
        <button onclick="document.getElementById('modalDuplikat').style.display='none'"
                style="background:#0f766e;color:#fff;border:none;padding:10px 32px;border-radius:8px;font-size:16px;cursor:pointer;">
            Mengerti, saya akan periksa
        </button>
    </div>
</div>
@endif