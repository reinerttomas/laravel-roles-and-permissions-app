<?php

declare(strict_types=1);

use App\Http\Controllers\Admin;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User;
use App\Http\Middleware\IsAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/dashboard', fn () => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function (): void {
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(IsAdminMiddleware::class)
        ->group(function (): void {
            Route::resource('tasks', Admin\TaskController::class);
        });

    Route::prefix('user')
        ->name('user.')
        ->group(function (): void {
            Route::resource('tasks', User\TaskController::class);
        });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
