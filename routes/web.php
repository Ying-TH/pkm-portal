<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function() {
    return redirect()->route('portal.overview');
});

Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/', function() {
        return Inertia::render('Portal/Overview');
     })->name('overview');

    Route::get('dashboard', function() {
        return Inertia::render('Portal/Dashboard');
     })->name('dashboard');

    Route::get('/news', function () {
        return Inertia::render('Portal/News/Index');
    })->name('news');

    Route::get('/news/{id}', function (int $id) {
        return Inertia::render('Portal/News/Show', ['articleId' => $id]);
    })->name('news.show');

    Route::get('/{module}', function (string $module) {
        return Inertia::render('Shared/EmptyState', ['module' => $module]);
    })->name('module');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';