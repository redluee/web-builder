<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ElementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('element')->insert([
            [
                'name' => 'Banner',
                'view_path' => 'components.banner',
                'settings' => json_encode([
                    'image_url' => '/storage/images/Sao-Paulo-Leaf.jpg',
                    'heading' => 'Welcome to this example website!',
                    'subheading' => 'This website is an example for an easy to edit website, which can be customized easily.',
                    'overlay_color' => 'black',
                    'overlay_opacity' => 50,
                    'height_class' => 'h-[50vh]',
                    'text_color_class' => 'text-white',
                ]),
            ],
            [
                'name' => 'Text Block',
                'view_path' => 'components.text-block',
                'settings' => null,
            ],
            [
                'name' => 'Image Gallery',
                'view_path' => 'components.image-gallery',
                'settings' => null,
            ],
            [
                'name' => 'Image carousel',
                'view_path' => 'components.image-carousel',
                'settings' => null,
            ],
            [
                'name' => 'Contact Card',
                'view_path' => 'components.contact-card',
                'settings' => null,
            ],
        ]);
    }
}
