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
                    'heading' => 'Lorum ipsum',
                    'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis nec turpis at libero vestibulum viverra non at mauris. Maecenas aliquet, nulla in sagittis semper, felis ligula dapibus justo, vitae consectetur justo arcu et odio. Proin dapibus nisl quam, id blandit massa consectetur sed. Sed venenatis orci eget feugiat consectetur. Proin aliquet ultricies elit, sed scelerisque arcu finibus finibus. Suspendisse potenti. Sed in ligula nec enim facilisis tincidunt. Integer ac dolor non quam aliquet facilisis. Donec vel felis at erat commodo eleifend.',
                    'text_color_class' => '#ffffff',
                    'bg_color_class' => '#111111',
                ]),
            ],
            [
                'name' => 'Image Gallery',
                'view_path' => 'components.image-gallery',
                'settings' => json_encode([
                    'images' => [
                        '/storage/images/Blueprint.jpg',
                        '/storage/images/color-banner.jpg',
                        '/storage/images/playbutton.jpeg',
                    ],
                    'columns' => 3,
                ]),
            ],
            [
                'name' => 'Image carousel',
                'view_path' => 'components.image-carousel',
                'settings' => json_encode([
                    'images' => [
                        '/storage/images/Blueprint.jpg',
                        '/storage/images/color-banner.jpg',
                        '/storage/images/playbutton.jpeg',
                    ],
                    'height_class' => 'h-64',
                ]),
            ],
            [
                'name' => 'Contact Card',
                'view_path' => 'components.contact-card',
                'settings' => json_encode([
                    'name' => 'Steven Heijn',
                    'email' => 'info@stevenheijn.nl',
                    'phone' => '+31 6 12345678',
                    'avatar_url' => '/storage/images/Profile.jpg',
                    'bg_color_class' => '#18181a',
                ]),
            ],
            [
                'name' => 'Video',
                'view_path' => 'components.video',
                'settings' => json_encode([
                    'video_url' => 'https://youtu.be/YHS8AYSwW34',
                    'height_class' => 'h-64',
                    'autoplay' => false,
                ]),
            ],
            [
                'name' => 'line',
                'view_path' => 'components.line',
                'settings' => json_encode([
                    'height' => '2px',
                    'bg_color_class' => '#2e3132',
                    'width' => '80%',
                    'style-type' => 'solid',
                ]),
            ],
        ]);
    }
}
