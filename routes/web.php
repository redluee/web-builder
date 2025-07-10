<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
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
});

require __DIR__.'/auth.php';
