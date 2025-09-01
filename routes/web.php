<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\DrinkController;
use App\Http\Controllers\BookingReportController;
use App\Http\Controllers\API\MovieShowtimesController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AdminUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root to login
Route::get('/', function() {
    if (Auth::guard('admin')->check()) {
        return redirect('/dashboard');
    } elseif (Auth::guard('web')->check()) {
        return redirect('/dashboard/pos');
    }
    return redirect()->route('admin.login');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
});

// Logout route
Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// Protected routes
Route::middleware('auth:admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users section
    Route::get('/dashboard/users', [DashboardController::class, 'users'])->name('dashboard.users');

    // Movies section
    Route::get('/dashboard/movies', [DashboardController::class, 'movies'])->name('dashboard.movies');
    Route::resource('movies', MovieController::class);

    // Halls section
    Route::get('/dashboard/halls', [DashboardController::class, 'halls'])->name('dashboard.halls');
    Route::resource('halls', HallController::class);

    // Seats section
    Route::get('/dashboard/seats', [DashboardController::class, 'seats'])->name('dashboard.seats');
    Route::resource('seats', SeatController::class);
    Route::match(['get', 'post'], 'seats/bulk-create', [SeatController::class, 'bulkCreate'])->name('seats.bulkCreate');
    Route::post('seats/bulk-store', [SeatController::class, 'bulkStore'])->name('seats.bulkStore');

    // Food and drinks
    Route::get('/dashboard/food-items', [DashboardController::class, 'foodItems'])->name('dashboard.food-items');
    Route::resource('food-items', FoodItemController::class);

    Route::get('/dashboard/drinks', [DashboardController::class, 'drinks'])->name('dashboard.drinks');
    Route::resource('drinks', DrinkController::class);

    // Reports
    Route::get('/dashboard/reports/bookings', [BookingReportController::class, 'index'])->name('dashboard.reports.bookings');
    Route::get('/dashboard/reports/bookings/export', [BookingReportController::class, 'exportExcel'])->name('dashboard.reports.bookings.export');
    Route::get('/dashboard/reports/bookings/print', [BookingReportController::class, 'printReport'])->name('dashboard.reports.bookings.print');
    Route::get('/dashboard/bookings/{id}', [DashboardController::class, 'showBooking'])->name('dashboard.bookings.show');
    Route::get('/dashboard/bookings/{id}/print', [DashboardController::class, 'printBooking'])->name('dashboard.bookings.print');

    // Hall assignments
    Route::get('/dashboard/movie-hall-assignments', [DashboardController::class, 'movieHallAssignments'])->name('dashboard.movieHallAssignments');
    Route::get('/dashboard/movie-hall-assignments/movie/{movie}', [DashboardController::class, 'movieShowtimes'])->name('dashboard.movieShowtimes');
    Route::post('/dashboard/movie-hall-assignments/store', [DashboardController::class, 'storeMovieHallAssignment'])->name('dashboard.movieHallAssignments.store');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('admin.profile.show');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('admin.profile.settings');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/profile/image', [ProfileController::class, 'updateImage'])->name('admin.profile.update.image');

    // User Management Routes
    Route::prefix('dashboard/users')->name('dashboard.users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
        Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
    });
});

// POS System routes - accessible by both admin and regular users
Route::middleware(['auth:admin,web'])->group(function () {
    Route::get('/dashboard/pos', [DashboardController::class, 'pos'])->name('dashboard.pos');
    Route::get('/dashboard/pos/get-movie-halls/{movie}', [DashboardController::class, 'getMovieHalls'])->name('dashboard.pos.getMovieHalls');
    Route::get('/dashboard/pos/get-hall-seats/{hall}', [DashboardController::class, 'getHallSeats'])->name('dashboard.pos.getHallSeats');
    Route::post('/dashboard/pos/save-booking', [DashboardController::class, 'saveBooking'])->name('dashboard.pos.saveBooking');
    Route::post('/dashboard/pos/book-ticket', [DashboardController::class, 'bookTicket'])->name('dashboard.pos.bookTicket');
});

// Responsive Design Test Route (Development only)
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/responsive-test', function () {
        return view('responsive-test');
    })->name('responsive.test');
});

// API routes
Route::get('/api/movie-showtimes/{movie}', [MovieShowtimesController::class, 'getShowtimes']);
