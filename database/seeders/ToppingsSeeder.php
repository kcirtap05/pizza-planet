<?php

namespace Database\Seeders;

use App\Models\Menu\Topping;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ToppingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Topping::insert([
            ['name' => 'ham'],
            ['name' => 'olives'],
            ['name' => 'mushrooms'],
            ['name' => 'bacon'],
            ['name' => 'mince'],
            ['name' => 'pepperoni'],
            ['name' => 'spicy mince'],
            ['name' => 'onion'],
            ['name' => 'green pepper'],
            ['name' => 'jalapenos'],
        ]);
    }
}
