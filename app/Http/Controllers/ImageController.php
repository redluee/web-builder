<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;


class ImageController extends Controller
{
    public function index()
    {
        // Fetch images from the database
        $images = Image::all();

        // Return the view with images
        return view('image.index', compact('images'));
    }

    public function show(Image $image)
    {
        // Return the view to edit the image
        return view('image.edit', compact('image'));
    }

    public function update(Request $request, Image $image)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Update image attributes
            $image->name = $validated['name'];
            $image->alt_text = $validated['alt_text'];

            // Handle file upload if provided
            if ($request->hasFile('image_file')) {
                $path = $request->file('image_file')->store('images', 'public');
                $image->url = '/storage/' . $path;
            }

            $image->save();

            return redirect()->route('images.index')
                ->with('success', 'Image updated successfully!');
                
        } catch (\Exception $e) {
            \Log::error('Image update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update image: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'image_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $image = new Image();
            $image->name = $validated['name'];
            $image->alt_text = $validated['alt_text'] ?? null;

            // Handle file upload
            if ($request->hasFile('image_file')) {
                $path = $request->file('image_file')->store('images', 'public');
                $image->url = '/storage/' . $path;
            }

            $image->save();

            return redirect()->route('images.index')
                ->with('success', 'Image created successfully!');
        } catch (\Exception $e) {
            \Log::error('Image creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create image: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
