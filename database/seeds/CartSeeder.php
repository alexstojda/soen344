<?php

use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Cart::class, 10)->create();
    }
}
