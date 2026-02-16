<?php

namespace Database\Seeders;

use App\Models\BarangayOfficials;
use Illuminate\Database\Seeder;
use App\Models\BarangayOfficial;

class BarangayOfficialSeeder extends Seeder
{
    public function run(): void
    {
        BarangayOfficials::factory()->count(5)->create();
    }
}
