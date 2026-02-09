<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Design;

class DashboardController extends Controller
{
    public function index()
    {
        $designsCount = Design::count();
        $activeCount = Design::where('is_active', true)->count();
        return view('admin.dashboard', compact('designsCount', 'activeCount'));
    }
}
