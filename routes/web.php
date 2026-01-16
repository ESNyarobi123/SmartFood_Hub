<?php

use App\Http\Controllers\Admin\Cyber\DashboardController as CyberDashboardController;
use App\Http\Controllers\Admin\Cyber\MealSlotController;
use App\Http\Controllers\Admin\Cyber\MenuController as CyberMenuController;
use App\Http\Controllers\Admin\Cyber\OrderController as CyberOrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\Food\DashboardController as FoodDashboardController;
use App\Http\Controllers\Admin\Food\OrderController as FoodOrderController;
use App\Http\Controllers\Admin\Food\PackageController;
use App\Http\Controllers\Admin\Food\ProductController;
use App\Http\Controllers\Admin\Food\SubscriptionController as FoodSubscriptionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\Cyber\OrderController as CyberCustomerOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Food\OrderController as FoodCustomerOrderController;
use App\Http\Controllers\Food\PackageController as FoodCustomerPackageController;
use App\Http\Controllers\Food\SubscriptionController as FoodCustomerSubscriptionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// ================================================
// PUBLIC ROUTES
// ================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ================================================
// AUTHENTICATION ROUTES
// ================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register/step', [\App\Http\Controllers\Auth\RegisterController::class, 'handleStep'])->name('register.step');
    Route::post('/register/skip', [\App\Http\Controllers\Auth\RegisterController::class, 'skipOptional'])->name('register.skip');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ================================================
// UNIFIED CUSTOMER DASHBOARD
// ================================================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ================================================
// CYBER CAFE CUSTOMER ROUTES
// ================================================
Route::prefix('cyber')->name('cyber.')->group(function () {
    Route::get('/', [CyberCustomerOrderController::class, 'index'])->name('index');
    Route::get('/menu', [CyberCustomerOrderController::class, 'menu'])->name('menu');

    Route::middleware('auth')->group(function () {
        Route::post('/order', [CyberCustomerOrderController::class, 'store'])->name('order.store');
        Route::get('/order/{order}', [CyberCustomerOrderController::class, 'show'])->name('order.show');
        Route::get('/orders', [CyberCustomerOrderController::class, 'history'])->name('orders');
    });
});

// ================================================
// MONANA FOOD CUSTOMER ROUTES
// ================================================
Route::prefix('food')->name('food.')->group(function () {
    Route::get('/', [FoodCustomerPackageController::class, 'index'])->name('index');
    Route::get('/packages', [FoodCustomerPackageController::class, 'packages'])->name('packages');
    Route::get('/packages/{package}', [FoodCustomerPackageController::class, 'show'])->name('packages.show');
    Route::get('/custom', [FoodCustomerOrderController::class, 'custom'])->name('custom');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [FoodCustomerSubscriptionController::class, 'dashboard'])->name('dashboard');
        Route::post('/packages/{package}/subscribe', [FoodCustomerSubscriptionController::class, 'subscribe'])->name('packages.subscribe');
        Route::get('/subscription/{subscription}', [FoodCustomerSubscriptionController::class, 'show'])->name('subscription.show');
        Route::post('/subscription/{subscription}/customize', [FoodCustomerSubscriptionController::class, 'customize'])->name('subscription.customize');
        Route::post('/subscription/{subscription}/pause', [FoodCustomerSubscriptionController::class, 'pause'])->name('subscription.pause');
        Route::post('/subscription/{subscription}/resume', [FoodCustomerSubscriptionController::class, 'resume'])->name('subscription.resume');

        Route::post('/order', [FoodCustomerOrderController::class, 'store'])->name('order.store');
        Route::get('/order/{order}', [FoodCustomerOrderController::class, 'show'])->name('order.show');
        Route::get('/orders', [FoodCustomerOrderController::class, 'history'])->name('orders');
    });
});

// ================================================
// PAYMENT ROUTES
// ================================================
Route::middleware('auth')->group(function () {
    Route::get('/payment', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');
    Route::get('/confirmation', [ConfirmationController::class, 'show'])->name('confirmation.show');
});

// ================================================
// ADMIN ROUTES
// ================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
    // Main Dashboard (Overview)
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // ================================================
    // CYBER CAFE ADMIN
    // ================================================
    Route::prefix('cyber')->name('cyber.')->group(function () {
        Route::get('/', [CyberDashboardController::class, 'index'])->name('dashboard');

        // Menu Items
        Route::get('/menu', [CyberMenuController::class, 'index'])->name('menu.index');
        Route::get('/menu/create', [CyberMenuController::class, 'create'])->name('menu.create');
        Route::post('/menu', [CyberMenuController::class, 'store'])->name('menu.store');
        Route::get('/menu/{menuItem}/edit', [CyberMenuController::class, 'edit'])->name('menu.edit');
        Route::patch('/menu/{menuItem}', [CyberMenuController::class, 'update'])->name('menu.update');
        Route::delete('/menu/{menuItem}', [CyberMenuController::class, 'destroy'])->name('menu.destroy');

        // Meal Slots
        Route::get('/meal-slots', [MealSlotController::class, 'index'])->name('meal-slots.index');
        Route::get('/meal-slots/{mealSlot}/edit', [MealSlotController::class, 'edit'])->name('meal-slots.edit');
        Route::patch('/meal-slots/{mealSlot}', [MealSlotController::class, 'update'])->name('meal-slots.update');
        Route::patch('/meal-slots/{mealSlot}/toggle', [MealSlotController::class, 'toggle'])->name('meal-slots.toggle');

        // Orders
        Route::get('/orders', [CyberOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/map', [CyberOrderController::class, 'map'])->name('orders.map');
        Route::get('/orders/{order}', [CyberOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [CyberOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::patch('/orders/{order}/assign', [CyberOrderController::class, 'assign'])->name('orders.assign');
    });

    // ================================================
    // MONANA FOOD ADMIN
    // ================================================
    Route::prefix('food')->name('food.')->group(function () {
        Route::get('/', [FoodDashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Packages
        Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
        Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');
        Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
        Route::get('/packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
        Route::patch('/packages/{package}', [PackageController::class, 'update'])->name('packages.update');
        Route::delete('/packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');
        Route::get('/packages/{package}/rules', [PackageController::class, 'rules'])->name('packages.rules');
        Route::post('/packages/{package}/rules', [PackageController::class, 'storeRule'])->name('packages.rules.store');
        Route::delete('/packages/rules/{rule}', [PackageController::class, 'destroyRule'])->name('packages.rules.destroy');

        // Subscriptions
        Route::get('/subscriptions', [FoodSubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::get('/subscriptions/{subscription}', [FoodSubscriptionController::class, 'show'])->name('subscriptions.show');
        Route::post('/subscriptions/{subscription}/pause', [FoodSubscriptionController::class, 'pause'])->name('subscriptions.pause');
        Route::post('/subscriptions/{subscription}/resume', [FoodSubscriptionController::class, 'resume'])->name('subscriptions.resume');
        Route::post('/subscriptions/{subscription}/cancel', [FoodSubscriptionController::class, 'cancel'])->name('subscriptions.cancel');

        // Orders
        Route::get('/orders', [FoodOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/map', [FoodOrderController::class, 'map'])->name('orders.map');
        Route::get('/orders/{order}', [FoodOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [FoodOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::patch('/orders/{order}/assign', [FoodOrderController::class, 'assign'])->name('orders.assign');
    });

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});
