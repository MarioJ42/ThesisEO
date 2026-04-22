<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\Client\PaymentController;
use App\Models\EoPortfolio;
use App\Models\WeddingPackage;
use App\Models\VendorCategory;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'owner') {
            return redirect()->route('owner.dashboard');
        } elseif (Auth::user()->role === 'pl') {
            return redirect()->route('pl.dashboard');
        }
    }

    $portfolios = EoPortfolio::latest()->get();
    $packages = WeddingPackage::where('is_active', true)->orderBy('base_price', 'asc')->get();

    return response()
        ->view('home', compact('portfolios', 'packages'))
        ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
})->name('home');

Route::get('/vendor', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'owner') {
            return redirect()->route('owner.dashboard');
        } elseif (Auth::user()->role === 'pl') {
            return redirect()->route('pl.dashboard');
        }
    }

    $categories = VendorCategory::with(['vendors' => function ($query) {
        $query->orderBy('name', 'asc')->with('packages');
    }])->orderBy('name', 'asc')->get();

    return response()
        ->view('vendor', compact('categories'))
        ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
})->name('vendor');

Route::get('/vendor/{id}/detail', function ($id) {
    if (Auth::check()) {
        if (Auth::user()->role === 'owner') return redirect()->route('owner.dashboard');
        if (Auth::user()->role === 'pl') return redirect()->route('pl.dashboard');
    }

    $vendor = \App\Models\Vendor::with(['categories', 'packages', 'portfolios'])->findOrFail($id);

    $clientEvents = collect();
    if (Auth::check() && Auth::user()->role === 'klien') {
        $clientEvents = \App\Models\Event::where('client_id', Auth::id())
            ->where('status', 'Planning')->get();
    }

    return view('vendor_detail', compact('vendor', 'clientEvents'));
})->name('vendor.show');

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

    Route::get('/clients', [OwnerController::class, 'clients'])->name('owner.clients');
    Route::post('/clients', [OwnerController::class, 'storeClient'])->name('owner.clients.store');
    Route::put('/clients/{user}', [OwnerController::class, 'updateClient'])->name('owner.clients.update');

    Route::get('/vendors', [OwnerController::class, 'vendors'])->name('owner.vendors');
    Route::post('/vendors', [OwnerController::class, 'storeVendor'])->name('owner.vendors.store');
    Route::put('/vendors/{vendor}', [OwnerController::class, 'updateVendor'])->name('owner.vendors.update');
    Route::get('/vendors/{vendor}/manage', [OwnerController::class, 'manageVendor'])->name('owner.vendors.manage');

    Route::post('/vendors/{vendor}/contacts', [OwnerController::class, 'storeVendorContact'])->name('owner.vendors.contacts.store');
    Route::put('/vendors/contacts/{contact}', [OwnerController::class, 'updateVendorContact'])->name('owner.vendors.contacts.update');
    Route::delete('/vendors/contacts/{contact}', [OwnerController::class, 'destroyVendorContact'])->name('owner.vendors.contacts.destroy');

    Route::post('/vendors/{vendor}/packages', [OwnerController::class, 'storeVendorPackage'])->name('owner.vendors.packages.store');
    Route::put('/vendors/packages/{package}', [OwnerController::class, 'updateVendorPackage'])->name('owner.vendors.packages.update');
    Route::delete('/vendors/packages/{package}', [OwnerController::class, 'destroyVendorPackage'])->name('owner.vendors.packages.destroy');

    Route::post('/vendors/{vendor}/portfolios', [OwnerController::class, 'storeVendorPortfolio'])->name('owner.vendors.portfolios.store');
    Route::delete('/vendors/portfolios/{portfolio}', [OwnerController::class, 'destroyVendorPortfolio'])->name('owner.vendors.portfolios.destroy');

    Route::get('/events', [EventController::class, 'index'])->name('owner.events.index');
    Route::post('/events', [EventController::class, 'store'])->name('owner.events.store');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('owner.events.update');

    Route::get('/events/{event}/manage', [EventController::class, 'manage'])->name('owner.events.manage');
    Route::post('/events/{event}/slots/custom', [EventController::class, 'addCustomSlot'])->name('owner.events.slots.custom');
    Route::put('/events/{event}/slots/{slot}', [EventController::class, 'assignVendorToSlot'])->name('owner.events.slots.assign');
    Route::put('/events/{event}/slots/{slot}/remove', [EventController::class, 'removeVendorFromSlot'])->name('owner.events.slots.remove');
    Route::delete('/events/{event}/slots/{slot}', [EventController::class, 'destroySlot'])->name('owner.events.slots.destroy');
    Route::put('/events/{event}/slots/{slot}/status', [EventController::class, 'updateSlotStatus'])->name('owner.events.slots.status');
    Route::put('/events/{event}/slots/{slot}/price', [EventController::class, 'updateDealPrice'])->name('owner.events.slots.price');

    Route::post('/events/{event}/guests', [EventController::class, 'storeGuest'])->name('owner.events.guests.store');
    Route::put('/events/{event}/guests/{guest}', [EventController::class, 'updateGuest'])->name('owner.events.guests.update');
    Route::delete('/events/{event}/guests/{guest}', [EventController::class, 'destroyGuest'])->name('owner.events.guests.destroy');

    Route::get('/event-packages', [OwnerController::class, 'weddingPackages'])->name('owner.wedding_packages');
    Route::post('/event-packages', [OwnerController::class, 'storeWeddingPackage'])->name('owner.wedding_packages.store');
    Route::put('/event-packages/{package}', [OwnerController::class, 'updateWeddingPackage'])->name('owner.wedding_packages.update');
    Route::get('/event-packages/{package}/manage', [OwnerController::class, 'manageWeddingPackage'])->name('owner.wedding_packages.manage');
    Route::post('/event-packages/{package}/template', [OwnerController::class, 'storePackageTemplate'])->name('owner.wedding_packages.template.store');
    Route::delete('/event-packages/{package}/template/{template}', [OwnerController::class, 'destroyPackageTemplate'])->name('owner.wedding_packages.template.destroy');
    Route::put('/event-packages/{package}/template/{template}/assign', [OwnerController::class, 'assignVendorToTemplate'])->name('owner.wedding_packages.template.assign');
    Route::put('/event-packages/{package}/price', [OwnerController::class, 'updatePackagePrice'])->name('owner.wedding_packages.price.update');
});

