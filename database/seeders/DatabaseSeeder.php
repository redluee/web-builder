<?php

namespace Database\Seeders;

use App\Models\PageElement;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password'=> bcrypt('p'),
        ]);

        $this->call([
            PageSeeder::class,
            ElementSeeder::class,
            ColorSeeder::class,
            ImageSeeder::class,
            TextblockSeeder::class,
            SettingSeeder::class,
            PageElementSeeder::class,
        ]);
    }
}
