<?php

use Illuminate\Database\Seeder;
use App\Models\Clinic;

class ClinicsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(Clinic::class)->create([
            'open'  => config('bonmatin.office_hours.open') . ':00:00',
            'close' => config('bonmatin.office_hours.close') . ':00:00',
        ]);

        factory(Clinic::class, 5)->create();
    }
}
