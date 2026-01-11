# SmartFood Hub - Complete Bot API Documentation

## Overview

This is the **COMPLETE** and **FINAL** API documentation for WhatsApp Bot integration with SmartFood Hub Laravel System. All APIs are implemented and tested.

**Base URL:** `https://food.hosting.hollyn.online/api`  
**Example:** `

---

## Authentication

### Bot Authentication (Required for Protected APIs)

All bot APIs require authentication using Bearer token:

```
Authorization: Bearer YOUR_BOT_TOKEN
```

**Token Location:** Admin Panel > Settings > `bot_super_token`

---

## 1. PUBLIC APIs (No Authentication Required)

### 1.1. Get Foods
**Endpoint:** `GET /api/foods`

**Description:** Get list of available foods (food items available today)

**Response:**
```json
[
  {
    "id": 1,
    "name": "Pilau",
    "price": 5000.00,
    "available_today": true
  }
]
```

---

### 1.2. Get Products
**Endpoint:** `GET /api/products`

**Description:** Get list of available kitchen products

**Response:**
```json
[
  {
    "id": 1,
    "name": "Mchele 1kg",
    "price": 3000.00,
    "stock": 999
  }
]
```

---

### 1.3. Get Subscription Packages
**Endpoint:** `GET /api/subscription-packages`

**Description:** Get list of available subscription packages

**Response:**
```json
[
  {
    "id": 1,
    "name": "Weekly Package",
    "items": ["Mchele 1kg", "Unga 1kg", "Mayai 5"],
    "price": 15000.00,
    "duration_type": "weekly",
    "meals_per_week": 7,
    "delivery_days": [1, 2, 3, 4, 5]
  }
]
```

---

## 2. BOT APIs (Authentication Required)

### 2.1. User & Session

#### 2.1.1. Resolve User by Phone Number
**Endpoint:** `POST /api/bot/user/resolve`

**Description:** Create or get user by phone number

**Request:**
```json
{
  "phone_number": "0712345678",
  "name": "John Doe"
}
```

**Response (200):**
```json
{
  "status": "success",
  "user_id": 1,
  "name": "John Doe",
  "phone": "0712345678",
  "email": "user_xxx@smartfoodhub.local",
  "created": true
}
```

---

### 2.2. Orders

#### 2.2.1. Create Order
**Endpoint:** `POST /api/bot/order`

**Request:**
```json
{
  "user_id": 1,
  "items": [
    {
      "type": "food",
      "id": 1,
      "qty": 2,
      "notes": "Extra spicy"
    },
    {
      "type": "product",
      "id": 3,
      "qty": 1
    }
  ],
  "location": "Mikocheni, Dar es Salaam",
  "custom_notes": "Please deliver early"
}
```

**Response (201):**
```json
{
  "status": "success",
  "order_id": 123,
  "message": "Order imepokelewa"
}
```

---

#### 2.2.2. Get Order
**Endpoint:** `GET /api/bot/order/{id}`

**Response (200):**
```json
{
  "order_id": 123,
  "order_number": "ORD-2024-001",
  "status": "pending",
  "items": [
    {
      "name": "Pilau",
      "qty": 2,
      "price": 5000.00
    }
  ],
  "total_amount": 10000.00,
  "delivery_status": "pending",
  "location": "Mikocheni, Dar es Salaam",
  "assigned_to": null
}
```

---

#### 2.2.3. Cancel Order
**Endpoint:** `POST /api/bot/order/cancel`

**Request:**
```json
{
  "order_id": 123,
  "reason": "Changed mind"
}
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Order imeghairiwa",
  "order_id": 123,
  "order_status": "cancelled"
}
```

**Note:** Only orders with status `pending` or `approved` can be cancelled.

---

#### 2.2.4. Get Order Delivery Info
**Endpoint:** `GET /api/bot/order/{id}/delivery`

**Response (200):**
```json
{
  "order_id": 123,
  "order_number": "ORD-2024-001",
  "status": "ready",
  "delivery_address": "Mikocheni, Dar es Salaam",
  "assigned_to": {
    "id": 5,
    "name": "Delivery Rider",
    "phone": "0755555555"
  },
  "delivered_at": null,
  "delivery_status": "in_progress"
}
```

---

#### 2.2.5. Update Location
**Endpoint:** `POST /api/bot/location`

**Request:**
```json
{
  "order_id": 123,
  "lat": -6.7924,
  "lng": 39.2083
}
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Location updated"
}
```

---

### 2.3. Subscriptions

#### 2.3.1. Create Subscription
**Endpoint:** `POST /api/bot/subscription`

**Request:**
```json
{
  "user_id": 1,
  "package_id": 1,
  "notes": "Please deliver on Mondays and Tuesdays only"
}
```

**Response (201):**
```json
{
  "status": "success",
  "subscription_id": 45,
  "start_date": "2024-01-15",
  "end_date": "2024-01-22",
  "payment_status": "pending"
}
```

---

#### 2.3.2. Get Subscription
**Endpoint:** `GET /api/bot/subscription/{id}`

**Response (200):**
```json
{
  "subscription_id": 45,
  "status": "active",
  "start_date": "2024-01-15",
  "end_date": "2024-01-22",
  "payment_status": "paid",
  "package_name": "Weekly Package",
  "package_price": 15000.00
}
```

---

#### 2.3.3. Pause Subscription
**Endpoint:** `POST /api/bot/subscription/pause`

**Request:**
```json
{
  "subscription_id": 45,
  "reason": "Going on vacation"
}
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Subscription imesimamishwa",
  "subscription_id": 45,
  "subscription_status": "paused"
}
```

**Note:** Only `active` subscriptions can be paused.

---

#### 2.3.4. Resume Subscription
**Endpoint:** `POST /api/bot/subscription/resume`

**Request:**
```json
{
  "subscription_id": 45
}
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Subscription imerejea",
  "subscription_id": 45,
  "subscription_status": "active"
}
```

**Note:** Only `paused` subscriptions can be resumed.

---

#### 2.3.5. Upgrade Subscription
**Endpoint:** `POST /api/bot/subscription/upgrade`

**Request:**
```json
{
  "subscription_id": 45,
  "package_id": 2
}
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Subscription imesasishwa",
  "subscription_id": 45,
  "old_package": "Weekly Package",
  "new_package": "Monthly Package",
  "new_end_date": "2024-02-15"
}
```

**Note:** Only `active` or `paused` subscriptions can be upgraded.

---

### 2.4. Payments

#### 2.4.1. Initiate Payment
**Endpoint:** `POST /api/bot/payment/initiate`

**Request:**
```json
{
  "user_id": 1,
  "type": "order",
  "id": 123,
  "method": "mpesa",
  "phone_number": "0712345678"
}
```

**Response (200):**
```json
{
  "status": "pending",
  "payment_id": 789,
  "message": "Lipia STK push imetumwa"
}
```

**Payment Methods:** `mpesa`, `tigopesa`, `airtelmoney`  
**Type:** `order` or `subscription`

---

#### 2.4.2. Get Payment Status
**Endpoint:** `GET /api/bot/payment/{id}`

**Response (200):**
```json
{
  "payment_id": 789,
  "status": "paid",
  "amount": 10000.00,
  "payment_method": "mobile_money",
  "transaction_id": "TXN123456789",
  "phone_number": "0712345678",
  "order_id": 123,
  "subscription_id": null,
  "created_at": "2024-01-15 10:30:00",
  "updated_at": "2024-01-15 10:35:00"
}
```

**Status Values:** `pending`, `paid`, `failed`, `cancelled`

---

### 2.5. History & Tracking

#### 2.5.1. Get Order History
**Endpoint:** `GET /api/bot/orders/history/{user_id}`

**Response (200):**
```json
{
  "status": "success",
  "user_id": 1,
  "orders": [
    {
      "order_id": 123,
      "order_number": "ORD-2024-001",
      "status": "delivered",
      "total_amount": 10000.00,
      "items": [
        {
          "name": "Pilau",
          "qty": 2,
          "price": 5000.00
        }
      ],
      "created_at": "2024-01-15 10:30:00"
    }
  ],
  "total_orders": 1
}
```

---

#### 2.5.2. Get Subscription History
**Endpoint:** `GET /api/bot/subscriptions/history/{user_id}`

**Response (200):**
```json
{
  "status": "success",
  "user_id": 1,
  "subscriptions": [
    {
      "subscription_id": 45,
      "status": "active",
      "package_name": "Weekly Package",
      "package_price": 15000.00,
      "start_date": "2024-01-15",
      "end_date": "2024-01-22",
      "payment_status": "paid",
      "created_at": "2024-01-15 10:30:00"
    }
  ],
  "total_subscriptions": 1
}
```

---

### 2.6. Notifications

#### 2.6.1. Get Notifications
**Endpoint:** `GET /api/bot/notifications/{user_id}`

**Response (200):**
```json
{
  "status": "success",
  "user_id": 1,
  "notifications": [
    {
      "id": 1,
      "type": "order",
      "title": "Order Status Update",
      "message": "Your order #ORD-2024-001 is ready for delivery",
      "is_read": false,
      "order_id": 123,
      "subscription_id": null,
      "payment_id": null,
      "created_at": "2024-01-15 10:30:00"
    }
  ],
  "unread_count": 1,
  "total": 1
}
```

---

#### 2.6.2. Acknowledge Notification
**Endpoint:** `POST /api/bot/acknowledge`

**Request (Single):**
```json
{
  "notification_id": 1
}
```

**Request (Mark All):**
```json
{
  "user_id": 1,
  "mark_all": true
}
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Notification imesomwa",
  "notification_id": 1
}
```

---

### 2.7. System & Health

#### 2.7.1. System Health Check
**Endpoint:** `GET /api/bot/system/health`

**Response (200 - Healthy):**
```json
{
  "status": "healthy",
  "database": true,
  "timestamp": "2024-01-15 10:30:00"
}
```

**Response (503 - Unhealthy):**
```json
{
  "status": "unhealthy",
  "database": false,
  "database_error": "Connection failed",
  "timestamp": "2024-01-15 10:30:00"
}
```

---

#### 2.7.2. Get Bot Config
**Endpoint:** `GET /api/bot/config`

**Response (200):**
```json
{
  "status": "success",
  "currency": "TZS",
  "currency_symbol": "TSh",
  "payment_methods": ["mpesa", "tigopesa", "airtelmoney"],
  "supported_languages": ["sw", "en"],
  "delivery_radius": 50,
  "min_order_amount": 0,
  "version": "1.0.0"
}
```

---

## 3. PAYMENT CALLBACK (System Only)

### 3.1. Payment Callback
**Endpoint:** `POST /api/payment/callback`

**Description:** This endpoint is called by the payment gateway (ZenoPay) after payment completion. **No authentication required.**

**Request:**
```json
{
  "payment_id": 789,
  "status": "paid",
  "reference": "TXN123456789"
}
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Payment status updated",
  "payment_id": 789,
  "payment_status": "paid",
  "order_status": "approved",
  "subscription_status": null
}
```

**Status Values:** `paid`, `failed`, `cancelled`

---

## Error Handling

### Status Codes

- `200 OK` - Request successful
- `201 Created` - Resource created successfully
- `401 Unauthorized` - Invalid or missing bot token
- `404 Not Found` - Resource not found
- `422 Unprocessable Entity` - Validation errors
- `500 Internal Server Error` - Server error
- `503 Service Unavailable` - Service unavailable (health check)

### Error Response Format

```json
{
  "status": "error",
  "message": "Error message here",
  "errors": {
    "field_name": ["Error message for field"]
  }
}
```

---

## Order Status Values

- `pending` - Order received, awaiting payment
- `approved` - Payment received, order approved
- `preparing` - Order being prepared
- `ready` - Order ready for delivery
- `delivered` - Order delivered
- `cancelled` - Order cancelled
- `rejected` - Order rejected

---

## Subscription Status Values

- `pending` - Subscription created, awaiting payment
- `active` - Subscription active and running
- `paused` - Subscription paused by user
- `cancelled` - Subscription cancelled
- `expired` - Subscription expired

---

## Payment Status Values

- `pending` - Payment initiated, awaiting completion
- `paid` - Payment completed successfully
- `failed` - Payment failed
- `cancelled` - Payment cancelled

---

## Phone Number Format

Phone numbers are automatically normalized to Tanzania format: `07XXXXXXXX`

Accepted formats:
- `0712345678`
- `255712345678`
- `712345678`

---

## Notes

1. **Bot Token:** Get from Admin Panel > Settings > `bot_super_token`
2. **Payment Gateway:** Configure ZenoPay settings in admin panel
3. **Webhook URL:** Set payment gateway callback URL to: `{APP_URL}/api/payment/callback`
4. **User Creation:** Users are auto-created via `/api/bot/user/resolve` if not exists
5. **Notifications:** System creates notifications automatically for orders, subscriptions, and payments

---

## Complete API List

### Public APIs (3)
1. `GET /api/foods`
2. `GET /api/products`
3. `GET /api/subscription-packages`

### Bot APIs (19)
1. `POST /api/bot/user/resolve`
2. `POST /api/bot/order`
3. `GET /api/bot/order/{id}`
4. `POST /api/bot/order/cancel`
5. `GET /api/bot/order/{id}/delivery`
6. `POST /api/bot/location`
7. `POST /api/bot/subscription`
8. `GET /api/bot/subscription/{id}`
9. `POST /api/bot/subscription/pause`
10. `POST /api/bot/subscription/resume`
11. `POST /api/bot/subscription/upgrade`
12. `POST /api/bot/payment/initiate`
13. `GET /api/bot/payment/{id}`
14. `GET /api/bot/orders/history/{user_id}`
15. `GET /api/bot/subscriptions/history/{user_id}`
16. `GET /api/bot/notifications/{user_id}`
17. `POST /api/bot/acknowledge`
18. `GET /api/bot/system/health`
19. `GET /api/bot/config`

### System APIs (1)
1. `POST /api/payment/callback`

**Total: 23 APIs**

---

**Documentation Version:** 1.0.0  
**Last Updated:** 2024-01-15  
**Status:** ✅ Complete - All APIs Implemented
# Bot Database Usage - Jinsi Bot Inavyotumia Database Yetu

## Overview

WhatsApp Bot inaweza kutumia database yetu kwa ajili ya:
1. **Usajili wa Watumiaji** - Kuunda accounts mpya za watumiaji
2. **Usajili wa Subscription** - Kuunda subscriptions mpya
3. **Kuhifadhi Orders** - Kuunda na kuhifadhi orders
4. **Kuhifadhi Payments** - Kufuatilia malipo
5. **Notifications** - Kutuma na kuhifadhi notifications

---

## 1. USAJILI WA WATUMIAJI (User Registration)

Bot inaweza kusajili watumiaji wapya kwa njia mbili:

### A. Quick Registration (Resolve User)
**Endpoint:** `POST /api/bot/user/resolve`

**Use Case:** User anaanza mazungumzo na bot, bot inapata nambari ya simu na inaunda user haraka.

**Request:**
```json
{
  "phone_number": "0712345678",
  "name": "John Doe"
}
```

**Response:**
```json
{
  "status": "success",
  "user_id": 1,
  "name": "John Doe",
  "phone": "0712345678",
  "email": "user_xxx@smartfoodhub.local",
  "created": true
}
```

**Database Action:**
- Inatafuta user kwa nambari ya simu
- Ikiwa haipo, inaunda user mpya na:
  - Name (kutoka request au "User")
  - Email (auto-generated: `user_xxx@smartfoodhub.local`)
  - Phone (formatted)
  - Password (random, auto-generated)

---

### B. Full Registration
**Endpoint:** `POST /api/bot/user/register`

**Use Case:** User anataka kusajili kikamilifu na data zote (name, email, phone, address).

**Request:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "0712345678",
  "address": "Mikocheni, Dar es Salaam"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "User amesajiliwa kwa mafanikio",
  "user_id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "0712345678",
  "address": "Mikocheni, Dar es Salaam"
}
```

