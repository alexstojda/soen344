<?php

use Illuminate\Database\Seeder;
use App\Models\Clinic;
use App\Models\Room;
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
        // Create unscheduled appointments
        $collection = factory(Appointment::class, 50)->create();

        $collection->each(function (Appointment $appointment) {
            // only clinic #1 for now
            $availabilities = Availability::available()->ofClinicId(Clinic::first()->id);

            if ($appointment->patient->has_checkup) {
                $appointment->type = 'urgent';
            }

            if ($appointment->type === 'checkup') {
                $avail = $availabilities->length()->random();
                $ids = $avail->ids;
            } else {
                $avail = $availabilities->get()->random();
                $ids = $avail->id;
            }

            $appointment->doctor_id = $avail->doctor_id;
            $appointment->room_id = Room::availableBetween($avail->start, $avail->end)->get()->random()->id;
            $appointment->availabilities()->sync($ids);
            $appointment->saveOrFail();
        });

    }
}
