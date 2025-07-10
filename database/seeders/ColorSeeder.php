<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Color::create([
            'variable_name' => 'primary_color',
            'hex_code' => '#000000',
        ]);

        Color::create([
            'variable_name' => 'secondary_color',
            'hex_code' => '#ffffff',
        ]);

        Color::create([
            'variable_name' => 'background_color',
            'hex_code' => '#18181a',
        ]);
    }
}