**Database Action:**
- Inaunda user mpya katika `users` table
- Ina-validate kuwa email na phone si tayari zimetumika
- Ina-hifadhi data zote kwenye database

**Validation:**
- Email lazima iwe unique
- Phone lazima iwe unique
- Name lazima iwe present

---

### C. Get User Profile
**Endpoint:** `GET /api/bot/user/{id}`

**Use Case:** Bot anataka kuangalia maelezo ya user.

**Response:**
```json
{
  "status": "success",
  "user_id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "0712345678",
  "address": "Mikocheni, Dar es Salaam",
  "total_orders": 5,
  "total_subscriptions": 2
}
```

**Database Action:**
- Inasoma data kutoka `users` table
- Inahesabu orders na subscriptions za user

---

### D. Update User Profile
**Endpoint:** `PUT /api/bot/user/{id}`

**Use Case:** User anataka kusasisha maelezo yake.

**Request:**
```json
{
  "name": "John Updated",
  "address": "New Address"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "User profile imesasishwa",
  "user_id": 1,
  "name": "John Updated",
  "email": "john@example.com",
  "phone": "0712345678",
  "address": "New Address"
}
```

**Database Action:**
- Inasasisha fields zilizotolewa katika `users` table
- Ina-validate kuwa email/phone si tayari zimetumika (kwa watumiaji wengine)

