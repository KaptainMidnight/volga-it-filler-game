<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Color::create(['name' => 'cyan']);
        Color::create(['name' => 'blue']);
        Color::create(['name' => 'green']);
        Color::create(['name' => 'yellow']);
        Color::create(['name' => 'magenta']);
        Color::create(['name' => 'white']);
        Color::create(['name' => 'red']);
    }
}
