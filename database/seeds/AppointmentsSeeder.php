<?php

use Illuminate\Database\Seeder;

class AppointmentsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Appointment::class, 50)->create();
    }
}
