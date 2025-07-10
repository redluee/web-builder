<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Textblock;
use App\Models\Page;
use Illuminate\Support\Facades\DB;

class TextblockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert textblocks
        $textblocks = [
            [
                'name' => 'welcome.banner',
                'content' => 'Welcome to this example website!',
            ],
            [
                'name' => 'welcome.intro',
                'content' => 'This website is an example for an easy to edit website, which can be customized easily.',
            ],
        ];

        foreach ($textblocks as $index => $data) {
            $textblock = Textblock::create($data);

            // Connect to the welcome page
            $page = Page::firstOrCreate(
                ['slug' => 'welcome'],
                ['title' => 'Welcome']
            );

            DB::table('page_textblocks')->insert([
                'page_id' => $page->id,
                'textblock_id' => $textblock->id,
                'order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
