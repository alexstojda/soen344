<?php

use Illuminate\Database\Seeder;

class ClinicsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Clinic::class, 1)->create();
    }
}
