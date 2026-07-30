<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMfaVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Kalau belum login, biarkan middleware auth yang urus
        if (!$user) {
            return $next($request);
        }

        // Kalau user belum pernah setup MFA sama sekali, paksa setup dulu
        if (!$user->google2fa_enabled) {
            if ($request->routeIs('mfa.setup') || $request->routeIs('mfa.confirm')) {
                return $next($request);
            }

            return redirect()->route('mfa.setup')
                ->with('warning', 'Anda wajib mengaktifkan MFA (Google Authenticator) sebelum melanjutkan.');
        }

        // Kalau MFA sudah aktif tapi sesi ini belum verifikasi kode, paksa verifikasi
        if (!session('mfa_verified')) {
            if ($request->routeIs('mfa.verify') || $request->routeIs('mfa.verify.submit')) {
                return $next($request);
            }

            return redirect()->route('mfa.verify');
        }

        return $next($request);
    }
}
