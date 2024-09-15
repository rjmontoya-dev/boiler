<?php

use App\Http\Controllers\Admin\Article\ArticleController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    # Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    Route::prefix('/admin')
    ->name('admin.')
    ->group(function () {

        # Article
        Route::prefix('/article')
            ->name('article.')
            ->controller(ArticleController::class)
            ->group( function () {

                Route::get('/','index')->name('index');
        });

    });
});
