<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #333;">
    <h2>Update Status Pengajuan UMKM</h2>
    <p>Yth. {{ $submission->full_name }},</p>
    <p>Status pengajuan Anda dengan Nomor Registrasi <strong>{{ $submission->registration_number }}</strong> telah diperbarui.</p>

    @php
        $statusLabels = [
            'verified_village' => 'Diverifikasi Desa, diteruskan ke Kecamatan',
            'approved' => 'Disetujui',
            'rejected_by_village' => 'Ditolak oleh Desa',
            'rejected_by_district' => 'Ditolak oleh Kecamatan',
        ];
    @endphp

    <p>Status terbaru: <strong>{{ $statusLabels[$submission->status] ?? $submission->status }}</strong></p>

    @if ($submission->rejection_reason)
        <p>Alasan: {{ $submission->rejection_reason }}</p>
    @endif

    <p>Untuk melihat detail, silakan cek status melalui halaman berikut dengan memasukkan Nomor Registrasi dan NIK Anda:<br>
    {{ route('public.lacak') }}</p>

    <p>Terima kasih.<br>Lapak Windusari</p>
</body>
</html>
