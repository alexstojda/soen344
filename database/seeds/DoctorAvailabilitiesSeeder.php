<?php

use Illuminate\Database\Seeder;

class DoctorAvailabilitiesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Availability::class, 100)->create();
    }
}
