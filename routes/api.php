<?php

use App\Http\Controllers\Api\BotApiController;
use App\Http\Controllers\Api\CyberApiController;
use App\Http\Controllers\Api\FoodApiController;
use App\Http\Controllers\Api\PaymentCallbackController;
use App\Http\Controllers\Api\PublicApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for Monana Platform
|--------------------------------------------------------------------------
*/

// ========================================
// PUBLIC APIs - No authentication required
// ========================================

// Service Selection
Route::get('/services', [PublicApiController::class, 'getServices'])->name('api.services');

// Cyber Cafe Public APIs
Route::prefix('cyber')->group(function () {
    Route::get('/menu', [PublicApiController::class, 'getCyberMenu'])->name('api.cyber.menu');
    Route::get('/meal-slots', [PublicApiController::class, 'getMealSlots'])->name('api.cyber.meal-slots');
});

// Monana Food Public APIs
Route::prefix('food')->group(function () {
    Route::get('/products', [PublicApiController::class, 'getFoodProducts'])->name('api.food.products');
    Route::get('/packages', [PublicApiController::class, 'getFoodPackages'])->name('api.food.packages');
    Route::get('/packages/{id}', [PublicApiController::class, 'getFoodPackage'])->name('api.food.package');
});

// Legacy endpoints (for backward compatibility)
Route::get('/foods', [PublicApiController::class, 'getCyberMenu'])->name('api.foods');
Route::get('/products', [PublicApiController::class, 'getFoodProducts'])->name('api.products');
Route::get('/subscription-packages', [PublicApiController::class, 'getFoodPackages'])->name('api.subscription-packages');

// ========================================
// BOT PROTECTED APIs - Token authentication required
// ========================================
Route::prefix('bot')->middleware('bot.auth')->group(function () {

    // Service Selection
    Route::post('/service/select', [BotApiController::class, 'selectService'])->name('api.bot.service.select');

    // User & Session
    Route::post('/user/resolve', [BotApiController::class, 'resolveUser'])->name('api.bot.user.resolve');
    Route::post('/user/register', [BotApiController::class, 'registerUser'])->name('api.bot.user.register');
    Route::get('/user/{id}', [BotApiController::class, 'getUser'])->name('api.bot.user.show');
    Route::put('/user/{id}', [BotApiController::class, 'updateUser'])->name('api.bot.user.update');
    Route::post('/location', [BotApiController::class, 'updateLocation'])->name('api.bot.location.update');

    // ========================================
    // CYBER CAFE BOT APIs
    // ========================================
    Route::prefix('cyber')->group(function () {
        Route::get('/menu', [CyberApiController::class, 'getMenu'])->name('api.bot.cyber.menu');
        Route::get('/meal-slots', [CyberApiController::class, 'getMealSlots'])->name('api.bot.cyber.meal-slots');
        Route::post('/order/create', [CyberApiController::class, 'createOrder'])->name('api.bot.cyber.order.create');
        Route::get('/order/{id}', [CyberApiController::class, 'getOrder'])->name('api.bot.cyber.order.show');
        Route::post('/order/cancel', [CyberApiController::class, 'cancelOrder'])->name('api.bot.cyber.order.cancel');
        Route::get('/orders/history/{user_id}', [CyberApiController::class, 'getOrderHistory'])->name('api.bot.cyber.orders.history');
    });

    // ========================================
    // MONANA FOOD BOT APIs
    // ========================================
    Route::prefix('food')->group(function () {
        Route::get('/products', [FoodApiController::class, 'getProducts'])->name('api.bot.food.products');
        Route::get('/packages', [FoodApiController::class, 'getPackages'])->name('api.bot.food.packages');
        Route::get('/packages/{id}', [FoodApiController::class, 'getPackage'])->name('api.bot.food.package');

        // Subscriptions
        Route::post('/subscription/create', [FoodApiController::class, 'createSubscription'])->name('api.bot.food.subscription.create');
        Route::get('/subscription/{id}', [FoodApiController::class, 'getSubscription'])->name('api.bot.food.subscription.show');
        Route::post('/subscription/customize', [FoodApiController::class, 'customizeSubscription'])->name('api.bot.food.subscription.customize');
        Route::post('/subscription/pause', [FoodApiController::class, 'pauseSubscription'])->name('api.bot.food.subscription.pause');
        Route::post('/subscription/resume', [FoodApiController::class, 'resumeSubscription'])->name('api.bot.food.subscription.resume');
        Route::get('/subscriptions/history/{user_id}', [FoodApiController::class, 'getSubscriptionHistory'])->name('api.bot.food.subscriptions.history');

        // Custom Orders
        Route::post('/order/create', [FoodApiController::class, 'createOrder'])->name('api.bot.food.order.create');
        Route::get('/order/{id}', [FoodApiController::class, 'getOrder'])->name('api.bot.food.order.show');
        Route::post('/order/cancel', [FoodApiController::class, 'cancelOrder'])->name('api.bot.food.order.cancel');
        Route::get('/orders/history/{user_id}', [FoodApiController::class, 'getOrderHistory'])->name('api.bot.food.orders.history');
    });

    // ========================================
    // PAYMENTS (Shared)
    // ========================================
    Route::post('/payment/initiate', [BotApiController::class, 'initiatePayment'])->name('api.bot.payment.initiate');
    Route::get('/payment/{id}', [BotApiController::class, 'getPayment'])->name('api.bot.payment.show');

    // Notifications
    Route::get('/notifications/{user_id}', [BotApiController::class, 'getNotifications'])->name('api.bot.notifications');
    Route::post('/acknowledge', [BotApiController::class, 'acknowledgeNotification'])->name('api.bot.acknowledge');

    // System & Health
    Route::get('/system/health', [BotApiController::class, 'getSystemHealth'])->name('api.bot.system.health');
    Route::get('/config', [BotApiController::class, 'getBotConfig'])->name('api.bot.config');

    // Legacy endpoints (for backward compatibility - keep old flows working)
    Route::post('/order', [BotApiController::class, 'createOrder'])->name('api.bot.order.create');
    Route::get('/order/{id}', [BotApiController::class, 'getOrder'])->name('api.bot.order.show');
    Route::post('/subscription', [BotApiController::class, 'createSubscription'])->name('api.bot.subscription.create');
    Route::get('/subscription/{id}', [BotApiController::class, 'getSubscription'])->name('api.bot.subscription.show');
});

// Payment Callback - No authentication (called by payment gateway)
Route::post('/payment/callback', [PaymentCallbackController::class, 'handle'])->name('api.payment.callback');
