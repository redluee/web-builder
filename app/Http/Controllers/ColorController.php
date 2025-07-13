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
            if ($request->wantsJson()) {
                // Render the updated tile as HTML
                $tile = view('components.color-edit-tile', [
                    'color' => $color,
                    'success' => 'Color updated!',
                ])->render();
                return response()->json([
                    'success' => 'Color updated!',
                    'color_id' => $color->id,
                    'tile' => $tile,
                ]);
            }
            return redirect()->back()
                ->with('success', 'Color updated!')
                ->with('color_id', $color->id)
                ->withInput();
        } else {
            if ($request->wantsJson()) {
                $tile = view('components.color-edit-tile', ['color' => $color])
                    ->withErrors(['hex_code' => 'Failed to update color. Please try again.'])
                    ->render();
                return response()->json([
                    'errors' => ['hex_code' => ['Failed to update color. Please try again.']],
                    'color_id' => $color->id,
                    'tile' => $tile,
                ], 422);
            }
            return redirect()->back()
                ->withErrors(['hex_code' => 'Failed to update color. Please try again.'])
                ->with('color_id', $color->id)
                ->withInput();
        }
    }
}
