<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\DepenceController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\Admin\AdminStatisticsController;
use App\Http\Controllers\Admin\AdminUserController;
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
        
        // Member leaves
        Route::delete('/{colocation}/leave', [ColocationController::class, 'leaveColocation'])->name('colocation.leave');
        
        // Ownera
        Route::delete('/{colocation}/cancel', [ColocationController::class, 'cancelColocation'])->name('colocation.cancel');
        Route::post('/{id}/transfer-owner/{newOwner}', [ColocationController::class, 'transferOwnership'])->name('colocation.transferOwner');

        Route::delete('/{colocation}/members/{user}/remove', [ColocationController::class, 'removeMember'])->name('colocation.removeMember');
        // Category routes
        Route::post('/{colocationId}/categories', [CategoriesController::class, 'store'])->name('categories.store');
        Route::delete('/{colocationId}/categories/{categoryId}', [CategoriesController::class, 'destroy'])->name('categories.destroy');
        
        // Depence routes
        Route::post('/{colocation}/depences',[DepenceController::class, 'store'])->name('depences.store');
        Route::delete('/depences/{depence}',[DepenceController::class, 'destroy'])->name('depences.destroy');

        // payer depence
        Route::post('/{colocation}/settlements/mark-paid',[SettlementController::class, 'markPaid'])->name('settlements.mark-paid');
    });

    Route::prefix('invitation')->group(function () {
        Route::post('/{id}', [InvitationController::class, 'inviter'])->name('invitation.send');
        Route::get('/{invitation:token}', [InvitationController::class, 'show'])->name('invitation.show');
        Route::post('/{invitation:token}/accept', [InvitationController::class, 'accept'])->name('invitation.accept');
        Route::post('/{invitation:token}/refuse', [InvitationController::class, 'refuse'])->name('invitation.refuse');
    });

    Route::prefix('expenses')->group(function () {
        Route::post('/{id}', [ColocationController::class, 'store'])->name('expenses.store');
    });
});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/statistics', [AdminStatisticsController::class, 'index'])->name('admin.statistics');
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::post('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('admin.ban');
    Route::post('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('admin.unban');
});

require __DIR__ . '/auth.php';
