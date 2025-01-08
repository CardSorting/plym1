<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProfileController;
use App\Jobs\GenerateCardImages;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Test route for RabbitMQ
Route::get('/test-queue', function () {
    try {
        $task = Task::create([
            'user_id' => auth()->id(),
            'type' => 'generate_images',
            'status' => 'pending',
            'input' => ['prompt' => 'Test card image generation']
        ]);
        
        \Log::info('Dispatching image generation job', [
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'goapi_key' => config('services.goapi.key') ? 'present' : 'missing'
        ]);
        
        GenerateCardImages::dispatch($task);
        
        return 'Job dispatched! Task ID: ' . $task->id;
    } catch (\Exception $e) {
        \Log::error('Failed to dispatch job', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return 'Error: ' . $e->getMessage();
    }
})->middleware(['auth']);

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
    Route::post('cards/generate-images', [CardController::class, 'generateImages'])
        ->middleware(['web', 'auth', \App\Http\Middleware\SetTimeoutMiddleware::class])
        ->name('cards.generate-images');
    Route::get('cards/tasks/{taskId}', [CardController::class, 'checkTaskStatus'])
        ->middleware(['web', 'auth', \App\Http\Middleware\SetTimeoutMiddleware::class])
        ->name('cards.task-status');

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
