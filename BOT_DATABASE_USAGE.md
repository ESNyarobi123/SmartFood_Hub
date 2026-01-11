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
