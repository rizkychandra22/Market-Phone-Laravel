<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Root\DashboardController as DashboardSuperAdmin;
use App\Http\Controllers\Seller\DashboardController as DashboardSeller;
use App\Http\Controllers\Customer\DashboardController as DashboardCustomer;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Permission\Traits\HasRoles;

Route::get('/', function () {
    $user = Auth::user();

    if (!$user) {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    return match (true) {
        $user->hasRole('Super Admin') => redirect()->route('root.dashboard'),
        $user->hasRole('Seller') => redirect()->route('seller.dashboard'),
        $user->hasRole('Customer') => redirect()->route('customer.dashboard'),
        default => url('/login'),
    };
});

// Rute Super Admin
Route::middleware(['role.redirect:Super Admin'])
    ->prefix('root')
    ->name('root.')
    ->group(function () {
        Route::get('/dashboard', [DashboardSuperAdmin::class, 'index'])->name('dashboard');
    });

// Rute Seller
Route::middleware(['role.redirect:Seller'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {
        Route::get('/dashboard', [DashboardSeller::class, 'index'])->name('dashboard');
    });

// Rute Customer
Route::middleware(['role.redirect:Customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [DashboardCustomer::class, 'index'])->name('dashboard');
    });

// Profile
Route::middleware('role.redirect:Super Admin,Seller,Customer')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
