<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::create([
            'name' => 'Water',
            'point' => 4
        ]);
        Item::create([
            'name' => 'Food',
            'point' => 3
        ]);
        Item::create([
            'name' => 'Medication',
            'point' => 2
        ]);
        Item::create([
            'name' => 'Ammunition',
            'point' => 1
        ]);
    }
}
