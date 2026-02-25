<?php

use App\Http\Controllers\ColocationController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [ColocationController::class, 'index'])->name('dashboard');

    Route::middleware(['banned'])->group(function () {
        Route::resource('colocations', ColocationController::class);

        Route::prefix('invitations')->group(function () {
            Route::get('/', [InvitationController::class, 'index'])->name('invitations.index');
            Route::post('/', [InvitationController::class, 'store'])->name('invitations.store');
            Route::post('/accept/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');
            Route::post('/reject/{token}', [InvitationController::class, 'reject'])->name('invitations.reject');
        });
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
