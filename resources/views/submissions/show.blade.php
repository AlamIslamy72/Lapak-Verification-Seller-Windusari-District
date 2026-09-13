<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Submission - {{ $submission->registration_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
            <div class="p-4 bg-green-100 text-green-700 rounded">{{ session('status') }}</div>
            @endif
            @if (session('info'))
            <div class="p-4 bg-blue-100 text-blue-700 rounded">{{ session('info') }}</div>
            @endif
            @if ($errors->any())
            <div class="p-4 bg-red-100 text-red-700 rounded">{{ $errors->first() }}</div>
            @endif

            {{-- Info Utama --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold">{{ $submission->full_name }}</h3>
                        <p class="text-sm text-gray-500">{{ $submission->registration_number }} · {{ $submission->village->name }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100">
                        @php $sl=['pending'=>'Menunggu Verifikasi Desa','verified_village'=>'Menunggu Verifikasi Kecamatan','approved'=>'Disetujui','rejected_by_village'=>'Ditolak Desa','rejected_by_district'=>'Ditolak Kecamatan']; @endphp {{ $sl[$submission->status] ?? $submission->status }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500">NIK</p>
                        <p class="font-medium">{{ $submission->nik }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">No. KK</p>
                        <p class="font-medium">{{ $submission->kk_number }}</p>
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-xs text-gray-500">Alamat</p>
                    <p class="font-medium">{{ $submission->address }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-xs text-gray-500">Nomor WhatsApp</p>
                    <p class="font-medium">
                        {{ $submission->whatsapp_number ?? '-' }}
                        @if ($submission->whatsapp_number)
                            <a href="{{ $submission->whatsapp_link }}" target="_blank" class="text-green-600 text-sm underline ml-2">Chat WA</a>
                        @endif
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500">Produk</p>
                        <p class="font-medium">{{ $submission->product_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Kategori</p>
                        <p class="font-medium">{{ $submission->category ?? '-' }}</p>
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-xs text-gray-500">Deskripsi</p>
                    <p class="font-medium">{{ $submission->product_description ?? '-' }}</p>
                </div>

                @if ($submission->product_photo_url)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Foto Produk</p>
                    <img src="{{ $submission->product_photo_url }}" class="w-48 rounded border">
                </div>
                @endif
            </div>

            {{-- Assign Kategori --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h4 class="font-semibold mb-2">Assign Kategori</h4>
                <form method="POST" action="{{ route('submissions.category', $submission) }}" class="flex gap-2">
                    @csrf
                    <select name="category" class="border-gray-300 rounded-md flex-1">
                        @foreach (['Makanan', 'Minuman', 'Makanan Olahan', 'Kerajinan', 'Jasa'] as $cat)
                        <option value="{{ $cat }}" @selected($submission->category === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <button class="bg-teal-700 text-white px-4 py-2 rounded-md text-sm">Simpan</button>
                </form>
            </div>

            {{-- Upload Survey Photo --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h4 class="font-semibold mb-2">Upload Foto Survey (menandai kunjungan)</h4>
                @if ($submission->survey_photo_url)
                <img src="{{ $submission->survey_photo_url }}" class="w-48 rounded border mb-2">
                <p class="text-xs text-green-600 mb-2">✓ Sudah dikunjungi</p>
                @endif
                <form method="POST" action="{{ route('submissions.survey-photo', $submission) }}" enctype="multipart/form-data" class="flex gap-2">
                    @csrf
                    <input type="file" name="survey_photo" class="flex-1 text-sm">
                    <button class="bg-teal-700 text-white px-4 py-2 rounded-md text-sm">Upload</button>
                </form>
            </div>

            {{-- Catatan Desa --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h4 class="font-semibold mb-2">Catatan Desa</h4>
                <form method="POST" action="{{ route('submissions.village-notes', $submission) }}">
                    @csrf
                    <textarea name="village_notes" rows="2" class="w-full border-gray-300 rounded-md mb-2">{{ $submission->village_notes }}</textarea>
                    <button class="bg-teal-700 text-white px-4 py-2 rounded-md text-sm">Simpan Catatan</button>
                </form>
            </div>

            @if ($submission->district_notes || auth()->user()->isAdminKecamatan())
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h4 class="font-semibold mb-2">Catatan Kecamatan</h4>
                <form method="POST" action="{{ route('submissions.district-notes', $submission) }}">
                    @csrf
                    <textarea name="district_notes" rows="2" class="w-full border-gray-300 rounded-md mb-2">{{ $submission->district_notes }}</textarea>
                    <button class="bg-teal-700 text-white px-4 py-2 rounded-md text-sm">Simpan Catatan</button>
                </form>
            </div>
            @endif

            @if ($submission->rejection_reason)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700 text-sm">
                <strong>Alasan Penolakan:</strong> {{ $submission->rejection_reason }}
            </div>
            @endif

            {{-- Aksi Approve/Reject --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h4 class="font-semibold mb-3">Aksi Verifikasi</h4>

                @if (auth()->user()->isAdminDesa() && in_array($submission->status, ['pending']))
                <div class="flex gap-2 mb-3">
                    <form method="POST" action="{{ route('submissions.approve-village', $submission) }}">
                        @csrf
                        <button class="bg-green-600 text-white px-4 py-2 rounded-md text-sm">Verifikasi Desa → Teruskan ke Kecamatan</button>
                    </form>
                </div>
                <form method="POST" action="{{ route('submissions.reject-village', $submission) }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="rejection_reason" placeholder="Alasan penolakan..." class="border-gray-300 rounded-md flex-1 text-sm" required>
                    <button class="bg-red-600 text-white px-4 py-2 rounded-md text-sm">Tolak</button>
                </form>
                @endif

                @if (auth()->user()->isAdminKecamatan() && in_array($submission->status, ['verified_village']))
                <div class="flex gap-2 mb-3">
                    <form method="POST" action="{{ route('submissions.approve-district', $submission) }}">
                        @csrf
                        <button class="bg-green-600 text-white px-4 py-2 rounded-md text-sm">Setujui Final (Approved)</button>
                    </form>
                </div>
                <form method="POST" action="{{ route('submissions.reject-district', $submission) }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="rejection_reason" placeholder="Alasan penolakan..." class="border-gray-300 rounded-md flex-1 text-sm" required>
                    <button class="bg-red-600 text-white px-4 py-2 rounded-md text-sm">Tolak</button>
                </form>
                @endif

                @if (in_array($submission->status, ['approved', 'rejected_by_village', 'rejected_by_district']))
                <p class="text-sm text-gray-500">Submission ini sudah final, tidak ada aksi lebih lanjut.</p>
                @endif
            </div>

            <a href="{{ url()->previous() }}" class="text-blue-600 text-sm hover:underline">← Kembali</a>
        </div>
    </div>
</x-app-layout>