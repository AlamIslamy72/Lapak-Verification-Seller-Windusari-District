<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['name' => 'admin_desa', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin_kecamatan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
