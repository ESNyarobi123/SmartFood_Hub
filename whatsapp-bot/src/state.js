/**
 * Session State Manager
 * Tracks each user's conversation state across the bot flow
 */

const sessions = new Map();

// All possible conversation states
export const STATES = {
    // Onboarding
    NEW_USER: 'NEW_USER',
    AWAITING_NAME: 'AWAITING_NAME',
    AWAITING_PHONE: 'AWAITING_PHONE',
    AWAITING_ADDRESS: 'AWAITING_ADDRESS',
    AWAITING_IDENTIFY: 'AWAITING_IDENTIFY',

    // Main
    MAIN_MENU: 'MAIN_MENU',

    // Monana Food (Cyber) — meal ordering
    CYBER_SLOTS: 'CYBER_SLOTS',
    CYBER_MENU: 'CYBER_MENU',
    CYBER_QUANTITY: 'CYBER_QUANTITY',
    CYBER_CONFIRM: 'CYBER_CONFIRM',
    CYBER_PAYMENT_METHOD: 'CYBER_PAYMENT_METHOD',
    CYBER_PAYMENT_PENDING: 'CYBER_PAYMENT_PENDING',
    CYBER_TRACK: 'CYBER_TRACK',

    // Monana Market — subscriptions, sokoni, customization
    MARKET_MENU: 'MARKET_MENU',
    MARKET_PACKAGES: 'MARKET_PACKAGES',
    MARKET_PACKAGE_DETAIL: 'MARKET_PACKAGE_DETAIL',
    MARKET_CONFIRM_SUB: 'MARKET_CONFIRM_SUB',
    MARKET_PAYMENT_METHOD: 'MARKET_PAYMENT_METHOD',
    MARKET_PAYMENT_PENDING: 'MARKET_PAYMENT_PENDING',
    MARKET_MY_SUB: 'MARKET_MY_SUB',
    MARKET_SUB_MANAGE: 'MARKET_SUB_MANAGE',
    MARKET_CUSTOMIZE: 'MARKET_CUSTOMIZE',
    MARKET_CUSTOMIZE_ITEM: 'MARKET_CUSTOMIZE_ITEM',
    MARKET_CUSTOMIZE_SWAP: 'MARKET_CUSTOMIZE_SWAP',
    MARKET_SOKONI: 'MARKET_SOKONI',
    MARKET_SOKONI_QTY: 'MARKET_SOKONI_QTY',
    MARKET_SOKONI_CART: 'MARKET_SOKONI_CART',
    MARKET_SOKONI_PAYMENT: 'MARKET_SOKONI_PAYMENT',
    MARKET_SOKONI_PENDING: 'MARKET_SOKONI_PENDING',

    // Account
    ACCOUNT_STATUS: 'ACCOUNT_STATUS',
};

/**
 * Get or create a session for a user
 */
export function getSession(jid) {
    if (!sessions.has(jid)) {
        sessions.set(jid, {
            state: STATES.NEW_USER,
            userId: null,
            userName: null,
            userPhone: null,
            userAddress: null,

            // Temporary data during flows
            temp: {},

            lastActivity: Date.now(),
        });
    }

    const session = sessions.get(jid);
    session.lastActivity = Date.now();
    return session;
}

/**
 * Set state for a user
 */
export function setState(jid, state, tempData = {}) {
    const session = getSession(jid);
    session.state = state;
    session.temp = { ...session.temp, ...tempData };
    session.lastActivity = Date.now();
}

/**
 * Set user info after registration/resolve
 */
export function setUser(jid, { userId, userName, userPhone, userAddress }) {
    const session = getSession(jid);
    if (userId !== undefined) session.userId = userId;
    if (userName !== undefined) session.userName = userName;
    if (userPhone !== undefined) session.userPhone = userPhone;
    if (userAddress !== undefined) session.userAddress = userAddress;
}

/**
 * Clear temp data
 */
export function clearTemp(jid) {
    const session = getSession(jid);
    session.temp = {};
}

/**
 * Reset session completely (logout)
 */
export function resetSession(jid) {
    sessions.delete(jid);
}

/**
 * Clean up stale sessions (30 min timeout)
 */
export function cleanupSessions(timeoutMs = 30 * 60 * 1000) {
    const now = Date.now();
    for (const [jid, session] of sessions) {
        if (now - session.lastActivity > timeoutMs) {
            sessions.delete(jid);
        }
    }
}

// Run cleanup every 10 minutes
setInterval(() => cleanupSessions(), 10 * 60 * 1000);
