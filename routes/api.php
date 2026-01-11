<?php

use App\Http\Controllers\Api\BotApiController;
use App\Http\Controllers\Api\PaymentCallbackController;
use App\Http\Controllers\Api\PublicApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public APIs - No authentication required
Route::get('/foods', [PublicApiController::class, 'getFoods'])->name('api.foods');
Route::get('/products', [PublicApiController::class, 'getProducts'])->name('api.products');
Route::get('/subscription-packages', [PublicApiController::class, 'getSubscriptionPackages'])->name('api.subscription-packages');

// Bot Protected APIs - Token authentication required
Route::prefix('bot')->middleware('bot.auth')->group(function () {
    // User & Session
    Route::post('/user/resolve', [BotApiController::class, 'resolveUser'])->name('api.bot.user.resolve');
    Route::post('/user/register', [BotApiController::class, 'registerUser'])->name('api.bot.user.register');
    Route::get('/user/{id}', [BotApiController::class, 'getUser'])->name('api.bot.user.show');
    Route::put('/user/{id}', [BotApiController::class, 'updateUser'])->name('api.bot.user.update');

    // Orders
    Route::post('/order', [BotApiController::class, 'createOrder'])->name('api.bot.order.create');
    Route::get('/order/{id}', [BotApiController::class, 'getOrder'])->name('api.bot.order.show');
    Route::post('/order/cancel', [BotApiController::class, 'cancelOrder'])->name('api.bot.order.cancel');
    Route::get('/order/{id}/delivery', [BotApiController::class, 'getOrderDelivery'])->name('api.bot.order.delivery');

    // Subscriptions
    Route::post('/subscription', [BotApiController::class, 'createSubscription'])->name('api.bot.subscription.create');
    Route::get('/subscription/{id}', [BotApiController::class, 'getSubscription'])->name('api.bot.subscription.show');
    Route::post('/subscription/pause', [BotApiController::class, 'pauseSubscription'])->name('api.bot.subscription.pause');
    Route::post('/subscription/resume', [BotApiController::class, 'resumeSubscription'])->name('api.bot.subscription.resume');
    Route::post('/subscription/upgrade', [BotApiController::class, 'upgradeSubscription'])->name('api.bot.subscription.upgrade');

    // Payments
    Route::post('/payment/initiate', [BotApiController::class, 'initiatePayment'])->name('api.bot.payment.initiate');
    Route::get('/payment/{id}', [BotApiController::class, 'getPayment'])->name('api.bot.payment.show');

    // Location
    Route::post('/location', [BotApiController::class, 'updateLocation'])->name('api.bot.location.update');

    // History & Tracking
    Route::get('/orders/history/{user_id}', [BotApiController::class, 'getOrderHistory'])->name('api.bot.orders.history');
    Route::get('/subscriptions/history/{user_id}', [BotApiController::class, 'getSubscriptionHistory'])->name('api.bot.subscriptions.history');

    // Notifications
    Route::get('/notifications/{user_id}', [BotApiController::class, 'getNotifications'])->name('api.bot.notifications');
    Route::post('/acknowledge', [BotApiController::class, 'acknowledgeNotification'])->name('api.bot.acknowledge');

    // System & Health
    Route::get('/system/health', [BotApiController::class, 'getSystemHealth'])->name('api.bot.system.health');
    Route::get('/config', [BotApiController::class, 'getBotConfig'])->name('api.bot.config');
});

// Payment Callback - No authentication (called by payment gateway)
Route::post('/payment/callback', [PaymentCallbackController::class, 'handle'])->name('api.payment.callback');
