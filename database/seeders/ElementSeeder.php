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
                'view_path' => 'components/banner',
                'label' => 'Image',
                'settings' => json_encode([
                    'overlay_color' => 'black',
                    'overlay_opacity' => 50,
                    ''=> '',
                ]),
            ],
            [
                'name' => 'Text Block',
                'view_path' => 'components/text-block',
                'label' => 'Text',
            ],
            [
                'name' => 'Image Gallery',
                'view_path' => 'components/image-gallery',
                'label' => 'Image',
            ],
            [
                'name' => 'Image carousel',
                'view_path' => 'components/image-carousel',
                'label' => 'Image',
            ],
            [
                'name' => 'Contact Card',
                'view_path' => 'components/contact-card',
                'label' => 'Text info',
            ],
        ]);
    }
}
