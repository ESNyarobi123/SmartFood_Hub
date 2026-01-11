# SmartFood Hub - API Documentation ya WhatsApp Bot

## Maelezo ya Jumla

Hii ni hati kamili ya API zote za SmartFood Hub kwa ajili ya kuunganisha na WhatsApp Bot.

### Base URL
```
{APP_URL}/api
```
Mfano: `https://smartfoodhub.com/api` au `http://localhost:8000/api`

### Authentication

#### Public APIs (Hazihitaji Authentication)
- Hazihitaji token
- Zinapatikana kwa kila mtu

#### Bot APIs (Zinahitaji Authentication)
- Zinahitaji token ya bot
- Token inaweza kutuma kwa njia mbili:
  1. **Bearer Token** (inapendekezwa):
     ```
     Authorization: Bearer YOUR_BOT_TOKEN
     ```
  2. **Header moja kwa moja**:
     ```
     Authorization: YOUR_BOT_TOKEN
     ```

- Token inapatikana katika Settings ya admin panel kama `bot_super_token`

---

## 1. PUBLIC APIs (Hazihitaji Authentication)

### 1.1. Pata Orodha ya Vyakula (Foods)

**Endpoint:** `GET /api/foods`

**Maelezo:** Pata orodha ya vyakula vyote vinavyopatikana

**Headers:**
```
Content-Type: application/json
```

**Response (200 OK):**
```json
[
  {
    "id": 1,
    "name": "Pilau",
    "price": 5000.00,
    "available_today": true
  },
  {
    "id": 2,
    "name": "Ugali na Nyama",
    "price": 4000.00,
    "available_today": true
  }
]
```

**Mfano wa Request:**
```bash
curl -X GET "https://smartfoodhub.com/api/foods"
```

---

### 1.2. Pata Orodha ya Bidhaa za Jikoni (Kitchen Products)

**Endpoint:** `GET /api/products`

**Maelezo:** Pata orodha ya bidhaa zote za jikoni zinazopatikana

**Headers:**
```
Content-Type: application/json
```

**Response (200 OK):**
```json
[
  {
    "id": 1,
    "name": "Mchele 1kg",
    "price": 3000.00,
    "stock": 999
  },
  {
    "id": 2,
    "name": "Unga wa Ngano 1kg",
    "price": 2500.00,
    "stock": 999
  }
]
```

**Mfano wa Request:**
```bash
curl -X GET "https://smartfoodhub.com/api/products"
```

---

### 1.3. Pata Orodha ya Vifurushi vya Usajili (Subscription Packages)

**Endpoint:** `GET /api/subscription-packages`

**Maelezo:** Pata orodha ya vifurushi vyote vya usajili vinavyopatikana

**Headers:**
```
Content-Type: application/json
```

**Response (200 OK):**
```json
[
  {
    "id": 1,
    "name": "Weekly Package",
    "items": [
      "Mchele 1kg",
      "Unga 1kg",
      "Mayai 5"
    ],
    "price": 15000.00,
    "duration_type": "weekly",
    "meals_per_week": 7,
    "delivery_days": [1, 2, 3, 4, 5]
  },
  {
    "id": 2,
    "name": "Monthly Package",
    "items": [
      "Mchele 5kg",
      "Unga 4kg",
      "Mayai 20"
    ],
    "price": 50000.00,
    "duration_type": "monthly",
    "meals_per_week": 30,
    "delivery_days": [1, 2, 3, 4, 5]
  }
]
```

**Mfano wa Request:**
```bash
curl -X GET "https://smartfoodhub.com/api/subscription-packages"
```

---

## 2. BOT APIs (Zinahitaji Authentication)

### 2.1. Tengeneza Order Mpya

**Endpoint:** `POST /api/bot/order`

**Maelezo:** Tengeneza order mpya kutoka WhatsApp bot

