<?php

use Illuminate\Database\Seeder;

class SystemUsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Doctor::class, 7)->create();
        factory(App\Models\Nurse::class, 14)->create();
        factory(App\Models\User::class, 25)->create();
    }
}
