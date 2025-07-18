<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy("created_at", "desc")->paginate(10);

        return view('page.index', compact('pages'));
    }

    public function create()
    {
        return view('page.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        try {
            Page::create($validated);
            return redirect()->route('pages.index')
                ->with('success', 'Page created successfully!');
        } catch (\Exception $e) {
            \Log::error('Page creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create page: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(Page $page)
    {
        return view('page.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        try {
            $page->update($validated);
            return redirect()->route('pages.index')
                ->with('success', 'Page updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Page update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update page: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Page $page)
    {
        try {
            $page->delete();
            return redirect()->route('pages.index')
                ->with('success', 'Page deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Page deletion failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete page: ' . $e->getMessage()]);
        }
    }

    
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)
            ->firstOrFail();

        return view('page', compact('page'));
    }
}