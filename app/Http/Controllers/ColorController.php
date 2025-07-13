<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;

class ColorController extends Controller
{

    public function index()
    {
        // Fetch colors from the database
        $colors = Color::all();

        // Return the view with colors
        return view('color.index', compact('colors'));
    }
    public function update(Request $request, \App\Models\Color $color)
    {
        $validated = $request->validate([
            'hex_code' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $color->hex_code = $validated['hex_code'];

        if ($color->save()) {
            return redirect()->back()
                ->with('success', 'Color updated!')
                ->with('color_id', $color->id)
                ->withInput();
        } else {
            return redirect()->back()
                ->withErrors(['hex_code' => 'Failed to update color. Please try again.'])
                ->with('color_id', $color->id)
                ->withInput();
        }
    }
}
