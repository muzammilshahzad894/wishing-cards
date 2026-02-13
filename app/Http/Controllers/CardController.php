<?php

namespace App\Http\Controllers;

use App\Models\Design;

class CardController extends Controller
{
    /**
     * Home: list active designs for user to choose.
     */
    public function home()
    {
        $designs = Design::active()->orderBy('order')->orderBy('id')->get();
        return view('cards.home', compact('designs'));
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
