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
                Route::get('/create','create')->name('create');
                Route::get('/{article}/edit','edit')->name('edit');
                Route::post('/store','store')->name('store');
                Route::post('/{article}/update','update')->name('update');
                Route::delete('/{article}/archive','archive')->name('archive');
                Route::patch('/{article}/restore','restore')->name('restore')->withTrashed();
        });

    });
});
