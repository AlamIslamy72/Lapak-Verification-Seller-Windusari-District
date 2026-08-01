<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Submission;
use App\Models\Village;
use App\Models\User;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $balesari = Village::where('name', 'Balesari')->first();
        $adminDesa = User::whereHas('role', fn($q) => $q->where('name', 'admin_desa'))->first();
        $adminKecamatan = User::whereHas('role', fn($q) => $q->where('name', 'admin_kecamatan'))->first();

        $submissions = [
            [
                'registration_number' => 'LPW-2026-000001',
                'full_name' => 'Sutrisno',
                'nik' => '3308212434430001',
                'kk_number' => '3308213454330001',
                'address' => 'Balesari RT 01 RW 01',
                'village_id' => $balesari->id,
                'product_name' => 'Keripik Singkong',
                'product_description' => 'Keripik singkong menggunakan singkong pilihan berkualitas',
                'category' => 'Food',
                'status' => 'pending',
                'visited' => false,
            ],
            [
                'registration_number' => 'LPW-2026-000002',
                'full_name' => 'Sutrisno',
                'nik' => '3308212434430002',
                'kk_number' => '3308213454330002',
                'address' => 'Balesari RT 01 RW 01',
                'village_id' => $balesari->id,
                'product_name' => 'Keripik Singkong Manis',
                'product_description' => 'Keripik singkong rasa manis khas',
                'category' => 'Processed Food',
                'status' => 'verified_village',
                'village_notes' => 'Mohon pihak kecamatan mempertimbangkannya',
                'visited' => true,
                'verified_by_village_id' => $adminDesa?->id,
            ],
            [
                'registration_number' => 'LPW-2026-000003',
                'full_name' => 'Siti Aminah',
                'nik' => '3308212434430003',
                'kk_number' => '3308213454330003',
                'address' => 'Balesari RT 02 RW 01',
                'village_id' => $balesari->id,
                'product_name' => 'Kerupuk Rambak',
                'product_description' => 'Kerupuk rambak sapi asli',
                'category' => 'Food',
                'status' => 'approved',
                'visited' => true,
                'verified_by_village_id' => $adminDesa?->id,
                'verified_by_district_id' => $adminKecamatan?->id,
            ],
            [
                'registration_number' => 'LPW-2026-000004',
                'full_name' => 'Budi Santoso',
                'nik' => '3308212434430004',
                'kk_number' => '3308213454330004',
                'address' => 'Balesari RT 03 RW 02',
                'village_id' => $balesari->id,
                'product_name' => 'Es Kelapa Muda',
                'product_description' => 'Es kelapa muda segar',
                'category' => 'Beverage',
                'status' => 'rejected_by_district',
                'district_notes' => 'Foto produk tidak sesuai',
                'rejection_reason' => 'Foto produk tidak sesuai',
                'visited' => true,
                'verified_by_village_id' => $adminDesa?->id,
                'verified_by_district_id' => $adminKecamatan?->id,
            ],
            [
                'registration_number' => 'LPW-2026-000005',
                'full_name' => 'Warga Luar Windusari',
                'nik' => '3372010101800001',
                'kk_number' => '3372010101800002',
                'address' => 'Dusun Krajan RT 01 RW 02',
                'village_id' => $balesari->id,
                'product_name' => 'Keripik Singkong Manis',
                'product_description' => 'Keripik singkong dengan rasa manis khas',
                'category' => 'Processed Food',
                'status' => 'rejected_by_village',
                'village_notes' => 'Bukan warga Kecamatan Windusari',
                'rejection_reason' => 'NIK tidak terdaftar sebagai warga Windusari',
                'visited' => false,
            ],
        ];

        foreach ($submissions as $submission) {
            Submission::create($submission);
        }
    }
}
