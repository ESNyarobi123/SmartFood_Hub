# SmartFood Hub - Complete Bot API Documentation

## Overview

This is the **COMPLETE** and **FINAL** API documentation for WhatsApp Bot integration with SmartFood Hub Laravel System. All APIs are implemented and tested.

**Base URL:** `{APP_URL}/api`  
**Example:** `https://smartfoodhub.com/api` or `http://localhost:8000/api`

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