---

## 2. USAJILI WA SUBSCRIPTION

### Create Subscription
**Endpoint:** `POST /api/bot/subscription`

**Use Case:** User anataka kusajili kwa kifurushi cha chakula.

**Request:**
```json
{
  "user_id": 1,
  "package_id": 1,
  "notes": "Please deliver on Mondays only"
}
```

**Response:**
```json
{
  "status": "success",
  "subscription_id": 45,
  "start_date": "2024-01-15",
  "end_date": "2024-01-22",
  "payment_status": "pending"
}
```

**Database Actions:**
1. **Creates record in `subscriptions` table:**
   - `user_id` - ID ya user
   - `subscription_package_id` - ID ya package
   - `start_date` - Tarehe ya kuanza
   - `end_date` - Tarehe ya mwisho (calculated based on package type)
   - `status` - "pending" (awaiting payment)
   - `delivery_schedule` - JSON array ya tarehe za ufikiaji
   - `notes` - Maelezo maalum

2. **Calculates dates:**
   - Weekly package: +7 days
   - Monthly package: +30 days

3. **Generates delivery schedule:**
   - Based on `meals_per_week` na `delivery_days` from package

**Tables Used:**
- `subscriptions` - Main subscription record
- `subscription_packages` - Package details
- `users` - User information

