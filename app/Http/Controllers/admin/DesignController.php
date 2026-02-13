<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Design;

class DesignController extends Controller
{
    /**
     * List all designs with active toggle and preview. Optional filter by category.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $categories = config('cards.categories', []);
        $query = Design::orderBy('category')->orderBy('order')->orderBy('id');
        if ($request->filled('category') && isset($categories[$request->category])) {
            $query->where('category', $request->category);
        }
        $designs = $query->get();
        return view('admin.designs.index', compact('designs', 'categories'));
    }

    /**
     * Preview design in full page (for modal iframe or direct view).
     */
    public function preview(Design $design)
    {
        $defaults = $design->getTemplateDefaults();
        return view('admin.designs.preview', [
            'design' => $design,
            'cardImage' => null,
            'cardName' => $defaults['namePlaceholder'],
            'greetingText' => $defaults['greetingText'],
        ]);
    }

    /**
     * Toggle design active/inactive.
     */
    public function toggleActive(Design $design)
    {
        $design->update(['is_active' => !$design->is_active]);
        return redirect()->back()->with('success', $design->is_active ? 'Design is now visible on frontend.' : 'Design is now hidden from frontend.');
    }
}
