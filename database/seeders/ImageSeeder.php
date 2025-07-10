<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Image;
use App\Models\Page;
use Illuminate\Support\Facades\DB;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the image
        $image = Image::create([
            'name' => 'banner',
            'url' => '/storage/images/Sao-Paulo-Leaf.jpg',
            'alt_text' => 'Banner Image',
        ]);

        // Find the welcome page (assuming slug is 'welcome' or 'home')
        $page = Page::where('slug', 'welcome')->first();

        // If the welcome page does not exist, create it
        if (!$page) {
            $page = Page::create([
                'title' => 'Welcome',
                'slug' => 'welcome',
            ]);
        }

        // Attach the image to the page via the pivot table
        DB::table('page_images')->insert([
            'page_id' => $page->id,
            'image_id' => $image->id,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