---

## 3. ORDERS

### Create Order
**Endpoint:** `POST /api/bot/order`

**Database Actions:**
1. **Creates record in `orders` table:**
   - `user_id` - ID ya user
   - `order_number` - Auto-generated (ORD-xxx)
   - `total_amount` - Calculated from items
   - `status` - "pending"
   - `delivery_address` - Anwani ya kufikia
   - `source` - "whatsapp"

2. **Creates records in `order_items` table:**
   - For each item in order
   - Links to `food_items` or `kitchen_products` via polymorphic relationship

**Tables Used:**
- `orders` - Main order record
- `order_items` - Order items (polymorphic)
- `food_items` - Food products
- `kitchen_products` - Kitchen products
- `users` - User information

---

## 4. PAYMENTS

### Initiate Payment
**Endpoint:** `POST /api/bot/payment/initiate`

**Database Actions:**
1. **Creates record in `payments` table:**
   - `order_id` or `subscription_id` - Links to order/subscription
   - `amount` - Kiasi cha malipo
   - `payment_method` - "mobile_money"
   - `transaction_id` - UUID for payment gateway
   - `status` - "pending"
   - `phone_number` - Nambari ya simu ya malipo

2. **Updates related order/subscription:**
   - After payment callback, status inabadilishwa

**Tables Used:**
- `payments` - Payment records
- `orders` - Order information
- `subscriptions` - Subscription information

