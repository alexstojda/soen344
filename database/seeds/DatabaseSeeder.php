<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Base system seeder
        $this->call([
            ClinicsSeeder::class,
            RoomsSeeder::class,
            SystemUsersSeeder::class,
        ]);

        if (env('SEED_SCHEDULE', true)) {
            //Seed doctor availabilities / schedule appointments
            $this->call([
                DoctorAvailabilitiesSeeder::class,
                AppointmentsSeeder::class
            ]);
        }
    }
}
