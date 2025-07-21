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
                'settings' => json_encode([
                    'heading' => 'About Us',
                    'body' => 'We are passionate about building dynamic websites. Our team is dedicated to providing the best solutions for your needs.',
                    'text_color_class' => '#ffffff',
                    'bg_color_class' => '#111111',
                ]),
            ],
            [
                'name' => 'Image Gallery',
                'view_path' => 'components.image-gallery',
                'settings' => json_encode([
                    'images' => [
                        '/storage/images/gallery1.jpg',
                        '/storage/images/gallery2.jpg',
                        '/storage/images/gallery3.jpg',
                    ],
                    'columns' => 3,
                ]),
            ],
            [
                'name' => 'Image carousel',
                'view_path' => 'components.image-carousel',
                'settings' => json_encode([
                    'images' => [
                        '/storage/images/carousel1.jpg',
                        '/storage/images/carousel2.jpg',
                        '/storage/images/carousel3.jpg',
                    ],
                    'height_class' => 'h-64',
                ]),
            ],
            [
                'name' => 'Contact Card',
                'view_path' => 'components.contact-card',
                'settings' => json_encode([
                    'name' => 'Jane Doe',
                    'email' => 'jane@example.com',
                    'phone' => '+1 555-123-4567',
                    'avatar_url' => '/storage/images/avatar-jane.jpg',
                ]),
            ],
        ]);
    }
}
