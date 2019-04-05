<?php

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Availability;

class AppointmentsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /**
         * @var \Illuminate\Support\Collection
         */
        $collection = factory(Appointment::class, 50)->create();

        $collection->each(function (Appointment $appointment) {
            // avail of doctor where not busy and length for appt type and room free
            $appointment->doctor->availabilities()->length();
        });

    }
}