Route::middleware(['auth'])->prefix('pl')->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role !== 'pl') {
            return redirect('/');
        }
        return view('pl.dashboard');
    })->name('pl.dashboard');

    Route::get('/events', [EventController::class, 'index'])->name('pl.events.index');
    Route::post('/events', [EventController::class, 'store'])->name('pl.events.store');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('pl.events.update');

    Route::get('/events/{event}/manage', [EventController::class, 'manage'])->name('pl.events.manage');
    Route::post('/events/{event}/slots/custom', [EventController::class, 'addCustomSlot'])->name('pl.events.slots.custom');
    Route::put('/events/{event}/slots/{slot}', [EventController::class, 'assignVendorToSlot'])->name('pl.events.slots.assign');
    Route::put('/events/{event}/slots/{slot}/remove', [EventController::class, 'removeVendorFromSlot'])->name('pl.events.slots.remove');
    Route::delete('/events/{event}/slots/{slot}', [EventController::class, 'destroySlot'])->name('pl.events.slots.destroy');
    Route::put('/events/{event}/slots/{slot}/status', [EventController::class, 'updateSlotStatus'])->name('pl.events.slots.status');
    Route::put('/events/{event}/slots/{slot}/price', [EventController::class, 'updateDealPrice'])->name('pl.events.slots.price');

    Route::post('/events/{event}/guests', [EventController::class, 'storeGuest'])->name('pl.events.guests.store');
    Route::put('/events/{event}/guests/{guest}', [EventController::class, 'updateGuest'])->name('pl.events.guests.update');
    Route::delete('/events/{event}/guests/{guest}', [EventController::class, 'destroyGuest'])->name('pl.events.guests.destroy');
});

Route::middleware(['auth'])->prefix('client')->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('client.events.index');
    Route::post('/events', [EventController::class, 'store'])->name('client.events.store');
    Route::post('/events/add-package-direct', [EventController::class, 'addPackageFromVendor'])->name('client.events.add_package_direct');
    Route::get('/events/{event}/manage', [EventController::class, 'manage'])->name('client.events.manage');
    Route::put('/events/{event}/slots/{slot}', [EventController::class, 'assignVendorToSlot'])->name('client.events.slots.assign');
    Route::put('/events/{event}/slots/{slot}/remove', [EventController::class, 'removeVendorFromSlot'])->name('client.events.slots.remove');

    Route::get('/events/{event}/guestbook', [EventController::class, 'guestbook'])->name('client.events.guestbook');
    Route::post('/events/{event}/guests', [EventController::class, 'storeGuest'])->name('client.events.guests.store');
    Route::put('/events/{event}/guests/{guest}', [EventController::class, 'updateGuest'])->name('client.events.guests.update');
    Route::delete('/events/{event}/guests/{guest}', [EventController::class, 'destroyGuest'])->name('client.events.guests.destroy');

    Route::get('/events/{event}/billing', [PaymentController::class, 'index'])->name('client.events.billing');
    Route::post('/events/{event}/pay', [PaymentController::class, 'pay'])->name('client.events.pay');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/midtrans/callback', [\App\Http\Controllers\Client\PaymentController::class, 'callback'])->name('midtrans.callback');

require __DIR__ . '/auth.php';
