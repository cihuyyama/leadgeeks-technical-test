<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [TicketController::class, 'index'])->name('dashboard');

    Route::resource('tickets', TicketController::class)->except(['show', 'index']);
});

require __DIR__.'/settings.php';
