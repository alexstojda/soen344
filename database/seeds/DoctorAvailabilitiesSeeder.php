<?php

use Illuminate\Database\Seeder;
use App\Models\Availability;
use App\Models\Clinic;
use App\Models\Doctor;

class DoctorAvailabilitiesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(Availability::class, 100)->create();
    }
}
