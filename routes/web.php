<?php
//import all of the controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

//
// When someone visits your homepage ('/'),
// run the index method in HomeController and call this route "home".
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [EventController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/events', [EventController::class, 'index'])->name('events.index');

//Booking routes
//Make a booking
Route::post('/events/{event}/book', [BookingController::class, 'store'])->name('bookings.store');
//Delete a booking
Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

// Middleware routes - routes that can only be accessed by authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/profile',[ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class,'destroy'])->name('profile.destroy');
    
    //EventController Routes (CRUD)
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::patch('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    
    // My Bookings page
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my-bookings');
    
    // Cancel booking route
    Route::delete('/bookings/{id}/cancel', [BookingController::class, 'cancelBooking'])->name('bookings.cancel');
});

//Searching Routes - IS public by theory
Route::get('/search',[SearchController::class,'index'])->name('search.index');
Route::get('/search/results', [SearchController::class,'search'])->name('search.results');
Route::get('/advanced-search',[SearchController::class,'search'])->name('search');

// Route to the privacy policy
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy.policy');

// Add event details route (for Task 4)
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

require __DIR__.'/auth.php';