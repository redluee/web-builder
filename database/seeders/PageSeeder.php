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
        $pages = [
            ['title' => 'Welcome', 'slug' => 'welcome'],
            ['title' => 'About', 'slug' => 'about'],
            ['title' => 'Contact', 'slug' => 'contact'],
            ['title' => 'Products', 'slug' => 'products'],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}
