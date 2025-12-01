<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Root\DashboardController as DashboardSuperAdmin;
use App\Http\Controllers\Seller\DashboardController as DashboardSeller;
use App\Http\Controllers\Customer\DashboardController as DashboardCustomer;
use App\Http\Controllers\Root\UserController;
use App\Http\Controllers\Seller\MarketController;
use App\Http\Controllers\Seller\ProductController;
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
        default => route('login'),
    };
});

// Rute Super Admin
Route::middleware(['role.redirect:Super Admin'])
    ->prefix('dashboard')->name('root.')->group(function () {
        Route::get('/root', [DashboardSuperAdmin::class, 'index'])->name('dashboard');
        Route::get('/root/users/sellers', [UserController::class, 'indexSeller'])->name('users.sellers');
        Route::get('/users/sellers/data', [UserController::class, 'dataSeller'])->name('users.sellers.data');
        Route::get('/root/users/customers', [UserController::class, 'indexCustomer'])->name('users.customers');
        Route::get('/users/customers/data', [UserController::class, 'dataCustomer'])->name('users.customers.data');
    });

// Rute Seller
Route::middleware(['role.redirect:Seller'])
    ->prefix('dashboard')->name('seller.')->group(function () {
        Route::get('/seller', [DashboardSeller::class, 'index'])->name('dashboard');
        Route::get('/seller/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/seller/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::get('/seller/market', [MarketController::class, 'index'])->name('market.index');
        Route::get('/seller/market/create', [MarketController::class, 'create'])->name('market.create');
    });

// Rute Customer
Route::middleware(['role.redirect:Customer'])
    ->prefix('dashboard')->name('customer.')->group(function () {
        Route::get('/customer', [DashboardCustomer::class, 'index'])->name('dashboard');
    });

// Profile
Route::middleware('role.redirect:Super Admin,Seller,Customer')->group(function () {
    Route::get('/profile/user/account', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/user/account', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/user/account', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
