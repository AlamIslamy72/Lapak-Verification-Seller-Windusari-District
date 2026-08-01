<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Desa {{ $village->name ?? '-' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('status') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-4">
                    <p class="text-xs uppercase text-yellow-700 font-semibold">Menunggu Verifikasi</p>
                    <p class="text-3xl font-bold text-yellow-800">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-green-100 border border-green-300 rounded-lg p-4">
                    <p class="text-xs uppercase text-green-700 font-semibold">Disetujui</p>
                    <p class="text-3xl font-bold text-green-800">{{ $stats['approved'] }}</p>
                </div>
                <div class="bg-red-100 border border-red-300 rounded-lg p-4">
                    <p class="text-xs uppercase text-red-700 font-semibold">Ditolak</p>
                    <p class="text-3xl font-bold text-red-800">{{ $stats['rejected'] }}</p>
                </div>
                <div class="bg-teal-100 border border-teal-300 rounded-lg p-4">
                    <p class="text-xs uppercase text-teal-700 font-semibold">Sudah Dikunjungi</p>
                    <p class="text-3xl font-bold text-teal-800">{{ $stats['visited'] }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Daftar Pengajuan</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('export.pdf') }}" class="bg-red-600 text-white px-3 py-1 rounded-md text-xs">Export PDF</a>
                        <a href="{{ route('export.csv') }}" class="bg-green-600 text-white px-3 py-1 rounded-md text-xs">Export Excel</a>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('export.pdf') }}" class="bg-red-600 text-white px-3 py-1 rounded-md text-xs">Export PDF</a>
                        <a href="{{ route('export.csv') }}" class="bg-green-600 text-white px-3 py-1 rounded-md text-xs">Export Excel</a>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('export.pdf') }}" class="bg-red-600 text-white px-3 py-1 rounded-md text-xs">Export PDF</a>
                        <a href="{{ route('export.csv') }}" class="bg-green-600 text-white px-3 py-1 rounded-md text-xs">Export Excel</a>
                    </div>
                    <div class="flex gap-2">
                        @foreach (['all' => 'Semua', 'pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $key => $label)
                            <a href="?filter={{ $key }}"
                                class="px-3 py-1 rounded-md text-xs font-medium {{ $filter === $key ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-2">Nama Penjual</th>
                                <th class="px-4 py-2">Produk</th>
                                <th class="px-4 py-2">Kategori</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($submissions as $submission)
                                <tr>
                                    <td class="px-4 py-2 font-medium">{{ $submission->full_name }}</td>
                                    <td class="px-4 py-2">{{ $submission->product_name }}</td>
                                    <td class="px-4 py-2">
                                        <span class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $submission->category ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-2">
                                        @php
                                            $statusLabels = [
                                                'pending' => ['Menunggu Verifikasi Desa', 'bg-yellow-100 text-yellow-800'],
                                                'verified_village' => ['Menunggu Verifikasi Kecamatan', 'bg-blue-100 text-blue-800'],
                                                'verified_district' => ['Diverifikasi Kecamatan', 'bg-blue-100 text-blue-800'],
                                                'approved' => ['Disetujui', 'bg-green-100 text-green-800'],
                                                'rejected_by_village' => ['Ditolak Desa', 'bg-red-100 text-red-800'],
                                                'rejected_by_district' => ['Ditolak Kecamatan', 'bg-red-100 text-red-800'],
                                            ];
                                            [$label, $color] = $statusLabels[$submission->status] ?? ['-', 'bg-gray-100'];
                                        @endphp
                                        <span class="{{ $color }} px-2 py-1 rounded text-xs font-medium">{{ $label }}</span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('submissions.show', $submission) }}" class="text-blue-600 hover:underline text-xs">Lihat</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-4 text-center text-gray-400">Belum ada pengajuan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
