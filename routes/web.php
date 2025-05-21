<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketResponseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tickets', TicketController::class)->except(['show']);
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show')->middleware('can:view,ticket');
    Route::post('/tickets/{ticket}/restore', [TicketController::class, 'restore'])->name('tickets.restore')->withTrashed();
    Route::delete('/tickets/{ticket}/force-delete', [TicketController::class, 'forceDelete'])->name('tickets.force-delete')->withTrashed();

    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/responses', [TicketResponseController::class, 'store'])->name('tickets.responses.store');
    Route::put('/responses/{response}', [TicketResponseController::class, 'update'])->name('tickets.responses.update');
    Route::delete('/responses/{response}', [TicketResponseController::class, 'destroy'])->name('tickets.responses.destroy');
});

require __DIR__.'/auth.php';
