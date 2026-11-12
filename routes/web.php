
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Guest routes: accessible only when NOT logged in
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1')
        ->name('register.submit');
});

// Protected routes: accessible only when logged in
Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/products', [DashboardController::class, 'storeProduct'])
        ->name('products.store');

    Route::post('/customers', [DashboardController::class, 'storeCustomer'])
        ->name('customers.store');

    Route::post('/orders', [DashboardController::class, 'storeOrder'])
        ->name('orders.store');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