---

## 5. NOTIFICATIONS

### Automatic Notification Creation

System inaunda notifications automatically kwa:
- Order status changes
- Payment confirmations
- Subscription updates
- Delivery updates

**Database Actions:**
- **Creates record in `notifications` table:**
  - `user_id` - ID ya user
  - `type` - "order", "subscription", "payment", "delivery"
  - `title` - Kichwa cha notification
  - `message` - Ujumbe wa notification
  - `order_id`, `subscription_id`, `payment_id` - Links
  - `is_read` - false (default)
  - `read_at` - null (default)

**Tables Used:**
- `notifications` - Notification records
- `users` - User information

---

## 6. WORKFLOW YA USAJILI WA SUBSCRIPTION

### Step-by-Step Process:

1. **User anaanza mazungumzo na bot**
   ```
   Bot: "Karibu! Nambari yako ya simu ni?"
   User: "0712345678"
   ```

2. **Bot inatumia `/api/bot/user/resolve`**
   - Inatafuta au inaunda user
   - Inapata `user_id`

3. **Bot inaonyesha vifurushi**
   ```
   Bot: "Vifurushi vinavyopatikana:"
   ```
   - Inatumia `GET /api/subscription-packages`

4. **User anachagua kifurushi**
   ```
   User: "Nataka Weekly Package"
   ```

5. **Bot inaunda subscription**
   - Inatumia `POST /api/bot/subscription`
   - Database inaunda record katika `subscriptions` table
   - Status: "pending" (awaiting payment)

6. **Bot inaanzisha malipo**
   - Inatumia `POST /api/bot/payment/initiate`
   - Database inaunda record katika `payments` table
   - Status: "pending"

7. **Payment gateway inaitwa callback**
   - `POST /api/payment/callback`
   - Database inasasisha:
     - `payments.status` → "paid"
     - `subscriptions.status` → "active"
     - `orders.status` → "approved" (if order)

8. **Notification inaundwa**
   - System inaunda notification automatically
   - Database inaunda record katika `notifications` table

