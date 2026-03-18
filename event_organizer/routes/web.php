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
        if (Auth::user()->role !== 'owner') {
            return redirect('/');
        }
        return view('owner.dashboard');
    })->name('owner.dashboard');

    Route::get('/users', [OwnerController::class, 'users'])->name('owner.users');
    Route::post('/users', [OwnerController::class, 'storeUser'])->name('owner.users.store');
    Route::put('/users/{user}', [OwnerController::class, 'updateUser'])->name('owner.users.update');

    Route::get('/vendors', [OwnerController::class, 'vendors'])->name('owner.vendors');
    Route::post('/vendors', [OwnerController::class, 'storeVendor'])->name('owner.vendors.store');
    Route::put('/vendors/{vendor}', [OwnerController::class, 'updateVendor'])->name('owner.vendors.update');
    Route::get('/events', [EventController::class, 'index'])->name('owner.events.index');
    
    Route::get('/vendors/{vendor}/manage', [OwnerController::class, 'manageVendor'])->name('owner.vendors.manage');

    Route::post('/vendors/{vendor}/contacts', [OwnerController::class, 'storeVendorContact'])->name('owner.vendors.contacts.store');
    Route::put('/vendors/contacts/{contact}', [OwnerController::class, 'updateVendorContact'])->name('owner.vendors.contacts.update');
    Route::delete('/vendors/contacts/{contact}', [OwnerController::class, 'destroyVendorContact'])->name('owner.vendors.contacts.destroy');

    Route::post('/vendors/{vendor}/packages', [OwnerController::class, 'storeVendorPackage'])->name('owner.vendors.packages.store');
    Route::put('/vendors/packages/{package}', [OwnerController::class, 'updateVendorPackage'])->name('owner.vendors.packages.update');
    Route::delete('/vendors/packages/{package}', [OwnerController::class, 'destroyVendorPackage'])->name('owner.vendors.packages.destroy');

    Route::post('/vendors/{vendor}/portfolios', [OwnerController::class, 'storeVendorPortfolio'])->name('owner.vendors.portfolios.store');
    Route::delete('/vendors/portfolios/{portfolio}', [OwnerController::class, 'destroyVendorPortfolio'])->name('owner.vendors.portfolios.destroy');
    });

Route::middleware(['auth'])->prefix('pl')->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role !== 'pl') {
            return redirect('/');
        }
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
