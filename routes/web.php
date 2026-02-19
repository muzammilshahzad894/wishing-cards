<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\DesignController;
use App\Http\Controllers\admin\TemplateController;
use App\Http\Controllers\admin\TemplateZoneController;
use Illuminate\Support\Facades\Artisan;

// Public – no login
Route::get('/', [CardController::class, 'home'])->name('cards.home');
Route::get('/category/{category}', [CardController::class, 'category'])->name('cards.category');
Route::get('/card/{design}', [CardController::class, 'create'])->name('cards.create');
Route::get('/cards/{template}', [CardController::class, 'showTemplate'])->name('cards.template.show');

// Guest – login page
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return redirect()->route('admin.login');
    })->name('login');

    Route::match(['get', 'post'], '/admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
});

// Admin – login required
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::get('/designs', [DesignController::class, 'index'])->name('designs.index');
    Route::post('/designs/sync', [DesignController::class, 'sync'])->name('designs.sync');
    Route::get('/designs/{design}/preview', [DesignController::class, 'preview'])->name('designs.preview');
    Route::post('/designs/{design}/toggle-active', [DesignController::class, 'toggleActive'])->name('designs.toggle-active');

    Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
    Route::get('/templates/create', [TemplateController::class, 'create'])->name('templates.create');
    Route::post('/templates', [TemplateController::class, 'store'])->name('templates.store');
    Route::get('/templates/{template}/editor', [TemplateController::class, 'editor'])->name('templates.editor');
    Route::post('/templates/{template}/zones', [TemplateZoneController::class, 'store'])->name('templates.zones.store');
});

Route::get('/migrate-fresh', function () {
    Artisan::call('migrate:fresh');
    Artisan::call('db:seed');
    dd('done');
});
