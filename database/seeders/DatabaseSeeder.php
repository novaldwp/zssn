<?php

namespace Database\Seeders;

use App\Models\Survivor;
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
        // \App\Models\User::factory(10)->create();
        Survivor::factory(25)->create();
        $this->call(GenderSeeder::class);
        $this->call(ItemSeeder::class);
    }
}
