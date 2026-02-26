<?php

use App\Http\Controllers\ColocationController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [ColocationController::class, 'colocations'])->name('dashboard');

    Route::prefix('colocation')->group(function () {
        Route::get('/{id}', [ColocationController::class, 'show'])->name('colocation.show');
        Route::post('/', [ColocationController::class, 'store'])->name('colocation.store');
        //Member devient quitter
        Route::delete('/{id}', [ColocationController::class, 'leaveColocation'])->name('colocation.leave');
        //owner devient annuler coloc
        Route::delete('/{id}', [ColocationController::class, 'cancelColocation'])->name('colocation.cancel');
    });


    Route::prefix('invitation')->group(function () {
        Route::post('/{id}', [InvitationController::class, 'inviter'])->name('invitation.send');
        Route::get('/{token}', [InvitationController::class, 'show'])->name('invitation.show');
        Route::get('/{token}/accept', [InvitationController::class, 'accept'])->name('invitation.accept');

    });
    
    Route::prefix('expenses')->group(function () {
        Route::post('/{id}', [ColocationController::class, 'store'])->name('expenses.store');
    });
});

require __DIR__ . '/auth.php';
