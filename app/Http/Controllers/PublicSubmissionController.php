<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Village;
use App\Services\CloudinaryService;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class PublicSubmissionController extends Controller
{
    public function create()
    {
        $villages = Village::orderBy('name')->get();

        // Cegah browser menampilkan form basi (yang sudah terisi) saat user klik tombol Back
        return response()
            ->view('public.daftar', ['villages' => $villages])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function store(Request $request, CloudinaryService $cloudinary)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nik' => 'required|regex:/^[0-9]{16}$/',
            'kk_number' => 'required|regex:/^[0-9]{16}$/',
            'address' => 'required|string',
            'email' => 'nullable|email',
            'village_id' => 'required|exists:villages,id',
            'product_name' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'product_photo' => 'nullable|image|max:4096',
            'nib_file' => 'nullable|file|max:4096',
        ], [
            'nik.regex' => 'NIK harus berupa 16 digit angka.',
            'kk_number.regex' => 'Nomor KK harus berupa 16 digit angka.',
        ]);

        // Tolak tegas kalau NIK + nama produk yang sama sudah pernah didaftarkan
        // sebelumnya (dan belum ditolak). Ini mencegah data ganda akibat submit
        // ulang (klik Back lalu Kirim lagi, double-click, dsb) dengan pesan error
        // yang jelas ke pengguna, bukan diam-diam redirect ke data lama.
        $duplicate = Submission::where('nik', $validated['nik'])
            ->where('product_name', $validated['product_name'])
            ->whereNotIn('status', ['rejected_by_village', 'rejected_by_district'])
            ->first();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'nik' => "Produk \"{$validated['product_name']}\" dengan NIK ini sudah pernah didaftarkan sebelumnya (No. Registrasi: {$duplicate->registration_number}, status: {$duplicate->status}). Silakan cek status pendaftaran Anda, atau gunakan NIK/nama produk lain jika ini pendaftaran yang berbeda.",
            ])->redirectTo(url()->previous());
        }

        $validated['registration_number'] = Submission::generateRegistrationNumber();
        $validated['status'] = 'pending';

        if ($request->hasFile('product_photo')) {
            $validated['product_photo_url'] = $cloudinary->upload($request->file('product_photo'), 'product-photos');
        }
        if ($request->hasFile('nib_file')) {
            $validated['nib_url'] = $cloudinary->upload($request->file('nib_file'), 'nib-files');
        }

        // Jaring pengaman terakhir di level database: kalau dua request submit
        // benar-benar bersamaan (race condition) lolos dari pengecekan di atas,
        // constraint unique di database akan menolak salah satunya di sini.
        try {
            $submission = Submission::create($validated);
        } catch (\Illuminate\Database\QueryException $e) {
            if ((int) $e->getCode() === 23000) {
                throw ValidationException::withMessages([
                    'nik' => 'Produk ini dengan NIK yang sama baru saja terdaftar. Silakan cek status pendaftaran Anda.',
                ])->redirectTo(url()->previous());
            }
            throw $e;
        }

        return redirect()->route('public.daftar.success', $submission->registration_number);
    }

    public function success($registrationNumber)
    {
        $submission = Submission::where('registration_number', $registrationNumber)->firstOrFail();
        return view('public.daftar-sukses', ['submission' => $submission]);
    }

    public function trackForm()
    {
        return view('public.lacak');
    }

    public function trackResult(Request $request)
    {
        $request->validate([
            'registration_number' => 'required|string',
            'nik' => 'required|regex:/^[0-9]{16}$/',
        ], [
            'nik.regex' => 'NIK harus berupa 16 digit angka.',
        ]);

        $submission = Submission::where('registration_number', trim($request->registration_number))
            ->where('nik', trim($request->nik))
            ->first();

        if (!$submission) {
            return back()->withErrors([
                'registration_number' => 'Data tidak ditemukan. Periksa kembali Nomor Registrasi dan NIK Anda.',
            ]);
        }

        return view('public.lacak-hasil', ['submission' => $submission]);
    }
}
