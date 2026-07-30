<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class MfaController extends Controller
{
    /**
     * Tampilkan halaman setup MFA (generate secret + QR code)
     */
    public function setup(Request $request)
    {
        $user = $request->user();

        // Kalau MFA sudah aktif, tidak perlu setup ulang
        if ($user->google2fa_enabled) {
            return redirect()->route('dashboard')->with('status', 'MFA sudah aktif.');
        }

        // Generate secret baru kalau belum ada
        if (!$user->google2fa_secret) {
            $user->google2fa_secret = Google2FA::generateSecretKey();
            $user->save();
        }

        $qrCodeUrl = Google2FA::getQRCodeInline(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );

        return view('mfa.setup', [
            'qrCodeUrl' => $qrCodeUrl,
            'secret' => $user->google2fa_secret,
        ]);
    }

    /**
     * Konfirmasi aktivasi MFA - user harus input kode dari Google Authenticator
     * untuk membuktikan setup QR code berhasil sebelum MFA benar-benar diaktifkan
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|string',
        ]);

        $user = $request->user();

        $valid = Google2FA::verifyKey(
            $user->google2fa_secret,
            $request->input('one_time_password')
        );

        if (!$valid) {
            return back()->withErrors([
                'one_time_password' => 'Kode tidak valid. Silakan cek ulang aplikasi Google Authenticator Anda.',
            ]);
        }

        $user->google2fa_enabled = true;
        $user->save();

        return redirect()->route('dashboard')->with('status', 'MFA berhasil diaktifkan!');
    }

    /**
     * Tampilkan halaman verifikasi MFA saat login
     */
    public function showVerifyForm()
    {
        return view('mfa.verify');
    }

    /**
     * Proses verifikasi kode MFA saat login
     */
    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|string',
        ]);

        $user = $request->user();

        $valid = Google2FA::verifyKey(
            $user->google2fa_secret,
            $request->input('one_time_password')
        );

        if (!$valid) {
            return back()->withErrors([
                'one_time_password' => 'Kode tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        // Tandai session ini sudah lolos verifikasi MFA
        session(['mfa_verified' => true]);

        return redirect()->intended(route('dashboard'));
    }
}
