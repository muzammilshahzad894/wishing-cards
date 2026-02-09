<?php

namespace App\Http\Controllers;

use App\Models\Design;

class CardController extends Controller
{
    /**
     * Home: show only active designs for users to choose.
     */
    public function home()
    {
        $designs = Design::active()->orderBy('created_at', 'desc')->get();
        return view('cards.home', compact('designs'));
    }

    /**
     * Create card: one design – upload photo, set name, save.
     */
    public function create(Design $design)
    {
        if (!$design->is_active) {
            abort(404, 'This design is not available.');
        }
        return view('cards.create', compact('design'));
    }
}
