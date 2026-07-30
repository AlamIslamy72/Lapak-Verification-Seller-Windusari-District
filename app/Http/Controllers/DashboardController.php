<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function desa(Request $request)
    {
        $user = $request->user();

        return view('dashboard-desa', [
            'village' => $user->village,
        ]);
    }

    public function kecamatan(Request $request)
    {
        return view('dashboard-kecamatan');
    }

    public function shared(Request $request)
    {
        $user = $request->user();

        return view('dashboard-shared', [
            'user' => $user,
        ]);
    }
}
