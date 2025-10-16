<?php

namespace Database\Seeders;

use App\Models\Menu\Pizza;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PizzaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pizza::insert([
            ['name' => 'Margherita', 'price' => 10],
            ['name' => 'Romana', 'price' => 13],
            ['name' => 'Americana', 'price' => 13],
            ['name' => 'Mexicana', 'price' => 15],
        ]);
    }
}
