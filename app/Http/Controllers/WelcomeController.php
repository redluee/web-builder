<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class WelcomeController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'welcome')->first();
        $images = $page ? $page->images : collect();
        $textblocks = $page ? $page->textblocks : collect();

        return view('welcome', compact('images', 'textblocks'));
    }
}
