<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register/step', [\App\Http\Controllers\Auth\RegisterController::class, 'handleStep'])->name('register.step');
    Route::post('/register/skip', [\App\Http\Controllers\Auth\RegisterController::class, 'skipOptional'])->name('register.skip');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store')->middleware('auth');

Route::get('/payment', [PaymentController::class, 'create'])->name('payment.create')->middleware('auth');
Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store')->middleware('auth');

Route::get('/confirmation', [ConfirmationController::class, 'show'])->name('confirmation.show')->middleware('auth');

Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('orders/{order}/assign', [AdminOrderController::class, 'assignDelivery'])->name('orders.assign');

    Route::resource('subscriptions', AdminSubscriptionController::class)->only(['index', 'show']);
    Route::post('subscriptions/{subscription}/pause', [AdminSubscriptionController::class, 'pause'])->name('subscriptions.pause');
    Route::post('subscriptions/{subscription}/resume', [AdminSubscriptionController::class, 'resume'])->name('subscriptions.resume');

    Route::get('menu', [MenuController::class, 'index'])->name('menu.index');

    // Categories
    Route::get('menu/categories/create', [MenuController::class, 'createCategory'])->name('menu.categories.create');
    Route::post('menu/categories', [MenuController::class, 'storeCategory'])->name('menu.categories.store');
    Route::get('menu/categories/{category}/edit', [MenuController::class, 'editCategory'])->name('menu.categories.edit');
    Route::patch('menu/categories/{category}', [MenuController::class, 'updateCategory'])->name('menu.categories.update');
    Route::delete('menu/categories/{category}', [MenuController::class, 'destroyCategory'])->name('menu.categories.destroy');

    // Food Items
    Route::get('menu/food-items/create', [MenuController::class, 'createFoodItem'])->name('menu.food-items.create');
    Route::post('menu/food-items', [MenuController::class, 'storeFoodItem'])->name('menu.food-items.store');
    Route::get('menu/food-items/{foodItem}/edit', [MenuController::class, 'editFoodItem'])->name('menu.food-items.edit');
    Route::patch('menu/food-items/{foodItem}', [MenuController::class, 'updateFoodItem'])->name('menu.food-items.update');
    Route::delete('menu/food-items/{foodItem}', [MenuController::class, 'destroyFoodItem'])->name('menu.food-items.destroy');

    // Kitchen Products
    Route::get('menu/kitchen-products/create', [MenuController::class, 'createKitchenProduct'])->name('menu.kitchen-products.create');
    Route::post('menu/kitchen-products', [MenuController::class, 'storeKitchenProduct'])->name('menu.kitchen-products.store');
    Route::get('menu/kitchen-products/{kitchenProduct}/edit', [MenuController::class, 'editKitchenProduct'])->name('menu.kitchen-products.edit');
    Route::patch('menu/kitchen-products/{kitchenProduct}', [MenuController::class, 'updateKitchenProduct'])->name('menu.kitchen-products.update');
    Route::delete('menu/kitchen-products/{kitchenProduct}', [MenuController::class, 'destroyKitchenProduct'])->name('menu.kitchen-products.destroy');

    // Subscription Packages
    Route::get('menu/subscription-packages/create', [MenuController::class, 'createSubscriptionPackage'])->name('menu.subscription-packages.create');
    Route::post('menu/subscription-packages', [MenuController::class, 'storeSubscriptionPackage'])->name('menu.subscription-packages.store');
    Route::get('menu/subscription-packages/{subscriptionPackage}/edit', [MenuController::class, 'editSubscriptionPackage'])->name('menu.subscription-packages.edit');
    Route::patch('menu/subscription-packages/{subscriptionPackage}', [MenuController::class, 'updateSubscriptionPackage'])->name('menu.subscription-packages.update');
    Route::delete('menu/subscription-packages/{subscriptionPackage}', [MenuController::class, 'destroySubscriptionPackage'])->name('menu.subscription-packages.destroy');

    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
});
