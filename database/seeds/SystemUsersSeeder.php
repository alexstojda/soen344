<?php

use Illuminate\Database\Seeder;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Nurse;
use App\Models\User as Patient;

class SystemUsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed first clinic with amount of staff following milestone 1 rules
        Clinic::each(function (Clinic $clinic) {
            if ($clinic->id === 1 || env('SEED_MULT_CLINICS', false)) {
                factory(Doctor::class, 7)->create([
                    'clinic_id' => $clinic->id,
                ]);
                factory(Nurse::class, 14)->create([
                    'clinic_id' => $clinic->id,
                ]);
            }
        });

        // Seed clients of the system, any amount
        factory(Patient::class, 25)->create();
    }
}
