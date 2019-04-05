<?php

use Illuminate\Database\Seeder;

class RoomsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Room::class, 5)->create();
    }
}
