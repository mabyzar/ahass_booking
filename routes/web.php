<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

Route::get('/', [BookingController::class, 'index'])->name('bookings.index');
Route::get('/bookings/data', [BookingController::class, 'getBookingsData'])->name('bookings.data');
Route::get('/bookings/slots', [BookingController::class, 'getSlots'])->name('bookings.slots');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
Route::patch('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');
Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');

