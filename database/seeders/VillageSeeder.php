<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Village;

class VillageSeeder extends Seeder
{
    public function run(): void
    {
        $villages = [
            'Balesari', 'Bandarsedayu', 'Banjarsari', 'Candisari', 'Dampit',
            'Genito', 'Girimulyo', 'Gondangrejo', 'Gunungsari', 'Kalijoso',
            'Kembangkuning', 'Kentengsari', 'Mangunsari', 'Ngemplak', 'Pasangsari',
            'Semen', 'Tanjungsari', 'Umbulsari', 'Windusari', 'Wonoroto',
        ];

        foreach ($villages as $village) {
            Village::create([
                'name' => $village,
                'code' => null,
            ]);
        }
    }
}
