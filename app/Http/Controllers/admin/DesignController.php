<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    public function index()
    {
        $designs = Design::orderBy('is_active', 'desc')->orderBy('created_at', 'desc')->get();
        return view('admin.designs.index', compact('designs'));
    }

    public function create()
    {
        return view('admin.designs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'greeting_text' => 'nullable|string|max:255',
            'name_placeholder' => 'nullable|string|max:255',
            'photo_x_pct' => 'nullable|numeric|min:0|max:100',
            'photo_y_pct' => 'nullable|numeric|min:0|max:100',
            'photo_width_pct' => 'nullable|numeric|min:5|max:100',
            'photo_height_pct' => 'nullable|numeric|min:5|max:100',
            'photo_rotation' => 'nullable|numeric|min:-360|max:360',
            'name_x_pct' => 'nullable|numeric|min:0|max:100',
            'name_y_pct' => 'nullable|numeric|min:0|max:100',
            'name_width_pct' => 'nullable|numeric|min:10|max:100',
            'name_align' => 'nullable|in:left,center,right',
            'name_font_size' => 'nullable|integer|min:10|max:72',
            'is_active' => 'boolean',
        ]);

        $path = $request->file('image')->store('designs', 'public');

        Design::create([
            'name' => $request->name,
            'image' => $path,
            'greeting_text' => $request->greeting_text ?? '',
            'name_placeholder' => $request->name_placeholder ?? 'Any Name Here',
            'photo_x_pct' => (float) ($request->photo_x_pct ?? 50),
            'photo_y_pct' => (float) ($request->photo_y_pct ?? 38),
            'photo_width_pct' => (float) ($request->photo_width_pct ?? 55),
            'photo_height_pct' => (float) ($request->photo_height_pct ?? 55),
            'photo_rotation' => (float) ($request->photo_rotation ?? 0),
            'name_x_pct' => (float) ($request->name_x_pct ?? 50),
            'name_y_pct' => (float) ($request->name_y_pct ?? 88),
            'name_width_pct' => (float) ($request->name_width_pct ?? 80),
            'name_align' => $request->name_align ?? 'center',
            'name_font_size' => (int) ($request->name_font_size ?? 18),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.designs.index')->with('success', 'Design added successfully.');
    }

    public function edit(Design $design)
    {
        return view('admin.designs.edit', compact('design'));
    }

    public function update(Request $request, Design $design)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'greeting_text' => 'nullable|string|max:255',
            'name_placeholder' => 'nullable|string|max:255',
            'photo_x_pct' => 'nullable|numeric|min:0|max:100',
            'photo_y_pct' => 'nullable|numeric|min:0|max:100',
            'photo_width_pct' => 'nullable|numeric|min:5|max:100',
            'photo_height_pct' => 'nullable|numeric|min:5|max:100',
            'photo_rotation' => 'nullable|numeric|min:-360|max:360',
            'name_x_pct' => 'nullable|numeric|min:0|max:100',
            'name_y_pct' => 'nullable|numeric|min:0|max:100',
            'name_width_pct' => 'nullable|numeric|min:10|max:100',
            'name_align' => 'nullable|in:left,center,right',
            'name_font_size' => 'nullable|integer|min:10|max:72',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'greeting_text' => $request->greeting_text ?? '',
            'name_placeholder' => $request->name_placeholder ?? 'Any Name Here',
            'photo_x_pct' => (float) ($request->photo_x_pct ?? 50),
            'photo_y_pct' => (float) ($request->photo_y_pct ?? 38),
            'photo_width_pct' => (float) ($request->photo_width_pct ?? 55),
            'photo_height_pct' => (float) ($request->photo_height_pct ?? 55),
            'photo_rotation' => (float) ($request->photo_rotation ?? 0),
            'name_x_pct' => (float) ($request->name_x_pct ?? 50),
            'name_y_pct' => (float) ($request->name_y_pct ?? 88),
            'name_width_pct' => (float) ($request->name_width_pct ?? 80),
            'name_align' => $request->name_align ?? 'center',
            'name_font_size' => (int) ($request->name_font_size ?? 18),
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($design->image);
            $data['image'] = $request->file('image')->store('designs', 'public');
        }

        $design->update($data);

        return redirect()->route('admin.designs.index')->with('success', 'Design updated successfully.');
    }

    public function destroy(Design $design)
    {
        Storage::disk('public')->delete($design->image);
        $design->delete();
        return redirect()->route('admin.designs.index')->with('success', 'Design deleted.');
    }

    public function toggleActive(Design $design)
    {
        $design->update(['is_active' => !$design->is_active]);
        return redirect()->back()->with('success', $design->is_active ? 'Design is now active.' : 'Design is now inactive.');
    }
}
