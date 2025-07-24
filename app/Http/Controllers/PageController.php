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
        // Get elements with their pivot data (page_elements table data)
        $elements = $page->elements()
            ->withPivot('id', 'sort_order', 'settings')
            ->orderBy('page_elements.sort_order')
            ->get();
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

    public function removeElement(Page $page, $pageElementId)
    {
        // Delete the specific page_element record by its ID
        \DB::table('page_elements')
            ->where('id', $pageElementId)
            ->where('page_id', $page->id) // Additional security check
            ->delete();
            
        return redirect()->route('pages.editContent', $page->id)->with('success', 'Element removed!');
    }

    public function updateElementOrder(Request $request, Page $page)
    {
        $order = $request->input('order');
        
        \Log::info('Received order update request', [
            'page_id' => $page->id,
            'order' => $order
        ]);

        // Validate the order array to ensure no duplicate sort_order values
        $sortOrders = array_column($order, 'sort_order');
        if (count($sortOrders) !== count(array_unique($sortOrders))) {
            \Log::warning('Duplicate sort_order values detected', ['sort_orders' => $sortOrders]);
            return response()->json(['success' => false, 'message' => 'Duplicate sort_order values detected.'], 400);
        }

        try {
            \DB::transaction(function () use ($order, $page) {
                // Step 1: Assign temporary negative sort_order values to avoid conflicts
                foreach ($order as $index => $item) {
                    \DB::table('page_elements')
                        ->where('id', $item['id']) // Use page_elements.id instead of element_id
                        ->where('page_id', $page->id)
                        ->update(['sort_order' => -($index + 1)]);
                }

                // Step 2: Update to the final sort_order values
                foreach ($order as $item) {
                    \DB::table('page_elements')
                        ->where('id', $item['id']) // Use page_elements.id instead of element_id
                        ->where('page_id', $page->id)
                        ->update(['sort_order' => $item['sort_order']]);
                }
            });

            \Log::info('Element order updated successfully for page ' . $page->id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Failed to update element order: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update element order.'], 500);
        }
    }
}