**Headers:**
```
Content-Type: application/json
Authorization: Bearer YOUR_BOT_TOKEN
```

**Request Body:**
```json
{
  "user_id": 1,
  "items": [
    {
      "type": "food",
      "id": 1,
      "qty": 2,
      "notes": "Tafadhali ongeza pilipili"
    },
    {
      "type": "product",
      "id": 3,
      "qty": 1,
      "notes": null
    }
  ],
  "custom_notes": "Tafadhali fika mapema",
  "location": "Mikocheni, Dar es Salaam"
}
```

**Parameters:**
- `user_id` (required, integer): ID ya mtumiaji
- `items` (required, array): Orodha ya vitu vya kuagiza
  - `type` (required, string): Aina ya kitu - `"food"` au `"product"`
  - `id` (required, integer): ID ya chakula au bidhaa
  - `qty` (required, integer): Idadi (lazima iwe angalau 1)
  - `notes` (optional, string): Maelezo maalum kwa kitu hicho
- `custom_notes` (optional, string): Maelezo ya jumla kwa order
- `location` (required, string): Anwani ya kufikia

**Response (201 Created):**
```json
{
  "status": "success",
  "order_id": 123,
  "message": "Order imepokelewa"
}
```

**Response (422 Validation Error):**
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "user_id": ["The user id field is required."],
    "items": ["The items field is required."]
  }
}
```

**Response (500 Server Error):**
```json
{
  "status": "error",
  "message": "Failed to create order: [error details]"
}
```

**Mfano wa Request:**
```bash
curl -X POST "https://smartfoodhub.com/api/bot/order" \
  -H "Authorization: Bearer YOUR_BOT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "items": [
      {
        "type": "food",
        "id": 1,
        "qty": 2,
        "notes": "Tafadhali ongeza pilipili"
      }
    ],
    "location": "Mikocheni, Dar es Salaam"
  }'
```

---

### 2.2. Pata Maelezo ya Order

**Endpoint:** `GET /api/bot/order/{id}`

**Maelezo:** Pata maelezo kamili ya order kwa kutumia ID

**Headers:**
```
Authorization: Bearer YOUR_BOT_TOKEN
```

**URL Parameters:**
- `id` (required, integer): ID ya order

**Response (200 OK):**
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
    },
    {
      "name": "Mchele 1kg",
      "qty": 1,
      "price": 3000.00
    }
  ],
  "total_amount": 13000.00,
  "delivery_status": "pending",
  "location": "Mikocheni, Dar es Salaam",
  "assigned_to": null
}
```

**Response (404 Not Found):**
```json
{
  "status": "error",
  "message": "Order not found"
}
```

**Mfano wa Request:**
```bash
curl -X GET "https://smartfoodhub.com/api/bot/order/123" \
  -H "Authorization: Bearer YOUR_BOT_TOKEN"
```

---

### 2.3. Tengeneza Usajili Mpya (Subscription)

**Endpoint:** `POST /api/bot/subscription`

**Maelezo:** Tengeneza usajili mpya kutoka WhatsApp bot

**Headers:**
```
Content-Type: application/json
Authorization: Bearer YOUR_BOT_TOKEN
```

**Request Body:**
```json
{
  "user_id": 1,
  "package_id": 1,
  "custom_items": [
    "Mchele 2kg",
    "Unga 2kg"
  ],
  "notes": "Tafadhali fika Jumatatu na Jumanne tu"
}
```

**Parameters:**
- `user_id` (required, integer): ID ya mtumiaji
- `package_id` (required, integer): ID ya kifurushi cha usajili
- `custom_items` (optional, array): Orodha ya vitu maalum (kwa sasa haitumiki, lakini inaweza kutumika baadaye)
- `notes` (optional, string): Maelezo maalum

**Response (201 Created):**
```json
{
  "status": "success",
  "subscription_id": 45,
  "start_date": "2024-01-15",
  "end_date": "2024-01-22",
  "payment_status": "pending"
}
```

