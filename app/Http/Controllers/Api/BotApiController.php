<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\CheckPaymentStatus;
use App\Models\Cyber\Order as CyberOrder;
use App\Models\Food\Order as FoodOrder;
use App\Models\Food\Package as FoodPackage;
use App\Models\Food\Subscription as FoodSubscription;
use App\Models\FoodItem;
use App\Models\KitchenProduct;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPackage;
use App\Models\User;
use App\Services\ZenoPayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BotApiController extends Controller
{
    public function __construct(
        private ZenoPayService $zenoPay
    ) {}

    /**
     * Create a new order from WhatsApp bot.
     */
    public function createOrder(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'items' => 'required|array|min:1',
                'items.*.type' => 'required|string|in:food,product',
                'items.*.id' => 'required|integer',
                'items.*.qty' => 'required|integer|min:1',
                'items.*.notes' => 'nullable|string',
                'custom_notes' => 'nullable|string',
                'location' => 'required|string',
            ]);

            DB::beginTransaction();

            $user = User::findOrFail($validated['user_id']);

            // Calculate total amount first
            $totalAmount = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                if ($item['type'] === 'food') {
                    $product = FoodItem::where('is_available', true)->findOrFail($item['id']);
                } else {
                    $product = KitchenProduct::where('is_available', true)->findOrFail($item['id']);
                }

                $itemTotal = $product->price * $item['qty'];
                $totalAmount += $itemTotal;

                // Store item data for later
                $itemsData[] = [
                    'orderable_type' => $item['type'] === 'food' ? FoodItem::class : KitchenProduct::class,
                    'orderable_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $product->price,
                    'notes' => $item['notes'] ?? null,
                ];
            }

            // Create and save the order first (so it gets an ID)
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'delivery_address' => $validated['location'],
                'notes' => $validated['custom_notes'] ?? null,
                'status' => 'pending',
                'source' => 'whatsapp',
                'total_amount' => $totalAmount,
            ]);

            // Now create order items with the order_id
            foreach ($itemsData as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'orderable_type' => $itemData['orderable_type'],
                    'orderable_id' => $itemData['orderable_id'],
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'notes' => $itemData['notes'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'order_id' => $order->id,
                'message' => 'Order imepokelewa',
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get order details by ID.
     */
    public function getOrder(int $id): JsonResponse
    {
        try {
            $order = Order::with(['orderItems.orderable', 'assignedUser'])
                ->findOrFail($id);

            $items = $order->orderItems->map(function ($item) {
                return [
                    'name' => $item->orderable->name ?? 'Unknown',
                    'qty' => $item->quantity,
                    'price' => (float) $item->price,
                ];
            });

            return response()->json([
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'items' => $items,
                'total_amount' => (float) $order->total_amount,
                'delivery_status' => $order->assigned_to ? 'assigned' : 'pending',
                'location' => $order->delivery_address,
                'assigned_to' => $order->assignedUser ? $order->assignedUser->name : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }
    }

    /**
     * Create a new subscription from WhatsApp bot.
     */
    public function createSubscription(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'package_id' => 'required|integer|exists:subscription_packages,id',
                'custom_items' => 'nullable|array',
                'notes' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $user = User::findOrFail($validated['user_id']);
            $package = SubscriptionPackage::where('is_active', true)->findOrFail($validated['package_id']);

            $startDate = now();
            $endDate = match ($package->duration_type) {
                'weekly' => $startDate->copy()->addWeek(),
                'monthly' => $startDate->copy()->addMonth(),
                default => $startDate->copy()->addWeek(),
            };

            $subscription = Subscription::create([
                'user_id' => $user->id,
                'subscription_package_id' => $package->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'pending',
                'delivery_schedule' => $this->generateDeliverySchedule($startDate, $endDate, $package->meals_per_week, $package->delivery_days),
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'subscription_id' => $subscription->id,
                'start_date' => $subscription->start_date->format('Y-m-d'),
                'end_date' => $subscription->end_date->format('Y-m-d'),
                'payment_status' => 'pending',
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create subscription: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get subscription details by ID.
     */
    public function getSubscription(int $id): JsonResponse
    {
        try {
            $subscription = Subscription::with('subscriptionPackage')
                ->findOrFail($id);

            $payment = $subscription->payments()->latest()->first();

            return response()->json([
                'subscription_id' => $subscription->id,
                'status' => $subscription->status,
                'start_date' => $subscription->start_date->format('Y-m-d'),
                'end_date' => $subscription->end_date->format('Y-m-d'),
                'payment_status' => $payment ? $payment->status : 'pending',
                'package_name' => $subscription->subscriptionPackage->name,
                'package_price' => (float) $subscription->subscriptionPackage->price,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Subscription not found',
            ], 404);
        }
    }

    /**
     * Initiate payment for order or subscription.
     * Supports: cyber_order, food_order, food_subscription + legacy order/subscription
     */
    public function initiatePayment(Request $request, ZenoPayService $zenoPay): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'type' => 'required|string|in:order,subscription,cyber_order,food_order,food_subscription',
                'id' => 'required|integer',
                'method' => 'required|string|in:mpesa,tigopesa,airtelmoney',
                'phone_number' => 'nullable|string|max:20',
            ]);

            if (! $zenoPay->isConfigured()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment gateway is not configured',
                ], 500);
            }

            DB::beginTransaction();

            $user = User::findOrFail($validated['user_id']);
            $transactionId = Str::uuid()->toString();
            $amount = 0;
            $serviceType = null;
            $paymentForeignKeys = [];

            // Resolve the payable entity based on type
            switch ($validated['type']) {
                case 'cyber_order':
                    $entity = CyberOrder::findOrFail($validated['id']);
                    $amount = $entity->total_amount;
                    $serviceType = 'cyber';
                    $paymentForeignKeys = ['cyber_order_id' => $entity->id];
                    break;

                case 'food_order':
                    $entity = FoodOrder::findOrFail($validated['id']);
                    $amount = $entity->total_amount;
                    $serviceType = 'food';
                    $paymentForeignKeys = ['food_order_id' => $entity->id];
                    break;

                case 'food_subscription':
                    $entity = FoodSubscription::with('package')->findOrFail($validated['id']);
                    $amount = $entity->package?->base_price ?? $entity->total_amount ?? 0;
                    $serviceType = 'food';
                    $paymentForeignKeys = ['food_subscription_id' => $entity->id];
                    break;

                // Legacy fallbacks
                case 'order':
                    $entity = Order::findOrFail($validated['id']);
                    $amount = $entity->total_amount;
                    $paymentForeignKeys = ['order_id' => $entity->id];
                    break;

                case 'subscription':
                    $entity = Subscription::with('subscriptionPackage')->findOrFail($validated['id']);
                    $amount = $entity->subscriptionPackage->price;
                    $paymentForeignKeys = ['subscription_id' => $entity->id];
                    break;
            }

            if ($amount <= 0) {
                DB::rollBack();

                return response()->json([
                    'status' => 'error',
                    'message' => 'Kiasi cha malipo si sahihi (0 au chini).',
                ], 422);
            }

            // Create payment record
            $payment = Payment::create(array_merge([
                'amount' => $amount,
                'payment_method' => 'mobile_money',
                'phone_number' => $user->phone ?? null,
                'transaction_id' => $transactionId,
                'status' => 'pending',
                'service_type' => $serviceType,
            ], $paymentForeignKeys));

            // Format phone number - use provided phone or user's phone
            $phoneNumber = $validated['phone_number'] ?? $user->phone ?? '';
            if (empty($phoneNumber)) {
                DB::rollBack();

                return response()->json([
                    'status' => 'error',
                    'message' => 'Namba ya simu inahitajika kwa malipo',
                ], 422);
            }

            // Format phone number to 07XXXXXXXX format
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            if (! str_starts_with($phoneNumber, '0')) {
                if (str_starts_with($phoneNumber, '255')) {
                    $phoneNumber = '0'.substr($phoneNumber, 3);
                } else {
                    $phoneNumber = '0'.$phoneNumber;
                }
            }

            // Initiate payment with ZenoPay
            $paymentData = [
                'order_id' => $transactionId,
                'buyer_email' => $user->email,
                'buyer_name' => $user->name,
                'buyer_phone' => $phoneNumber,
                'amount' => (int) $amount,
            ];

            $result = $zenoPay->initiatePayment($paymentData);

            if (! $result['success']) {
                DB::rollBack();

                return response()->json([
                    'status' => 'error',
                    'message' => $result['message'] ?? 'Payment initiation failed',
                ], 500);
            }

            DB::commit();

            // Schedule payment status check
            CheckPaymentStatus::dispatch($payment->id)->delay(now()->addSeconds(30));

            return response()->json([
                'status' => 'pending',
                'payment_id' => $payment->id,
                'amount' => (float) $amount,
                'message' => 'STK push imetumwa kwenye simu yako',
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to initiate payment: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update location for order delivery tracking.
     */
    public function updateLocation(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|integer|exists:orders,id',
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
            ]);

            $order = Order::findOrFail($validated['order_id']);

            // Store location in notes or create a separate location field
            // For now, we'll append to delivery_address
            $order->delivery_address = $order->delivery_address.' | Lat: '.$validated['lat'].', Lng: '.$validated['lng'];
            $order->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Location updated',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update location: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Resolve user by whatsapp_jid or phone number - check if exists, don't auto-create.
     */
    public function resolveUser(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'phone_number' => 'required|string|max:30',
                'whatsapp_jid' => 'nullable|string|max:50',
            ]);

            $rawPhone = $validated['phone_number'];
            $jid = $validated['whatsapp_jid'] ?? null;

            // 1) Try finding user by whatsapp_jid first (most reliable for returning bot users)
            $user = null;
            if ($jid) {
                $user = User::where('whatsapp_jid', $jid)->first();
            }

            // 2) If not found by JID, try formatted phone number
            if (! $user) {
                $phoneNumber = preg_replace('/[^0-9]/', '', $rawPhone);
                if (str_starts_with($phoneNumber, '255')) {
                    $phoneNumber = '0'.substr($phoneNumber, 3);
                } elseif (str_starts_with($phoneNumber, '0') && strlen($phoneNumber) >= 10 && strlen($phoneNumber) <= 13) {
                    // Already in 07xx format, keep as-is
                } elseif (strlen($phoneNumber) >= 9 && strlen($phoneNumber) <= 10) {
                    $phoneNumber = '0'.$phoneNumber;
                }
                // Only search by phone if it looks like a valid Tanzanian number
                if (strlen($phoneNumber) >= 10 && strlen($phoneNumber) <= 13) {
                    $user = User::where('phone', $phoneNumber)->first();
                }
            }

            if (! $user) {
                return response()->json([
                    'status' => 'not_registered',
                    'message' => 'Namba hii haijasajiliwa. Tafadhali jisajili kwanza.',
                ]);
            }

            // Update whatsapp_jid if not set yet (web user coming to WhatsApp)
            if ($jid && ! $user->whatsapp_jid) {
                $user->update(['whatsapp_jid' => $jid]);
            }

            return response()->json([
                'status' => 'registered',
                'user_id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'address' => $user->address,
                'source' => $user->source ?? 'web',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to resolve user: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Register a new user through bot.
     * Email is optional (auto-generated if not provided).
     * Password is generated and returned so bot can send it to user for web login.
     */
    public function registerUser(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:1000',
                'email' => 'nullable|string|email|max:255|unique:users,email',
                'whatsapp_jid' => 'nullable|string|max:50',
            ], [
                'email.unique' => 'Barua pepe hii tayari imetumika.',
                'name.required' => 'Jina linahitajika.',
                'phone.required' => 'Namba ya simu inahitajika.',
                'address.required' => 'Eneo linahitajika.',
            ]);

            DB::beginTransaction();

            // Format phone number (Tanzanian)
            $phoneNumber = preg_replace('/[^0-9]/', '', $validated['phone']);
            if (str_starts_with($phoneNumber, '255')) {
                $phoneNumber = '0'.substr($phoneNumber, 3);
            } elseif (str_starts_with($phoneNumber, '+255')) {
                $phoneNumber = '0'.substr(preg_replace('/[^0-9]/', '', $phoneNumber), 3);
            } elseif (! str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '0'.$phoneNumber;
            }

            // Check if user with phone already exists
            $existingUser = User::where('phone', $phoneNumber)->first();
            if ($existingUser) {
                DB::rollBack();

                // Auto-link whatsapp_jid if not set yet
                if (! empty($validated['whatsapp_jid']) && ! $existingUser->whatsapp_jid) {
                    $existingUser->update(['whatsapp_jid' => $validated['whatsapp_jid']]);
                }

                return response()->json([
                    'status' => 'already_exists',
                    'message' => 'Namba hii tayari imesajiliwa.',
                    'user_id' => $existingUser->id,
                    'name' => $existingUser->name,
                    'phone' => $existingUser->phone,
                    'address' => $existingUser->address,
                ], 200);
            }

            // Generate a readable password for web login (e.g., "monana7842")
            $plainPassword = 'monana'.rand(1000, 9999);

            // Auto-generate email if not provided
            $email = $validated['email'] ?? 'bot_'.$phoneNumber.'@monana.local';

            // Create new user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $email,
                'phone' => $phoneNumber,
                'whatsapp_jid' => $validated['whatsapp_jid'] ?? null,
                'address' => $validated['address'],
                'password' => bcrypt($plainPassword),
                'source' => 'whatsapp',
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Akaunti yako imeundwa kwa mafanikio!',
                'user_id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'address' => $user->address,
                'web_login' => [
                    'phone' => $phoneNumber,
                    'password' => $plainPassword,
                    'note' => 'Tumia namba ya simu na password hii kuingia kwenye website yetu.',
                ],
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Taarifa hazikukamilika.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Imeshindwa kusajili: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get combined user status - active orders + subscriptions dashboard.
     */
    public function getUserStatus(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }

        try {
            // Active Cyber orders (not delivered/cancelled)
            $cyberOrders = $user->cyberOrders()
                ->whereNotIn('status', ['delivered', 'cancelled'])
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($order) => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number ?? 'CYB-'.$order->id,
                    'status' => $order->status,
                    'total_amount' => (float) ($order->total_amount ?? 0),
                    'created_at' => $order->created_at?->format('Y-m-d H:i') ?? '',
                ]);

            // Active Food orders (not delivered/cancelled)
            $foodOrders = $user->foodOrders()
                ->whereNotIn('status', ['delivered', 'cancelled'])
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($order) => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number ?? 'FD-'.$order->id,
                    'status' => $order->status,
                    'total_amount' => (float) ($order->total_amount ?? 0),
                    'created_at' => $order->created_at?->format('Y-m-d H:i') ?? '',
                ]);

            // Subscriptions (active + recently expired/cancelled)
            $subscriptions = $user->foodSubscriptions()
                ->where(function ($q) {
                    $q->whereIn('status', ['active', 'paused', 'pending'])
                      ->orWhere(function ($q2) {
                          $q2->whereIn('status', ['expired', 'cancelled'])
                             ->where('updated_at', '>=', now()->subDays(30));
                      });
                })
                ->with(['package', 'payments' => fn ($q) => $q->latest()->limit(1)])
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($sub) => [
                    'subscription_id' => $sub->id,
                    'package_name' => $sub->package?->name ?? 'Kifurushi',
                    'status' => $sub->status,
                    'start_date' => $sub->start_date?->toDateString() ?? '',
                    'end_date' => $sub->end_date?->toDateString() ?? '',
                    'days_remaining' => $sub->end_date ? max(0, (int) now()->diffInDays($sub->end_date, false)) : 0,
                    'delivery_address' => $sub->delivery_address ?? '',
                    'payment_status' => $sub->payments->first()?->status ?? 'unpaid',
                    'expired_at' => $sub->expired_at?->toDateString() ?? null,
                ]);

            return response()->json([
                'status' => 'success',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'address' => $user->address,
                ],
                'cyber_orders' => $cyberOrders,
                'food_orders' => $foodOrders,
                'subscriptions' => $subscriptions,
                'summary' => [
                    'active_cyber_orders' => $cyberOrders->count(),
                    'active_food_orders' => $foodOrders->count(),
                    'active_subscriptions' => $subscriptions->count(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Imeshindwa kupata taarifa: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user profile by ID.
     */
    public function getUser(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'total_orders' => $user->orders()->count(),
                'total_subscriptions' => $user->subscriptions()->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }
    }

    /**
     * Update user profile.
     */
    public function updateUser(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:users,email,'.$id,
                'phone' => 'nullable|string|max:20|unique:users,phone,'.$id,
                'address' => 'nullable|string|max:1000',
            ]);

            $user = User::findOrFail($id);

            // Format phone number if provided
            if (! empty($validated['phone'])) {
                $phoneNumber = preg_replace('/[^0-9]/', '', $validated['phone']);
                if (str_starts_with($phoneNumber, '255')) {
                    $phoneNumber = '0'.substr($phoneNumber, 3);
                } elseif (! str_starts_with($phoneNumber, '0')) {
                    $phoneNumber = '0'.$phoneNumber;
                }
                $validated['phone'] = $phoneNumber;
            }

            // Update only provided fields
            $user->fill(array_filter($validated, fn ($value) => ! is_null($value)));
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'User profile imesasishwa',
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update user: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel an order.
     */
    public function cancelOrder(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|integer|exists:orders,id',
                'reason' => 'nullable|string|max:500',
            ]);

            $order = Order::findOrFail($validated['order_id']);

            // Only allow cancellation if order is pending or approved
            if (! in_array($order->status, ['pending', 'approved'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order cannot be cancelled. Current status: '.$order->status,
                ], 422);
            }

            $order->status = 'cancelled';
            if (! empty($validated['reason'])) {
                $order->notes = ($order->notes ? $order->notes."\n\n" : '').'Cancellation reason: '.$validated['reason'];
            }
            $order->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Order imeghairiwa',
                'order_id' => $order->id,
                'order_status' => $order->status,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to cancel order: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get order delivery information.
     */
    public function getOrderDelivery(int $id): JsonResponse
    {
        try {
            $order = Order::with('assignedUser')->findOrFail($id);

            return response()->json([
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'delivery_address' => $order->delivery_address,
                'assigned_to' => $order->assignedUser ? [
                    'id' => $order->assignedUser->id,
                    'name' => $order->assignedUser->name,
                    'phone' => $order->assignedUser->phone,
                ] : null,
                'delivered_at' => $order->delivered_at ? $order->delivered_at->format('Y-m-d H:i:s') : null,
                'delivery_status' => $order->status === 'delivered' ? 'delivered' : ($order->assigned_to ? 'in_progress' : 'pending'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }
    }

    /**
     * Pause a subscription.
     */
    public function pauseSubscription(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'subscription_id' => 'required|integer|exists:subscriptions,id',
                'reason' => 'nullable|string|max:500',
            ]);

            $subscription = Subscription::findOrFail($validated['subscription_id']);

            if ($subscription->status !== 'active') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Subscription can only be paused if it is active. Current status: '.$subscription->status,
                ], 422);
            }

            $subscription->status = 'paused';
            if (! empty($validated['reason'])) {
                $subscription->notes = ($subscription->notes ? $subscription->notes."\n\n" : '').'Pause reason: '.$validated['reason'];
            }
            $subscription->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Subscription imesimamishwa',
                'subscription_id' => $subscription->id,
                'subscription_status' => $subscription->status,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to pause subscription: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Resume a paused subscription.
     */
    public function resumeSubscription(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'subscription_id' => 'required|integer|exists:subscriptions,id',
            ]);

            $subscription = Subscription::findOrFail($validated['subscription_id']);

            if ($subscription->status !== 'paused') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Subscription can only be resumed if it is paused. Current status: '.$subscription->status,
                ], 422);
            }

            $subscription->status = 'active';
            $subscription->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Subscription imerejea',
                'subscription_id' => $subscription->id,
                'subscription_status' => $subscription->status,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to resume subscription: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upgrade a subscription to a different package.
     */
    public function upgradeSubscription(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'subscription_id' => 'required|integer|exists:subscriptions,id',
                'package_id' => 'required|integer|exists:subscription_packages,id',
            ]);

            DB::beginTransaction();

            $subscription = Subscription::with('subscriptionPackage')->findOrFail($validated['subscription_id']);
            $newPackage = SubscriptionPackage::where('is_active', true)->findOrFail($validated['package_id']);

            if ($subscription->status !== 'active' && $subscription->status !== 'paused') {
                DB::rollBack();

                return response()->json([
                    'status' => 'error',
                    'message' => 'Subscription can only be upgraded if it is active or paused. Current status: '.$subscription->status,
                ], 422);
            }

            $oldPackage = $subscription->subscriptionPackage;
            $subscription->subscription_package_id = $newPackage->id;

            // Recalculate end date based on remaining days
            $remainingDays = max(0, now()->diffInDays($subscription->end_date, false));
            $newEndDate = match ($newPackage->duration_type) {
                'weekly' => now()->addDays(min($remainingDays, 7)),
                'monthly' => now()->addDays(min($remainingDays, 30)),
                default => now()->addDays(min($remainingDays, 7)),
            };

            $subscription->end_date = $newEndDate;
            $subscription->delivery_schedule = $this->generateDeliverySchedule(now(), $newEndDate, $newPackage->meals_per_week, $newPackage->delivery_days);
            $subscription->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Subscription imesasishwa',
                'subscription_id' => $subscription->id,
                'old_package' => $oldPackage->name,
                'new_package' => $newPackage->name,
                'new_end_date' => $subscription->end_date->format('Y-m-d'),
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upgrade subscription: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get payment status by ID.
     */
    public function getPayment(int $id): JsonResponse
    {
        try {
            $payment = Payment::with(['order', 'subscription'])->findOrFail($id);

            return response()->json([
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'amount' => (float) $payment->amount,
                'payment_method' => $payment->payment_method,
                'transaction_id' => $payment->transaction_id,
                'phone_number' => $payment->phone_number,
                'order_id' => $payment->order_id,
                'subscription_id' => $payment->subscription_id,
                'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $payment->updated_at->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found',
            ], 404);
        }
    }

    /**
     * Get order history for a user.
     */
    public function getOrderHistory(int $userId): JsonResponse
    {
        try {
            $user = User::findOrFail($userId);

            $orders = Order::where('user_id', $userId)
                ->with('orderItems.orderable')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($order) {
                    $items = $order->orderItems->map(function ($item) {
                        return [
                            'name' => $item->orderable->name ?? 'Unknown',
                            'qty' => $item->quantity,
                            'price' => (float) $item->price,
                        ];
                    });

                    return [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'status' => $order->status,
                        'total_amount' => (float) $order->total_amount,
                        'items' => $items,
                        'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    ];
                });

            return response()->json([
                'status' => 'success',
                'user_id' => $userId,
                'orders' => $orders,
                'total_orders' => $orders->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }
    }

    /**
     * Get subscription history for a user.
     */
    public function getSubscriptionHistory(int $userId): JsonResponse
    {
        try {
            $user = User::findOrFail($userId);

            $subscriptions = Subscription::where('user_id', $userId)
                ->with('subscriptionPackage')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($subscription) {
                    $payment = $subscription->payments()->latest()->first();

                    return [
                        'subscription_id' => $subscription->id,
                        'status' => $subscription->status,
                        'package_name' => $subscription->subscriptionPackage->name,
                        'package_price' => (float) $subscription->subscriptionPackage->price,
                        'start_date' => $subscription->start_date->format('Y-m-d'),
                        'end_date' => $subscription->end_date->format('Y-m-d'),
                        'payment_status' => $payment ? $payment->status : 'pending',
                        'created_at' => $subscription->created_at->format('Y-m-d H:i:s'),
                    ];
                });

            return response()->json([
                'status' => 'success',
                'user_id' => $userId,
                'subscriptions' => $subscriptions,
                'total_subscriptions' => $subscriptions->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }
    }

    /**
     * Get notifications for a user.
     */
    public function getNotifications(int $userId): JsonResponse
    {
        try {
            $user = User::findOrFail($userId);

            $notifications = Notification::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'is_read' => $notification->is_read,
                        'order_id' => $notification->order_id,
                        'subscription_id' => $notification->subscription_id,
                        'payment_id' => $notification->payment_id,
                        'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                    ];
                });

            $unreadCount = Notification::where('user_id', $userId)
                ->where('is_read', false)
                ->count();

            return response()->json([
                'status' => 'success',
                'user_id' => $userId,
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
                'total' => $notifications->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }
    }

    /**
     * Acknowledge (mark as read) a notification.
     */
    public function acknowledgeNotification(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'notification_id' => 'nullable|integer|exists:notifications,id',
                'user_id' => 'nullable|integer|exists:users,id',
                'mark_all' => 'nullable|boolean',
            ]);

            DB::beginTransaction();

            if (! empty($validated['mark_all']) && ! empty($validated['user_id'])) {
                // Mark all notifications as read for user
                Notification::where('user_id', $validated['user_id'])
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true,
                        'read_at' => now(),
                    ]);

                $count = Notification::where('user_id', $validated['user_id'])
                    ->where('is_read', false)
                    ->count();

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'All notifications zimesomwa',
                    'user_id' => $validated['user_id'],
                ]);
            } elseif (! empty($validated['notification_id'])) {
                // Mark single notification as read
                $notification = Notification::findOrFail($validated['notification_id']);
                $notification->is_read = true;
                $notification->read_at = now();
                $notification->save();

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Notification imesomwa',
                    'notification_id' => $notification->id,
                ]);
            } else {
                DB::rollBack();

                return response()->json([
                    'status' => 'error',
                    'message' => 'Either notification_id or user_id with mark_all is required',
                ], 422);
            }
        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to acknowledge notification: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get system health status.
     */
    public function getSystemHealth(): JsonResponse
    {
        try {
            $health = [
                'status' => 'healthy',
                'database' => false,
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ];

            // Check database connection
            try {
                DB::connection()->getPdo();
                $health['database'] = true;
            } catch (\Exception $e) {
                $health['status'] = 'unhealthy';
                $health['database'] = false;
                $health['database_error'] = $e->getMessage();
            }

            return response()->json($health, $health['status'] === 'healthy' ? 200 : 503);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'unhealthy',
                'message' => 'Health check failed: '.$e->getMessage(),
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ], 503);
        }
    }

    /**
     * Get bot configuration.
     */
    public function getBotConfig(): JsonResponse
    {
        try {
            return response()->json([
                'status' => 'success',
                'currency' => 'TZS',
                'currency_symbol' => 'TSh',
                'payment_methods' => ['mpesa', 'tigopesa', 'airtelmoney'],
                'supported_languages' => ['sw', 'en'],
                'delivery_radius' => 50, // km
                'min_order_amount' => 0,
                'version' => '1.0.0',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get config: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate delivery schedule for subscription.
     */
    private function generateDeliverySchedule($startDate, $endDate, $mealsPerWeek, $deliveryDays): array
    {
        $schedule = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            if (in_array($currentDate->dayOfWeek, $deliveryDays ?? [1, 2, 3, 4, 5])) {
                $schedule[] = $currentDate->format('Y-m-d');
            }
            $currentDate->addDay();
        }

        return $schedule;
    }
}
