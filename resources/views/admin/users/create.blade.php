<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Akun Petugas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Password Awal</label>
                        <input type="text" name="password" value="password123" required class="w-full border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Role</label>
                        <select name="role_id" required class="w-full border-gray-300 rounded-md" onchange="document.getElementById('village-field').style.display = this.selectedOptions[0].text === 'admin_desa' ? 'block' : 'none'">
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="village-field">
                        <label class="block text-sm font-medium mb-1">Desa (khusus admin_desa)</label>
                        <select name="village_id" class="w-full border-gray-300 rounded-md">
                            <option value="">-- Pilih Desa --</option>
                            @foreach ($villages as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-teal-700 text-white py-2 rounded-md font-medium">Simpan</button>
                </form>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:underline mt-4 inline-block">← Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>
