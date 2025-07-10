<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::create([
            'title' => 'About',
            'slug' => 'about',
        ]);

        Page::create([
            'title' => 'Contact',
            'slug' => 'contact',
        ]);

        Page::create([
            'title' => 'Products',
            'slug' => 'products',
        ]);
    }
}
