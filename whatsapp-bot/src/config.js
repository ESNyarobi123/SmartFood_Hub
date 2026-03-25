/**
 * Monana WhatsApp Bot Configuration
 */
export const config = {
    // Laravel API Base URL
    apiBaseUrl: process.env.API_BASE_URL || 'http://127.0.0.1:8000/api/bot',

    // Bot authentication token (must match bot_super_token in Laravel settings)
    botToken: process.env.BOT_TOKEN || 'monana-bot-secret-2026',

    // Session storage directory
    sessionDir: './auth_session',

    // Bot info
    botName: 'Monana Bot',
    currency: 'TZS',

    // Timeouts
    sessionTimeout: 30 * 60 * 1000, // 30 minutes inactivity timeout
    paymentPollInterval: 10 * 1000,  // 10 seconds between payment status checks
    paymentPollMaxAttempts: 18,       // 3 minutes max polling
};
