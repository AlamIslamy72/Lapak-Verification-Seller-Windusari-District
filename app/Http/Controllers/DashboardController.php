<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $query = Submission::with('village')->latest();

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

    public function exportPdf(Request $request)
    {
        $user = $request->user();
        $query = Submission::latest();

        if ($user->isAdminDesa()) {
            $query->where('village_id', $user->village_id);
        }

        $submissions = $query->get();
        $title = $user->isAdminDesa() ? 'Laporan Desa ' . $user->village->name : 'Laporan Kecamatan Windusari';

        $pdf = Pdf::loadView('exports.submissions-pdf', [
            'submissions' => $submissions,
            'title' => $title,
        ]);

        return $pdf->download('laporan-umkm-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportCsv(Request $request)
    {
        $user = $request->user();
        $query = Submission::latest();

        if ($user->isAdminDesa()) {
            $query->where('village_id', $user->village_id);
        }

        $submissions = $query->get();

        $filename = 'laporan-umkm-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($submissions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No. Registrasi', 'Nama', 'NIK', 'Desa', 'Produk', 'Kategori', 'Status', 'Dikunjungi', 'Tanggal Daftar']);

            foreach ($submissions as $s) {
                fputcsv($file, [
                    $s->registration_number,
                    $s->full_name,
                    $s->nik,
                    $s->village->name,
                    $s->product_name,
                    $s->category,
                    $s->status,
                    $s->visited ? 'Ya' : 'Tidak',
                    $s->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
