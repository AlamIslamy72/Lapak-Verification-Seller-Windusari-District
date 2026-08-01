<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Village;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'village'])
            ->whereNotNull('role_id')
            ->orderBy('name')
            ->get();

        return view('admin.users.index', ['users' => $users]);
    }

    public function create()
    {
        $roles = Role::all();
        $villages = Village::orderBy('name')->get();

        return view('admin.users.create', ['roles' => $roles, 'villages' => $villages]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'village_id' => 'nullable|exists:villages,id',
        ]);

        $role = Role::find($validated['role_id']);
        if ($role->name === 'admin_desa' && !$validated['village_id']) {
            return back()->withErrors(['village_id' => 'Admin desa wajib memilih desa.'])->withInput();
        }
        if ($role->name === 'admin_kecamatan') {
            $validated['village_id'] = null;
        }

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('admin.users.index')->with('status', 'Akun petugas berhasil ditambahkan.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $user->delete();

        return back()->with('status', 'Akun petugas berhasil dihapus.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate(['password' => 'required|string|min:8']);

        $user->update(['password' => Hash::make($validated['password'])]);

        return back()->with('status', 'Password akun berhasil direset.');
    }
}
