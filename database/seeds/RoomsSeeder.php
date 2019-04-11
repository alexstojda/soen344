<?php

use Illuminate\Database\Seeder;
use App\Models\Clinic;
use App\Models\Room;

class RoomsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Clinic::each(function (Clinic $clinic) {
            factory(Room::class, 5)->create([
                'clinic_id' => $clinic->id,
            ]);
        });
    }
}
