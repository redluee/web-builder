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
            [
                'page_id' => 1,
                'element_id' => 2, // Text Block element
                'sort_order' => 2,
            ],
            [
                'page_id' => 1,
                'element_id' => 3, // Image Gallery element
                'sort_order' => 3,
            ],
            [
                'page_id' => 1,
                'element_id' => 4, // Image Carousel element
                'sort_order' => 4,
            ],
            [
                'page_id' => 1,
                'element_id' => 5, // contact card element
                'sort_order' => 5,
            ],
            [
                'page_id' => 1,
                'element_id' => 6, // video element
                'sort_order' => 6,
            ],
            [
                'page_id' => 1,
                'element_id' => 7, // line element
                'sort_order' => 7,
            ],
            [
                'page_id' => 1,
                'element_id' => 8, // space element
                'sort_order' => 8,
            ],
        ];

        foreach ($elements as $element) {
            \DB::table('page_elements')->insert($element);
        }
    }
}
