<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;

class DashboardController extends Controller
{
    public function desa(Request $request)
    {
        $user = $request->user();
        $villageId = $user->village_id;
        $filter = $request->get('filter', 'all');

        $query = Submission::where('village_id', $villageId)->latest();

        match ($filter) {
            'pending' => $query->where('status', 'pending'),
            'approved' => $query->where('status', 'approved'),
            'rejected' => $query->whereIn('status', ['rejected_by_village', 'rejected_by_district']),
            default => null,
        };

        $submissions = $query->get();

        $stats = [
            'pending' => Submission::where('village_id', $villageId)->where('status', 'pending')->count(),
            'approved' => Submission::where('village_id', $villageId)->where('status', 'approved')->count(),
            'rejected' => Submission::where('village_id', $villageId)->whereIn('status', ['rejected_by_village', 'rejected_by_district'])->count(),
            'visited' => Submission::where('village_id', $villageId)->where('visited', true)->count(),
        ];

        return view('dashboard-desa', [
            'village' => $user->village,
            'stats' => $stats,
            'submissions' => $submissions,
            'filter' => $filter,
        ]);
    }

    public function kecamatan(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = Submission::latest();

        match ($filter) {
            'pending' => $query->whereIn('status', ['pending', 'verified_village']),
            'approved' => $query->where('status', 'approved'),
            'rejected' => $query->whereIn('status', ['rejected_by_village', 'rejected_by_district']),
            default => null,
        };

        $submissions = $query->get();

        $stats = [
            'pending' => Submission::whereIn('status', ['pending', 'verified_village'])->count(),
            'approved' => Submission::where('status', 'approved')->count(),
            'rejected' => Submission::whereIn('status', ['rejected_by_village', 'rejected_by_district'])->count(),
            'visited' => Submission::where('visited', true)->count(),
        ];

        return view('dashboard-kecamatan', [
            'stats' => $stats,
            'submissions' => $submissions,
            'filter' => $filter,
        ]);
    }

    public function shared(Request $request)
    {
        return view('dashboard-shared', ['user' => $request->user()]);
    }
}
