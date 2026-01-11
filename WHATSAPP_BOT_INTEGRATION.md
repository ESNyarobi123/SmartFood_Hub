# Muunganisho wa WhatsApp Bot - Muhtasari wa Haraka

## URLs za API

### Base URL
```
{APP_URL}/api
```

### Public APIs (Hazihitaji Token)
- `GET /api/foods` - Pata vyakula
- `GET /api/products` - Pata bidhaa za jikoni
- `GET /api/subscription-packages` - Pata vifurushi vya usajili

### Bot APIs (Zinahitaji Token)
- `POST /api/bot/order` - Tengeneza order
- `GET /api/bot/order/{id}` - Pata maelezo ya order
- `POST /api/bot/subscription` - Tengeneza usajili
- `GET /api/bot/subscription/{id}` - Pata maelezo ya usajili
- `POST /api/bot/payment/initiate` - Anzisha malipo
- `POST /api/bot/location` - Sasisha eneo

### Callback API
- `POST /api/payment/callback` - Callback kutoka payment gateway

---

## Authentication

**Header:**
```
Authorization: Bearer YOUR_BOT_TOKEN
```

**Token inapatikana:** Admin Panel > Settings > `bot_super_token`

---

## Mifano ya Requests

### 1. Tengeneza Order
```json
POST /api/bot/order
{
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
}
```

### 2. Anzisha Malipo
```json
POST /api/bot/payment/initiate
{
  "user_id": 1,
  "type": "order",
  "id": 123,
  "method": "mpesa",
  "phone_number": "0712345678"
}
```

### 3. Tengeneza Usajili
```json
POST /api/bot/subscription
{
  "user_id": 1,
  "package_id": 1,
  "notes": "Maelezo maalum"
}
```

---

## Payment Callback

**URL:** `{APP_URL}/api/payment/callback`

**Request:**
```json
{
  "payment_id": 789,
  "status": "paid",
  "reference": "TXN123456789"
}
```

**Response:**
```json
{
  "status": "success",
  "payment_id": 789,
  "payment_status": "paid",
  "order_status": "approved",
  "subscription_status": null
}
```

---

## Status Codes

- `200` - Mafanikio
- `201` - Imeundwa
- `401` - Token si sahihi
- `404` - Haijapatikana
- `422` - Validation error
- `500` - Server error

---

## Workflow ya Kawaida

1. **Pata data:** Tumia Public APIs kwa orodha ya vyakula/bidhaa
2. **Tengeneza order/subscription:** Tumia Bot APIs
3. **Anzisha malipo:** Tumia payment initiate API
4. **Angalia status:** Polling au subiri callback

---

**Kwa maelezo kamili, angalia:** `API_DOCUMENTATION.md`
