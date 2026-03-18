<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create(['name' => 'Consultation', 'price' => 50.00]);
        Service::create(['name' => 'X-Ray', 'price' => 120.00]);
        Service::create(['name' => 'Blood Test', 'price' => 45.00]);
        Service::create(['name' => 'Dental Checkup', 'price' => 80.00]);
        Service::create(['name' => 'Pharmacy', 'price' => 30.00]);
    }
}
