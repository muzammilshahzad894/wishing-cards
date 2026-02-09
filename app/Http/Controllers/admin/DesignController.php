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
            'photo_shape' => 'nullable|in:rectangle,circle,ellipse,rounded',
            'photo_left_pct' => 'nullable|numeric|min:0|max:100',
            'photo_top_pct' => 'nullable|numeric|min:0|max:100',
            'photo_right_pct' => 'nullable|numeric|min:0|max:100',
            'photo_bottom_pct' => 'nullable|numeric|min:0|max:100',
            'name_x_pct' => 'nullable|numeric|min:0|max:100',
            'name_y_pct' => 'nullable|numeric|min:0|max:100',
            'name_width_pct' => 'nullable|numeric|min:10|max:100',
            'name_align' => 'nullable|in:left,center,right',
            'name_font_size' => 'nullable|integer|min:10|max:72',
            'is_active' => 'boolean',
        ]);

        $path = $request->file('image')->store('designs', 'public');
        $photo = self::photoEdgesToBox($request);

        Design::create([
            'name' => $request->name,
            'image' => $path,
            'greeting_text' => $request->greeting_text ?? '',
            'name_placeholder' => $request->name_placeholder ?? 'Any Name Here',
            'photo_x_pct' => $photo['x'],
            'photo_y_pct' => $photo['y'],
            'photo_width_pct' => $photo['w'],
            'photo_height_pct' => $photo['h'],
            'photo_rotation' => (float) ($request->photo_rotation ?? 0),
            'photo_shape' => $request->photo_shape ?? 'rectangle',
            'photo_left_pct' => $photo['left'],
            'photo_top_pct' => $photo['top'],
            'photo_right_pct' => $photo['right'],
            'photo_bottom_pct' => $photo['bottom'],
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
            'photo_shape' => 'nullable|in:rectangle,circle,ellipse,rounded',
            'photo_left_pct' => 'nullable|numeric|min:0|max:100',
            'photo_top_pct' => 'nullable|numeric|min:0|max:100',
            'photo_right_pct' => 'nullable|numeric|min:0|max:100',
            'photo_bottom_pct' => 'nullable|numeric|min:0|max:100',
            'name_x_pct' => 'nullable|numeric|min:0|max:100',
            'name_y_pct' => 'nullable|numeric|min:0|max:100',
            'name_width_pct' => 'nullable|numeric|min:10|max:100',
            'name_align' => 'nullable|in:left,center,right',
            'name_font_size' => 'nullable|integer|min:10|max:72',
            'is_active' => 'boolean',
        ]);

        $photo = self::photoEdgesToBox($request, $design);
        $data = [
            'name' => $request->name,
            'greeting_text' => $request->greeting_text ?? '',
            'name_placeholder' => $request->name_placeholder ?? 'Any Name Here',
            'photo_x_pct' => $photo['x'],
            'photo_y_pct' => $photo['y'],
            'photo_width_pct' => $photo['w'],
            'photo_height_pct' => $photo['h'],
            'photo_rotation' => (float) ($request->photo_rotation ?? 0),
            'photo_shape' => $request->photo_shape ?? 'rectangle',
            'photo_left_pct' => $photo['left'],
            'photo_top_pct' => $photo['top'],
            'photo_right_pct' => $photo['right'],
            'photo_bottom_pct' => $photo['bottom'],
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

    /** Build photo box from edges (left, top, right, bottom) or fallback to center+size. */
    protected static function photoEdgesToBox(Request $request, ?Design $design = null): array
    {
        $left = $request->filled('photo_left_pct') ? (float) $request->photo_left_pct : null;
        $top = $request->filled('photo_top_pct') ? (float) $request->photo_top_pct : null;
        $right = $request->filled('photo_right_pct') ? (float) $request->photo_right_pct : null;
        $bottom = $request->filled('photo_bottom_pct') ? (float) $request->photo_bottom_pct : null;

        if ($left !== null && $top !== null && $right !== null && $bottom !== null) {
            $w = max(5, $right - $left);
            $h = max(5, $bottom - $top);
            return [
                'left' => $left,
                'top' => $top,
                'right' => $right,
                'bottom' => $bottom,
                'x' => ($left + $right) / 2,
                'y' => ($top + $bottom) / 2,
                'w' => $w,
                'h' => $h,
            ];
        }

        $x = (float) ($request->photo_x_pct ?? $design?->photo_x_pct ?? 50);
        $y = (float) ($request->photo_y_pct ?? $design?->photo_y_pct ?? 38);
        $w = (float) ($request->photo_width_pct ?? $design?->photo_width_pct ?? 55);
        $h = (float) ($request->photo_height_pct ?? $design?->photo_height_pct ?? 55);
        return [
            'left' => $x - $w / 2,
            'top' => $y - $h / 2,
            'right' => $x + $w / 2,
            'bottom' => $y + $h / 2,
            'x' => $x,
            'y' => $y,
            'w' => $w,
            'h' => $h,
        ];
    }
}