**Response (422 Validation Error):**
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "user_id": ["The user id field is required."],
    "package_id": ["The package id field is required."]
  }
}
```

**Response (500 Server Error):**
```json
{
  "status": "error",
  "message": "Failed to create subscription: [error details]"
}
```

**Mfano wa Request:**
```bash
curl -X POST "https://smartfoodhub.com/api/bot/subscription" \
  -H "Authorization: Bearer YOUR_BOT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "package_id": 1,
    "notes": "Tafadhali fika Jumatatu na Jumanne tu"
  }'
```

---

### 2.4. Pata Maelezo ya Usajili

**Endpoint:** `GET /api/bot/subscription/{id}`

**Maelezo:** Pata maelezo kamili ya usajili kwa kutumia ID

**Headers:**
```
Authorization: Bearer YOUR_BOT_TOKEN
```

**URL Parameters:**
- `id` (required, integer): ID ya usajili

**Response (200 OK):**
```json
{
  "subscription_id": 45,
  "status": "pending",
  "start_date": "2024-01-15",
  "end_date": "2024-01-22",
  "payment_status": "pending",
  "package_name": "Weekly Package",
  "package_price": 15000.00
}
```

**Response (404 Not Found):**
```json
{
  "status": "error",
  "message": "Subscription not found"
}
```

**Mfano wa Request:**
```bash
curl -X GET "https://smartfoodhub.com/api/bot/subscription/45" \
  -H "Authorization: Bearer YOUR_BOT_TOKEN"
```

---

### 2.5. Anzisha Malipo (Initiate Payment)

**Endpoint:** `POST /api/bot/payment/initiate`

**Maelezo:** Anzisha malipo kwa order au subscription

**Headers:**
```
Content-Type: application/json
Authorization: Bearer YOUR_BOT_TOKEN
```

**Request Body:**
```json
{
  "user_id": 1,
  "type": "order",
  "id": 123,
  "method": "mpesa",
  "phone_number": "0712345678"
}
```

**Parameters:**
- `user_id` (required, integer): ID ya mtumiaji
- `type` (required, string): Aina ya malipo - `"order"` au `"subscription"`
- `id` (required, integer): ID ya order au subscription
- `method` (required, string): Njia ya malipo - `"mpesa"`, `"tigopesa"`, au `"airtelmoney"`
- `phone_number` (optional, string): Nambari ya simu (ikiwa haitolewi, itatumia nambari ya mtumiaji)

**Response (200 OK):**
```json
{
  "status": "pending",
  "payment_id": 789,
  "message": "Lipia STK push imetumwa"
}
```

**Response (422 Validation Error):**
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "user_id": ["The user id field is required."],
    "phone_number": ["Phone number is required for payment"]
  }
}
```

**Response (500 Server Error):**
```json
{
  "status": "error",
  "message": "Payment gateway is not configured"
}
```

**Mfano wa Request:**
```bash
curl -X POST "https://smartfoodhub.com/api/bot/payment/initiate" \
  -H "Authorization: Bearer YOUR_BOT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "type": "order",
    "id": 123,
    "method": "mpesa",
    "phone_number": "0712345678"
  }'
```

**Maelezo:**
- System itatuma STK push kwa nambari ya simu iliyotolewa
- Payment status itaangaliwa baada ya sekunde 30
- Bot inaweza kuangalia status ya malipo kwa kutumia payment callback

---

### 2.6. Sasisha Eneo (Update Location)

**Endpoint:** `POST /api/bot/location`

**Maelezo:** Sasisha eneo la kufikia kwa order (kwa ajili ya tracking)

**Headers:**
```
Content-Type: application/json
Authorization: Bearer YOUR_BOT_TOKEN
```

**Request Body:**
```json
{
  "order_id": 123,
  "lat": -6.7924,
  "lng": 39.2083
}
```

