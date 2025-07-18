<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageElementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert page elements
        $elements = [
            [
                'page_id' => 1, // Assuming the welcome page has ID 1
                'element_id' => 1, // Banner element
                'sort_order' => 1,
            ],
        ];

        foreach ($elements as $element) {
            \DB::table('page_elements')->insert($element);
        }
    }
}
