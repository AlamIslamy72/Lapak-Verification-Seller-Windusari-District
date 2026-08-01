<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Village;

class PublicSubmissionController extends Controller
{
    public function create()
    {
        $villages = Village::orderBy('name')->get();
        return view('public.daftar', ['villages' => $villages]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nik' => 'required|regex:/^[0-9]{16}$/',
            'kk_number' => 'required|regex:/^[0-9]{16}$/',
            'address' => 'required|string',
            'village_id' => 'required|exists:villages,id',
            'product_name' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'product_photo' => 'nullable|image|max:4096',
            'nib_file' => 'nullable|file|max:4096',
        ], [
            'nik.regex' => 'NIK harus berupa 16 digit angka.',
            'kk_number.regex' => 'Nomor KK harus berupa 16 digit angka.',
        ]);

        $validated['registration_number'] = Submission::generateRegistrationNumber();
        $validated['status'] = 'pending';

        if ($request->hasFile('product_photo')) {
            $validated['product_photo_url'] = $request->file('product_photo')->store('product-photos', 'public');
        }
        if ($request->hasFile('nib_file')) {
            $validated['nib_url'] = $request->file('nib_file')->store('nib-files', 'public');
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
