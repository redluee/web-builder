<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Element;
use App\Models\PageElement;

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
            'slug' => 'required|string',
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

    public function editContent(Page $page)
    {
        $elements = $page->elements()->orderBy('pivot_sort_order')->get();
        $availableElements = Element::all();
        return view('page.edit-content', compact('page', 'elements', 'availableElements'));
    }

    public function addElement(Request $request, Page $page)
    {
        $validated = $request->validate([
            'element_id' => 'required|exists:element,id',
            'settings' => 'nullable|array',
        ]);
        $sortOrder = $page->elements()->count() + 1;
        $page->elements()->attach($validated['element_id'], [
            'sort_order' => $sortOrder,
            'settings' => json_encode($validated['settings'] ?? []),
        ]);
        return redirect()->route('pages.editContent', $page->id)->with('success', 'Element added!');
    }

    public function removeElement(Page $page, $elementId)
    {
        $page->elements()->detach($elementId);
        return redirect()->route('pages.editContent', $page->id)->with('success', 'Element removed!');
    }

    public function updateElementOrder(Request $request, Page $page)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:element,id',
        ]);
        foreach ($validated['order'] as $index => $elementId) {
            $page->elements()->updateExistingPivot($elementId, ['sort_order' => $index + 1]);
        }
        return response()->json(['success' => true]);
    }
}