**Parameters:**
- `order_id` (required, integer): ID ya order
- `lat` (required, numeric): Latitude ya eneo
- `lng` (required, numeric): Longitude ya eneo

**Response (200 OK):**
```json
{
  "status": "success",
  "message": "Location updated"
}
```

**Response (422 Validation Error):**
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "order_id": ["The order id field is required."],
    "lat": ["The lat field is required."]
  }
}
```

**Mfano wa Request:**
```bash
curl -X POST "https://smartfoodhub.com/api/bot/location" \
  -H "Authorization: Bearer YOUR_BOT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": 123,
    "lat": -6.7924,
    "lng": 39.2083
  }'
```

---

## 3. PAYMENT CALLBACK API

### 3.1. Payment Callback (Kutoka Payment Gateway)

**Endpoint:** `POST /api/payment/callback`

**Maelezo:** Endpoint hii inaitwa na payment gateway (ZenoPay) baada ya malipo kukamilika au kushindwa. **HAIHITAJI AUTHENTICATION** kwa sababu inaitwa na payment gateway.

**Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "payment_id": 789,
  "status": "paid",
  "reference": "TXN123456789"
}
```

**Parameters:**
- `payment_id` (required, integer): ID ya payment record katika database
- `status` (required, string): Hali ya malipo - `"paid"`, `"failed"`, au `"cancelled"`
- `reference` (optional, string): Nambari ya kumbukumbu ya malipo kutoka payment gateway

**Response (200 OK):**
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

**Response (500 Server Error):**
```json
{
  "status": "error",
  "message": "Failed to process payment callback: [error details]"
}
```

**Maelezo:**
- Endpoint hii inasasisha hali ya malipo katika database
- Ikiwa malipo yamekamilika (`paid`):
  - Order status inabadilishwa kutoka `pending` hadi `approved`
  - Subscription status inabadilishwa kutoka `pending` hadi `active`
- Ikiwa malipo yameshindwa au yameghairiwa (`failed` au `cancelled`):
  - Order status inabadilishwa hadi `cancelled`
  - Subscription status inabadilishwa hadi `cancelled`

**Mfano wa Request (kutoka Payment Gateway):**
```bash
curl -X POST "https://smartfoodhub.com/api/payment/callback" \
  -H "Content-Type: application/json" \
  -d '{
    "payment_id": 789,
    "status": "paid",
    "reference": "TXN123456789"
  }'
```

---

## 4. MAELEZO YA CALLBACKS KWA WHATSAPP BOT

### 4.1. Webhook URL ya Payment Gateway

**URL:** `{APP_URL}/api/payment/callback`

**Mfano:** `https://smartfoodhub.com/api/payment/callback`

**Jinsi ya Kuisetup:**
1. Nenda kwenye dashboard ya payment gateway (ZenoPay)
2. Pata sehemu ya Webhooks/Callbacks
3. Weka URL hapo juu
4. Hakikisha kuwa payment gateway inaweza kufikia URL hiyo

### 4.2. Jinsi Bot Inavyoweza Kufuatilia Malipo

Bot inaweza kufuatilia status ya malipo kwa njia mbili:

#### Njia 1: Kwa kutumia Payment Callback (Inapendekezwa)
- Payment gateway itaitwa callback endpoint baada ya malipo
- Bot inaweza kuangalia status ya order/subscription baada ya muda

#### Njia 2: Polling (Kusubiri na kuangalia mara kwa mara)
- Bot inaweza kuangalia status ya order/subscription kwa kutumia:
  - `GET /api/bot/order/{id}` - kwa orders
  - `GET /api/bot/subscription/{id}` - kwa subscriptions

