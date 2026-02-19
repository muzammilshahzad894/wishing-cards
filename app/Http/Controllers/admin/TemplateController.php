<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    /**
     * List all card templates.
     */
    public function index()
    {
        $templates = Template::withCount('zones')->orderBy('created_at', 'desc')->get();
        return view('admin.templates.index', compact('templates'));
    }

    /**
     * Show form to create a new template.
     */
    public function create()
    {
        return view('admin.templates.create');
    }

    /**
     * Store a new template (title + background image).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'background_image' => ['required', 'image', 'max:10240'], // 10MB
        ]);

        $path = $request->file('background_image')->store('templates', 'public');
        $template = Template::create([
            'title' => $validated['title'],
            'background_image' => $path,
        ]);

        return redirect()->route('admin.templates.editor', $template)
            ->with('success', 'Template created. Now define the editable zones.');
    }

    /**
     * Admin template editor: load background in Fabric.js, draw zones.
     * GET /admin/templates/{id}/editor
     */
    public function editor(Template $template)
    {
        $template->load('zones');
        return view('admin.templates.editor', compact('template'));
    }
}
