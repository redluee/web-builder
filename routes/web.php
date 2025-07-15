<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

// Use the WelcomeController for the home page
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Public pages
Route::view('/products', 'products.index')->name('products');
Route::view('/contact', 'contact.index')->name('contact');
Route::view('/about', 'about.index')->name('about');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //dashboard route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    //color management
    Route::get('/colors', [ColorController::class, 'index'])->name('colors.index');
    Route::put('/colors/{color}', [ColorController::class, 'update'])->name('colors.update');

    //image management
    Route::get('/images', [ImageController::class, 'index'])->name('images.index');
    Route::get('/images/create', function() {
        return view('image.create');
    })->name('images.create');
    Route::get('/images/{image}', [ImageController::class,'show'])->name('images.edit');
    Route::put('/images/{image}', [ImageController::class, 'update'])->name('images.update');
    Route::delete('/images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');
    Route::post('/images', [ImageController::class, 'store'])->name('images.store');

    //page management
    Route::get('/pages', [PageController::class, 'index'])->name('pages.index');

    //video management
    Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/{video}', [VideoController::class, 'create'])->name('videos.edit');
    Route::put('/videos/{video}', [VideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');
    Route::get('/videos/create', function() {
        return view('video.create');
    })->name('videos.create');
    Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');

    //navigation management
    Route::get('/navigation', [NavigationController::class, 'index'])->name('navigation.index');
    Route::put('/navigation/update', [NavigationController::class,'update'])->name('navigation.update');


});

require __DIR__.'/auth.php';
