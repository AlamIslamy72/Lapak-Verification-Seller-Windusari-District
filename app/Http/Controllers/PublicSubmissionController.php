<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Village;
use App\Services\CloudinaryService;
use Illuminate\Support\Str;


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

        // Cegah data ganda: kalau NIK + nama produk yang sama baru saja didaftarkan
        // (misalnya karena user klik Back lalu submit ulang), jangan buat submission baru.
        // Cukup arahkan ke nomor registrasi yang sudah ada.
        $recentDuplicate = Submission::where('nik', $validated['nik'])
            ->where('product_name', $validated['product_name'])
            ->where('created_at', '>=', now()->subMinutes(5))
            ->first();

        if ($recentDuplicate) {
            return redirect()
                ->route('public.daftar.success', $recentDuplicate->registration_number)
                ->with('info', 'Anda sudah mendaftarkan produk ini sebelumnya. Berikut nomor registrasi Anda.');
        }

        $validated['registration_number'] = Submission::generateRegistrationNumber();
        $validated['status'] = 'pending';

        if ($request->hasFile('product_photo')) {
            $validated['product_photo_url'] = $cloudinary->upload($request->file('product_photo'), 'product-photos');
        }
        if ($request->hasFile('nib_file')) {
            $validated['nib_url'] = $cloudinary->upload($request->file('nib_file'), 'nib-files');
        }

        $submission = Submission::create($validated);

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
