<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubmissionStatusChanged;

class SubmissionController extends Controller
{
    protected function authorizeAccess(Request $request, Submission $submission): void
    {
        $user = $request->user();

        if ($user->isAdminDesa() && $submission->village_id !== $user->village_id) {
            abort(403, 'Anda tidak memiliki akses ke submission desa lain.');
        }
    }

    public function show(Request $request, Submission $submission)
    {
        $this->authorizeAccess($request, $submission);

        return view('submissions.show', [
            'submission' => $submission,
        ]);
    }

    public function assignCategory(Request $request, Submission $submission)
    {
        $this->authorizeAccess($request, $submission);

        $request->validate(['category' => 'required|string']);

        $submission->update(['category' => $request->category]);

        return back()->with('status', 'Kategori berhasil disimpan.');
    }

    public function uploadSurveyPhoto(Request $request, Submission $submission)
    {
        $this->authorizeAccess($request, $submission);

        $request->validate(['survey_photo' => 'required|image|max:4096']);

        $path = $request->file('survey_photo')->store('survey-photos', 'public');

        $submission->update([
            'survey_photo_url' => $path,
            'visited' => true,
        ]);

        return back()->with('status', 'Foto survey berhasil diupload. Status kunjungan otomatis diperbarui.');
    }

    public function saveVillageNotes(Request $request, Submission $submission)
    {
        $this->authorizeAccess($request, $submission);

        $request->validate(['village_notes' => 'nullable|string']);

        $submission->update(['village_notes' => $request->village_notes]);

        return back()->with('status', 'Catatan desa berhasil disimpan.');
    }

    public function saveDistrictNotes(Request $request, Submission $submission)
    {
        $this->authorizeAccess($request, $submission);

        $request->validate(['district_notes' => 'nullable|string']);

        $submission->update(['district_notes' => $request->district_notes]);

        return back()->with('status', 'Catatan kecamatan berhasil disimpan.');
    }

    public function approveVillage(Request $request, Submission $submission)
    {
        $this->authorizeAccess($request, $submission);

        $submission->update([
            'status' => 'verified_village',
            'verified_by_village_id' => $request->user()->id,
        ]);

        if ($submission->email) {
            Mail::to($submission->email)->send(new SubmissionStatusChanged($submission));
        }

        return back()->with('status', 'Submission diverifikasi desa, diteruskan ke kecamatan.');
    }

    public function rejectVillage(Request $request, Submission $submission)
    {
        $this->authorizeAccess($request, $submission);

        $request->validate(['rejection_reason' => 'required|string']);

        $submission->update([
            'status' => 'rejected_by_village',
            'rejection_reason' => $request->rejection_reason,
            'verified_by_village_id' => $request->user()->id,
        ]);

        if ($submission->email) {
            Mail::to($submission->email)->send(new SubmissionStatusChanged($submission));
        }

        return back()->with('status', 'Submission ditolak oleh desa.');
    }

    public function approveDistrict(Request $request, Submission $submission)
    {
        $submission->update([
            'status' => 'approved',
            'verified_by_district_id' => $request->user()->id,
        ]);

        if ($submission->email) {
            Mail::to($submission->email)->send(new SubmissionStatusChanged($submission));
        }

        return back()->with('status', 'Submission disetujui final oleh kecamatan.');
    }

    public function rejectDistrict(Request $request, Submission $submission)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        $submission->update([
            'status' => 'rejected_by_district',
            'rejection_reason' => $request->rejection_reason,
            'verified_by_district_id' => $request->user()->id,
        ]);

        if ($submission->email) {
            Mail::to($submission->email)->send(new SubmissionStatusChanged($submission));
        }

        return back()->with('status', 'Submission ditolak oleh kecamatan.');
    }
}
