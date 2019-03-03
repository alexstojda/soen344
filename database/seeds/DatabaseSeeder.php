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
        $this->call(SystemUsersSeeder::class);
        $this->call(RoomsSeeder::class);
        //Seed doctor availabilities
        $this->call(DoctorAvailabilitiesSeeder::class);
    }
}
