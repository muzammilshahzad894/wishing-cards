<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Support\Facades\File;

class DesignController extends Controller
{
    /**
     * Sync designs from template folder structure.
     * Scans resources/views/cards/templates/{category}/{template}/ and creates Design rows.
     */
    public function sync()
    {
        $basePath = resource_path('views/cards/templates');
        if (!File::isDirectory($basePath)) {
            return redirect()->route('admin.designs.index')->with('error', 'Templates directory not found.');
        }

        $synced = 0;
        $categories = config('cards.categories', []);
        $categoryDirs = File::directories($basePath);

        foreach ($categoryDirs as $categoryPath) {
            $categoryFolder = basename($categoryPath);
            $categoryKey = $this->resolveCategoryKey($categoryFolder, $categories);

            foreach (File::directories($categoryPath) as $templatePath) {
                $templateFolder = basename($templatePath);
                $templateBlade = $templatePath . DIRECTORY_SEPARATOR . 'template.blade.php';
                if (!File::exists($templateBlade)) {
                    continue;
                }

                $templateKey = $categoryFolder . '-' . $templateFolder;
                $name = ucfirst($categoryFolder) . ' ' . ucfirst($templateFolder);

                Design::firstOrCreate(
                    [
                        'category' => $categoryKey,
                        'template_key' => $templateKey,
                    ],
                    [
                        'name' => $name,
                        'is_active' => true,
                        'order' => 0,
                    ]
                );
                $synced++;
            }
        }

        return redirect()->route('admin.designs.index')->with('success', $synced . ' template(s) synced successfully!');
    }

    private function resolveCategoryKey(string $categoryFolder, array $categories): string
    {
        foreach (array_keys($categories) as $key) {
            if (str_starts_with($key, $categoryFolder)) {
                return $key;
            }
        }
        return $categoryFolder . '-cards';
    }

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
