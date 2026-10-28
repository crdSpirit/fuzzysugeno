<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\StoreProfileController;
use App\Http\Controllers\Supplier\SupplierDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED AREA
|--------------------------------------------------------------------------
 */
Route::middleware('auth')->group(function () {

    // ======================
    // DASHBOARD UMUM
    // ======================
    Route::get('/', [SupplierDashboardController::class, 'dashboard'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER (TOKO PEMESAN)
    |--------------------------------------------------------------------------
     */
    Route::middleware('role:customer_admin,customer_staff')->group(function () {

        // ORDER (CUSTOMER)
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::post('/orders/{order}/items', [OrderController::class, 'addItem'])->name('orders.items.add');
        Route::delete('/orders/items/{item}', [OrderController::class, 'removeItem'])->name('orders.items.remove');

        Route::post('/orders/{order}/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
        Route::post('/orders/{id}/preview', [OrderController::class, 'previewCheckout'])
            ->name('orders.previewCheckout');

        Route::post('/orders/{id}/final-checkout', [OrderController::class, 'finalCheckout'])
            ->name('orders.finalCheckout');

    });

    /*
    |--------------------------------------------------------------------------
    | SUPPLIER (TOKO PUSAT)
    |--------------------------------------------------------------------------
     */
    Route::middleware('role:supplier_admin,supplier_staff')->prefix('supplier')->name('supplier.')->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [SupplierDashboardController::class, 'dashboard'])
            ->name('dashboard');

        // ORDER MASUK
        Route::get('/orders', [SupplierDashboardController::class, 'orders'])
            ->name('orders');

        Route::get('/orders/{order}', [SupplierDashboardController::class, 'show'])
            ->name('orders.show');

        // SHIPMENT + FUZZY
        Route::post('/shipments/{order}/process', [ShipmentController::class, 'process'])
            ->name('shipments.process');

    });
    Route::post(
        '/supplier/shipments/{order}/ship',
        [ShipmentController::class, 'ship']
    )->name('supplier.shipments.ship');

    Route::get('/store/profile', [StoreProfileController::class, 'edit'])
        ->name('store.profile.edit');

    Route::post('/store/profile', [StoreProfileController::class, 'update'])
        ->name('store.profile.update');

    Route::get('/locations/provinces', [LocationController::class, 'provinces']);
    Route::get('/locations/cities/{province}', [LocationController::class, 'cities']);
    Route::get('/locations/districts/{city}', [LocationController::class, 'districts']);
    Route::get('/locations/subdistricts/{district}', [LocationController::class, 'subdistricts']);

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

});
