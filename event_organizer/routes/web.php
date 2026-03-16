<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OwnerController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/vendor', function () {
    return view('vendor');
})->name('vendor');

Route::middleware(['auth'])->prefix('owner')->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role !== 'owner') abort(403);
        return view('owner.dashboard');
    })->name('owner.dashboard');

    Route::get('/users', [OwnerController::class, 'users'])->name('owner.users');
    Route::get('/vendors', [OwnerController::class, 'vendors'])->name('owner.vendors');
    Route::get('/events', [EventController::class, 'index'])->name('owner.events.index');
});

Route::middleware(['auth'])->prefix('pl')->group(function () {
    Route::get('/dashboard', function () {
        if (!in_array(Auth::user()->role, ['owner', 'pl'])) abort(403);
        return view('pl.dashboard');
    })->name('pl.dashboard');

    Route::get('/events', [EventController::class, 'index'])->name('pl.events.index');
});

Route::middleware(['auth'])->prefix('client')->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('client.events.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
