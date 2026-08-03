<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Akun Petugas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ $errors->first() }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold">Daftar Akun Petugas</h3>
                    <a href="{{ route('admin.users.create') }}" class="bg-teal-700 text-white px-4 py-2 rounded-md text-sm">+ Tambah Akun</a>
                </div>

                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Role</th>
                            <th class="px-4 py-2">Desa</th>
                            <th class="px-4 py-2">MFA</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $u)
                            <tr>
                                <td class="px-4 py-2 font-medium">{{ $u->name }}</td>
                                <td class="px-4 py-2">{{ $u->email }}</td>
                                <td class="px-4 py-2">{{ $u->role->name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $u->village->name ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs {{ $u->google2fa_enabled ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $u->google2fa_enabled ? 'Aktif' : 'Belum' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 flex gap-2">
                                    <form method="POST" action="{{ route('admin.users.reset-password', $u) }}" onsubmit="return confirm('Reset password ke: password123 ?')">
                                        @csrf
                                        <input type="hidden" name="password" value="password123">
                                        <button class="text-blue-600 hover:underline text-xs">Reset Password</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Yakin hapus akun ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:underline text-xs">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
