<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardBuilderController extends Controller
{
    /**
     * Show the card builder (category: birthday for now).
     */
    public function index(Request $request)
    {
        $category = $request->get('category', 'birthday');
        $templates = $this->getTemplatesForCategory($category);

        return view('admin.cards.builder', [
            'category' => $category,
            'templates' => $templates,
        ]);
    }

    /**
     * Get available templates for a category.
     */
    protected function getTemplatesForCategory(string $category): array
    {
        $all = [
            'birthday' => [
                [
                    'id' => 'birthday-elegant',
                    'name' => 'Elegant Gold',
                    'description' => 'Classic gold borders with serif typography',
                    'preview_class' => 'template-elegant',
                ],
                [
                    'id' => 'birthday-modern',
                    'name' => 'Modern Gradient',
                    'description' => 'Soft gradient background with modern font',
                    'preview_class' => 'template-modern',
                ],
                [
                    'id' => 'birthday-playful',
                    'name' => 'Playful Party',
                    'description' => 'Colorful and fun with rounded corners',
                    'preview_class' => 'template-playful',
                ],
                [
                    'id' => 'birthday-minimal',
                    'name' => 'Minimal Clean',
                    'description' => 'Simple and clean with lots of whitespace',
                    'preview_class' => 'template-minimal',
                ],
            ],
        ];

        return $all[$category] ?? $all['birthday'];
    }
}