**Mfano wa Polling:**
```javascript
// Pseudo code
async function checkPaymentStatus(orderId) {
  const response = await fetch(`/api/bot/order/${orderId}`, {
    headers: {
      'Authorization': `Bearer ${BOT_TOKEN}`
    }
  });
  
  const order = await response.json();
  
  if (order.status === 'approved') {
    // Malipo yamekamilika
    return 'paid';
  } else if (order.status === 'cancelled') {
    // Malipo yameshindwa
    return 'failed';
  } else {
    // Bado inasubiri
    return 'pending';
  }
}
```

---

## 5. ERROR HANDLING

### Status Codes

- `200 OK` - Request imefanikiwa
- `201 Created` - Resource imeundwa kwa mafanikio
- `401 Unauthorized` - Token si sahihi au haipo
- `404 Not Found` - Resource haijapatikana
- `422 Unprocessable Entity` - Validation errors
- `500 Internal Server Error` - Kosa la server

### Error Response Format

```json
{
  "status": "error",
  "message": "Error message hapa",
  "errors": {
    "field_name": ["Error message ya field"]
  }
}
```

---

## 6. MIFANO YA WORKFLOW

### Workflow 1: Kuagiza Chakula

1. **Pata orodha ya vyakula:**
   ```
   GET /api/foods
   ```

2. **Tengeneza order:**
   ```
   POST /api/bot/order
   {
     "user_id": 1,
     "items": [{"type": "food", "id": 1, "qty": 2}],
     "location": "Mikocheni, Dar es Salaam"
   }
   ```

3. **Anzisha malipo:**
   ```
   POST /api/bot/payment/initiate
   {
     "user_id": 1,
     "type": "order",
     "id": 123,
     "method": "mpesa",
     "phone_number": "0712345678"
   }
   ```

4. **Angalia status ya order (baada ya muda):**
   ```
   GET /api/bot/order/123
   ```

### Workflow 2: Kujisajili kwa Kifurushi

1. **Pata orodha ya vifurushi:**
   ```
   GET /api/subscription-packages
   ```

2. **Tengeneza usajili:**
   ```
   POST /api/bot/subscription
   {
     "user_id": 1,
     "package_id": 1
   }
   ```

3. **Anzisha malipo:**
   ```
   POST /api/bot/payment/initiate
   {
     "user_id": 1,
     "type": "subscription",
     "id": 45,
     "method": "mpesa",
     "phone_number": "0712345678"
   }
   ```

4. **Angalia status ya usajili:**
   ```
   GET /api/bot/subscription/45
   ```

---

## 7. NOTES ZA MUHIMU

1. **Token ya Bot:**
   - Token inapatikana katika admin panel > Settings > `bot_super_token`
   - Hakikisha token ni salama na haijachapishwa kwa wengine

2. **Phone Number Format:**
   - System inakubali nambari katika format yoyote
   - Inabadilishwa kiotomatiki kuwa `07XXXXXXXX`
   - Inaweza kukubali: `0712345678`, `255712345678`, `712345678`

3. **Order Status:**
   - `pending` - Order imepokelewa, bado haijalipwa
   - `approved` - Order imelipwa na imekubaliwa
   - `cancelled` - Order imeghairiwa au malipo yameshindwa

4. **Subscription Status:**
   - `pending` - Usajili umepokelewa, bado haujalipwa
   - `active` - Usajili umelipwa na unaendelea
   - `cancelled` - Usajili umeghairiwa au malipo yameshindwa

5. **Payment Status:**
   - `pending` - Malipo bado yanasubiri
   - `paid` - Malipo yamekamilika
   - `failed` - Malipo yameshindwa
   - `cancelled` - Malipo yameghairiwa

6. **Testing:**
   - Tumia Postman, cURL, au HTTP client yoyote kujaribu APIs
   - Hakikisha kuwa una token sahihi kabla ya kujaribu Bot APIs

---

## 8. SUPPORT

Kwa maswali au matatizo, tafadhali wasiliana na timu ya maendeleo.

---

**Tarehe ya Kusasishwa:** 2024-01-15
**Toleo:** 1.0
