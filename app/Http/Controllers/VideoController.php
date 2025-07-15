<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::all();
        return view('video.index', compact('videos'));
    }

    public function create(Video $video)
    {
        // Show the edit form for a video (used for editing)
        return view('video.edit', compact('video'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'youtube_url' => 'required|url|max:255',
            'video_name' => 'required|string|max:255',
            'video_description' => 'nullable|string',
        ]);

        try {
            Video::create($validated);
            return redirect()->route('videos.index')
                ->with('success', 'Video created successfully!');
        } catch (\Exception $e) {
            \Log::error('Video creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create video: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'youtube_url' => 'required|url|max:255',
            'video_name' => 'required|string|max:255',
            'video_description' => 'nullable|string',
        ]);

        try {
            $video->update($validated);
            return redirect()->route('videos.index')
                ->with('success', 'Video updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Video update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update video: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Video $video)
    {
        try {
            $video->delete();
            return redirect()->route('videos.index')
                ->with('success', 'Video deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Video deletion failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete video: ' . $e->getMessage()]);
        }
    }
}
