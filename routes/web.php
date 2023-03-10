<?php

use App\Http\Controllers\AdminCourseController;
use App\Http\Controllers\AdminSourceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::prefix('admin')->middleware(['auth', 'adminarea' , 'verified'])->group(function(){

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('courses', AdminCourseController::class)
        ->except(['show'])
    ;

    Route::resource('sources', AdminSourceController::class)
        ->except(['show'])
    ;

    Route::get('sources/search/{search}', [ AdminSourceController::class, 'search' ])
        ->name('sources.search')
    ;
});

require __DIR__.'/auth.php';
