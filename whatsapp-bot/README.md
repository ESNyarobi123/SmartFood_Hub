# 🤖 Monana WhatsApp Bot

WhatsApp bot for **Monana Platform** built with [Baileys v7](https://github.com/WhiskeySockets/Baileys). Connects to the Laravel backend via REST APIs.

## Features

- **Onboarding** — Auto-detect new vs returning users by phone number
- **Monana Food** 🍽️ — Browse meal slots, order food, pay via mobile money
- **Monana Market** 📦 — Subscribe to weekly/monthly packages, view products
- **Akaunti Yangu** 👤 — View active orders, subscriptions, account status
- **Payments** — M-Pesa, Tigo Pesa, Airtel Money (STK Push via ZenoPay)
- **Web Bridge** — Bot users get a password for web login; web users are auto-recognized on WhatsApp

## Architecture

```
whatsapp-bot/
├── src/
│   ├── index.js          # Baileys socket + connection management
│   ├── handler.js        # Main message router
│   ├── api.js            # Laravel API client (axios)
│   ├── config.js         # Configuration
│   ├── state.js          # Session state machine
│   ├── flows/
│   │   ├── onboarding.js # Registration + resolve user
│   │   ├── cyber.js      # Monana Food ordering flow
│   │   ├── market.js     # Monana Market subscriptions flow
│   │   └── account.js    # Account status flow
│   └── utils/
│       └── formatter.js  # WhatsApp message formatting
├── package.json
├── .env.example
└── README.md
```

## Setup

### Prerequisites
- Node.js 18+
- Laravel backend running (`php artisan serve`)
- `bot_super_token` set in Laravel settings table

### Install & Run

```bash
cd whatsapp-bot
npm install
npm start
```

1. Scan the QR code with your WhatsApp
2. Bot is now connected and ready

### Environment Variables

Copy `.env.example` and customize:

```bash
cp .env.example .env
```

| Variable | Default | Description |
|----------|---------|-------------|
| `API_BASE_URL` | `http://127.0.0.1:8000/api/bot` | Laravel API endpoint |
| `BOT_TOKEN` | `monana-bot-secret-2026` | Must match `bot_super_token` in Laravel |

## User Flow

```
User sends "Hi"
  ├── Returning user? → Main Menu
  └── New user? → Ask Name → Ask Address → Register → Show credentials → Main Menu

Main Menu
  ├── 1. Monana Food → Meal Slots → Menu → Cart → Payment
  ├── 2. Monana Market → Packages → Package Detail → Subscribe → Payment
  └── 3. Akaunti Yangu → Orders + Subscriptions status

Global Commands:
  • "menu" / "start" → Main Menu
  • "0" → Go back
  • "reset" → Clear session
```

## API Endpoints Used

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/user/resolve` | POST | Check if phone is registered |
| `/user/register` | POST | Register new user |
| `/user/{id}/status` | GET | Combined account dashboard |
| `/cyber/meal-slots` | GET | Available meal slots |
| `/cyber/menu` | GET | Menu items |
| `/cyber/order/create` | POST | Create cyber order |
| `/food/packages` | GET | Subscription packages |
| `/food/packages/{id}` | GET | Package details |
| `/food/subscription/create` | POST | Create subscription |
| `/payment/initiate` | POST | Start mobile money payment |
| `/payment/{id}` | GET | Check payment status |
