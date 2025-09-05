<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CarouselSlideController;

// Ruta de login (sin autenticación)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestión de pedidos
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{id}', [DashboardController::class, 'showOrder'])->name('orders.show');
    Route::post('/orders/{id}/update-status', [DashboardController::class, 'updateOrderStatus'])->name('orders.update-status');
    
    // Gestión de productos
    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/toggle-availability', [ProductController::class, 'toggleAvailability'])->name('products.toggle-availability');
    
    // Gestión de contactos
    Route::resource('contacts', ContactController::class)->only(['index', 'show', 'destroy']);
    Route::post('/contacts/{contact}/toggle-read', [ContactController::class, 'toggleRead'])->name('contacts.toggle-read');
    
    // Gestión de slides del carrusel
    Route::resource('carousel-slides', CarouselSlideController::class);
    
    // API para tiempo real
    Route::get('/api/orders/realtime', [DashboardController::class, 'getRealtimeOrders'])->name('api.orders.realtime');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
