<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/vendor', function () {
    return view('vendor');
})->name('vendor');

Route::middleware(['auth'])->prefix('owner')->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role !== 'owner') abort(403);
        return view('admin.dashboard_owner');
    })->name('owner.dashboard');

    Route::get('/users', function () {
        if (Auth::user()->role !== 'owner') abort(403);
        return view('admin.users');
    })->name('admin.users');

    Route::get('/vendors', function () {
        if (Auth::user()->role !== 'owner') abort(403);
        return view('admin.vendors');
    })->name('admin.vendors');
});

Route::middleware(['auth'])->prefix('pl')->group(function () {
    Route::get('/dashboard', function () {
        if (!in_array(Auth::user()->role, ['owner', 'pl'])) abort(403);
        return view('admin.dashboard_pl');
    })->name('pl.dashboard');

    Route::get('/events', function () {
        if (!in_array(Auth::user()->role, ['owner', 'pl'])) abort(403);
        return view('admin.events');
    })->name('pl.events');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
