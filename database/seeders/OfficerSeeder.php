<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Village;
use Illuminate\Support\Facades\Hash;

class OfficerSeeder extends Seeder
{
    public function run(): void
    {
        $adminDesaRole = Role::where('name', 'admin_desa')->first();
        $adminKecamatanRole = Role::where('name', 'admin_kecamatan')->first();
        $balesari = Village::where('name', 'Balesari')->first();

        User::create([
            'name' => 'Admin Desa Balesari',
            'email' => 'desa.balesari@windusari.test',
            'password' => Hash::make('password123'),
            'role_id' => $adminDesaRole->id,
            'village_id' => $balesari->id,
        ]);

        User::create([
            'name' => 'Admin Kecamatan Windusari',
            'email' => 'kecamatan@windusari.test',
            'password' => Hash::make('password123'),
            'role_id' => $adminKecamatanRole->id,
            'village_id' => null,
        ]);
    }
}