---

## 7. DATABASE TABLES USED BY BOT

### Primary Tables:
1. **`users`** - User accounts
   - Created via: `POST /api/bot/user/register` or `POST /api/bot/user/resolve`
   - Read via: `GET /api/bot/user/{id}`
   - Updated via: `PUT /api/bot/user/{id}`

2. **`subscriptions`** - Subscription records
   - Created via: `POST /api/bot/subscription`
   - Read via: `GET /api/bot/subscription/{id}`
   - Updated via: Pause/Resume/Upgrade endpoints

3. **`orders`** - Order records
   - Created via: `POST /api/bot/order`
   - Read via: `GET /api/bot/order/{id}`
   - Updated via: Cancel endpoint, Payment callback

4. **`payments`** - Payment records
   - Created via: `POST /api/bot/payment/initiate`
   - Read via: `GET /api/bot/payment/{id}`
   - Updated via: Payment callback

5. **`notifications`** - Notification records
   - Created via: System automatically
   - Read via: `GET /api/bot/notifications/{user_id}`
   - Updated via: `POST /api/bot/acknowledge`

### Supporting Tables:
- `subscription_packages` - Package definitions
- `food_items` - Food menu items
- `kitchen_products` - Kitchen products
- `order_items` - Order line items
- `subscription_deliveries` - Delivery schedule

---

## 8. DATA FLOW EXAMPLE

### Complete Subscription Flow:

```
1. User → Bot: "Nataka kusajili"
   ↓
2. Bot → API: POST /api/bot/user/resolve
   Database: Creates/gets user in `users` table
   ↓
3. Bot → API: GET /api/subscription-packages
   Database: Reads from `subscription_packages` table
   ↓
4. User → Bot: "Nataka Weekly Package"
   ↓
5. Bot → API: POST /api/bot/subscription
   Database: Creates record in `subscriptions` table
   ↓
6. Bot → API: POST /api/bot/payment/initiate
   Database: Creates record in `payments` table
   ↓
7. Payment Gateway → API: POST /api/payment/callback
   Database: Updates `payments.status` = "paid"
   Database: Updates `subscriptions.status` = "active"
   Database: Creates notification in `notifications` table
   ↓
8. Bot → API: GET /api/bot/notifications/{user_id}
   Database: Reads from `notifications` table
   ↓
9. Bot → User: "Usajili wako umekamilika!"
```

---

## 9. IMPORTANT NOTES

### ✅ Advantages:
1. **Single Source of Truth** - Data yote iko kwenye database moja
2. **Consistency** - Bot na Web app zote zina-access data sawa
3. **Real-time Updates** - Mabadiliko yanaonekana mara moja
4. **History Tracking** - Historia yote inahifadhiwa
5. **Notifications** - System inaweza kutuma notifications automatically

### ⚠️ Considerations:
1. **Data Validation** - Bot lazima i-validate data kabla ya ku-save
2. **Error Handling** - Lazima i-handle errors vizuri
3. **Security** - Token authentication inahitajika
4. **Phone Format** - Phone numbers zina-format automatically

---

## 10. API ENDPOINTS SUMMARY

### User Management:
- `POST /api/bot/user/resolve` - Quick user lookup/create
- `POST /api/bot/user/register` - Full user registration
- `GET /api/bot/user/{id}` - Get user profile
- `PUT /api/bot/user/{id}` - Update user profile

### Subscription:
- `POST /api/bot/subscription` - Create subscription
- `GET /api/bot/subscription/{id}` - Get subscription
- `POST /api/bot/subscription/pause` - Pause subscription
- `POST /api/bot/subscription/resume` - Resume subscription
- `POST /api/bot/subscription/upgrade` - Upgrade subscription

### Orders:
- `POST /api/bot/order` - Create order
- `GET /api/bot/order/{id}` - Get order
- `POST /api/bot/order/cancel` - Cancel order

### Payments:
- `POST /api/bot/payment/initiate` - Start payment
- `GET /api/bot/payment/{id}` - Get payment status

### History:
- `GET /api/bot/orders/history/{user_id}` - Order history
- `GET /api/bot/subscriptions/history/{user_id}` - Subscription history

### Notifications:
- `GET /api/bot/notifications/{user_id}` - Get notifications
- `POST /api/bot/acknowledge` - Mark as read

---

**Documentation Version:** 1.0.0  
**Last Updated:** 2024-01-15  
**Status:** ✅ Complete
