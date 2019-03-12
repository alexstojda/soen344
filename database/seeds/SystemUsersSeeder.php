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
        factory(\App\Doctor::class, 7)->create();
        factory(\App\Nurse::class, 14)->create();
        factory(\App\User::class,  25)->create();
    }
}
