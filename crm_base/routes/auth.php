<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    /*Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.create');*/
	
    Route::get('login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store'])->name('login.auth');

    Route::get('forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
	Route::get('reset-password-thankyou', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'thankYou'])->name('password.request.thankyou');
    Route::get('reset-password/{token}', [App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
	Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
	
    Route::get('/profilo', [App\Http\Controllers\ProfiloController::class, 'edit'])->name('profilo.edit');
    Route::put('/profilo', [App\Http\Controllers\ProfiloController::class, 'update'])->name('profilo.update');
	
    /*Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');*/

    //Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    //Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    //Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
