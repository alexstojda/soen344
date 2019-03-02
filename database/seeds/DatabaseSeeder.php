<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        factory(\App\Doctor::class, 7)->create();
        factory(\App\Nurse::class, 14)->create();
        factory(\App\User::class,  20)->create();
    }
}
