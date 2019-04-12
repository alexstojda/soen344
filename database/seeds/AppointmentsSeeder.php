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

        // Attempt to schedule patient appointments
        $collection->each(function (Appointment $appointment) {
            $availabilities = Availability::available();

            if ($appointment->patient->has_checkup) {
                $appointment->type = 'urgent';
            }

            if ($appointment->type === 'checkup') {
                if ($availabilities->length()->isEmpty()) {
                    abort(412, 'No more 60m timeslots available');
                }
                $avail = $availabilities->length()->random();
                $ids = $avail->ids;
            } else {
                $avail = $availabilities->get()->random();
                $ids = $avail->id;
            }

            $appointment->doctor_id = $avail->doctor_id;

            $rooms = Doctor::findOrFail($avail->doctor_id)->clinic->roomsBetween($avail->start, $avail->end)->get();
            if ($rooms->isNotEmpty()) {
                $appointment->room_id = $rooms->random()->id;
                $appointment->availabilities()->sync($ids);
            } else {
                $appointment->room_id = null;
                $appointment->status = 'cancelled';
            }
            $appointment->saveOrFail();
        });

    }
}
