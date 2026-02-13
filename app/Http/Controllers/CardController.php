<?php

namespace App\Http\Controllers;

use App\Models\Design;

class CardController extends Controller
{
    /**
     * Home at /: show all active designs, sidebar with categories. No redirect.
     */
    public function home()
    {
        $categories = config('cards.categories', ['birthday-cards' => 'Birthday Cards']);
        $designs = Design::active()->orderBy('category')->orderBy('order')->orderBy('id')->get();
        return view('cards.home', [
            'designs' => $designs,
            'categories' => $categories,
            'currentCategory' => null,
        ]);
    }

    /**
     * Category page: show active designs for this category (when user clicks a category).
     */
    public function category(string $category)
    {
        $categories = config('cards.categories', ['birthday-cards' => 'Birthday Cards']);
        if (!isset($categories[$category])) {
            abort(404, 'Category not found.');
        }
        $designs = Design::active()->inCategory($category)->orderBy('order')->orderBy('id')->get();
        return view('cards.home', [
            'designs' => $designs,
            'categories' => $categories,
            'currentCategory' => $category,
        ]);
    }

    /**
     * Create card: selected design, user adds photo and name.
     */
    public function create(Design $design)
    {
        if (!$design->is_active) {
            abort(404, 'This design is not available.');
        }
        return view('cards.create', compact('design'));
    }
}
