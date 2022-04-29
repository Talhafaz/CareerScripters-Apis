<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ServiceType::truncate();
        ServiceType::create(['name' => 'Private Sector']);
        ServiceType::create(['name' => 'Federal']);
    }
}
