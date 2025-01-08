<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cards routes
    Route::resource('cards', CardController::class);
    Route::post('cards/{card}/publish', [CardController::class, 'publish'])->name('cards.publish');
    Route::post('cards/{card}/unpublish', [CardController::class, 'unpublish'])->name('cards.unpublish');
    Route::post('cards/generate-images', [CardController::class, 'generateImages'])->name('cards.generate-images');

    // Packs routes
    Route::resource('packs', PackController::class);
    Route::post('packs/{pack}/toggle-availability', [PackController::class, 'toggleAvailability'])
        ->name('packs.toggle-availability');
    Route::post('packs/{pack}/purchase', [PackController::class, 'purchase'])->name('packs.purchase');

    // Stores routes
    Route::resource('stores', StoreController::class);
    Route::post('stores/{store}/toggle-status', [StoreController::class, 'toggleStatus'])
        ->name('stores.toggle-status');
    Route::post('stores/{store}/withdraw', [StoreController::class, 'withdrawBalance'])
        ->name('stores.withdraw');
});

// Public marketplace route
Route::get('/marketplace', [StoreController::class, 'marketplace'])->name('marketplace');

require __DIR__.'/auth.php';
