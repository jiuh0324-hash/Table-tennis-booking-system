<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\TableTennisTableController;
use App\Http\Controllers\ProfileController; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/bookings');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');

    Route::get('/bookings', [BookingController::class, 'index'])
        ->middleware('can:viewAny,App\Models\Booking')
        ->name('bookings.index');
    
    Route::get('/bookings/create', [BookingController::class, 'create'])
        ->middleware('can:create,App\Models\Booking')
        ->name('bookings.create');
    
    Route::post('/bookings', [BookingController::class, 'store'])
        ->middleware('can:create,App\Models\Booking')
        ->name('bookings.store');
    
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])
        ->middleware('can:update,booking')
        ->name('bookings.edit');
    
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])
        ->middleware('can:update,booking')
        ->name('bookings.update');
    
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])
        ->middleware('can:delete,booking')
        ->name('bookings.destroy');
    
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])
        ->name('bookings.my');

    Route::patch('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])
        ->middleware('can:confirm,booking')
        ->name('bookings.confirm');
    
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])
        ->middleware('can:cancel,booking')
        ->name('bookings.cancel');

    Route::get('/tables', [TableTennisTableController::class, 'index'])
        ->middleware('can:viewAny,App\Models\TableTennisTable')
        ->name('tables.index');
    
    Route::get('/tables/create', [TableTennisTableController::class, 'create'])
        ->middleware('can:create,App\Models\TableTennisTable')
        ->name('tables.create');
    
    Route::post('/tables', [TableTennisTableController::class, 'store'])
        ->middleware('can:create,App\Models\TableTennisTable')
        ->name('tables.store');
    
    Route::get('/tables/{table}/edit', [TableTennisTableController::class, 'edit'])
        ->middleware('can:update,table')
        ->name('tables.edit');
    
    Route::put('/tables/{table}', [TableTennisTableController::class, 'update'])
        ->middleware('can:update,table')
        ->name('tables.update');
    
    Route::delete('/tables/{table}', [TableTennisTableController::class, 'destroy'])
        ->middleware('can:delete,table')
        ->name('tables.destroy');
});

require __DIR__.'/auth.php';