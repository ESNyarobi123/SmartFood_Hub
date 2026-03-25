import { getSession, setState, STATES, resetSession } from './state.js';
import { handleOnboarding } from './flows/onboarding.js';
import { handleCyber } from './flows/cyber.js';
import { handleMarket } from './flows/market.js';
import { handleAccount } from './flows/account.js';
import { mainMenuMessage } from './utils/formatter.js';

/**
 * Main message router — receives every incoming message and delegates to the right flow
 */
export async function handleMessage(jid, rawText, send) {
    const text = (rawText || '').trim();
    const session = getSession(jid);

    // ─── Global commands (work from any state) ───
    const lower = text.toLowerCase();

    if (lower === 'menu' || lower === 'start' || lower === 'hi' || lower === 'hello' || lower === 'habari' || lower === 'mambo') {
        // If user is authenticated, show main menu
        if (session.userId) {
            setState(jid, STATES.MAIN_MENU);
            await send(mainMenuMessage(session.userName));
            return;
        }
        // Otherwise trigger onboarding
        setState(jid, STATES.NEW_USER);
    }

    if (lower === 'reset' || lower === 'logout') {
        resetSession(jid);
        await send('🔄 Session imefutwa. Andika *hi* kuanza upya.');
        return;
    }

    // ─── Onboarding flow ───
    const onboardingStates = [STATES.NEW_USER, STATES.AWAITING_NAME, STATES.AWAITING_PHONE, STATES.AWAITING_ADDRESS, STATES.AWAITING_IDENTIFY];
    if (onboardingStates.includes(session.state)) {
        const handled = await handleOnboarding(jid, text, send);
        if (handled) return;
    }

    // ─── Main Menu routing ───
    if (session.state === STATES.MAIN_MENU) {
        switch (text) {
            case '1':
                // Monana Food → show meal slots
                setState(jid, STATES.CYBER_SLOTS);
                await handleCyber(jid, text, send);
                return;

            case '2':
                // Monana Market → show market sub-menu
                setState(jid, STATES.MARKET_MENU);
                await handleMarket(jid, '', send);
                return;

            case '3':
                // Akaunti Yangu → fetch and show status
                setState(jid, STATES.ACCOUNT_STATUS);
                await handleAccount(jid, text, send);
                return;

            default:
                // If user just arrived and hasn't seen the menu yet
                if (!text || !['1', '2', '3'].includes(text)) {
                    if (session.userId) {
                        await send(mainMenuMessage(session.userName));
                    } else {
                        // Trigger onboarding
                        setState(jid, STATES.NEW_USER);
                        await handleOnboarding(jid, text, send);
                    }
                    return;
                }
        }
    }

    // ─── Cyber (Monana Food) flow ───
    const cyberStates = [
        STATES.CYBER_SLOTS, STATES.CYBER_MENU, STATES.CYBER_QUANTITY,
        STATES.CYBER_CONFIRM, STATES.CYBER_PAYMENT_METHOD, STATES.CYBER_PAYMENT_PENDING,
    ];
    if (cyberStates.includes(session.state)) {
        const handled = await handleCyber(jid, text, send);
        if (handled) return;
    }

    // ─── Market (Monana Market) flow ───
    const marketStates = [
        STATES.MARKET_MENU, STATES.MARKET_PACKAGES, STATES.MARKET_PACKAGE_DETAIL,
        STATES.MARKET_CONFIRM_SUB, STATES.MARKET_PAYMENT_METHOD, STATES.MARKET_PAYMENT_PENDING,
    ];
    if (marketStates.includes(session.state)) {
        const handled = await handleMarket(jid, text, send);
        if (handled) return;
    }

    // ─── Account flow ───
    if (session.state === STATES.ACCOUNT_STATUS) {
        const handled = await handleAccount(jid, text, send);
        if (handled) return;
    }

    // ─── Fallback — unknown state or command ───
    if (session.userId) {
        await send(
            `🤔 Sijaelewa. Jaribu:\n\n` +
            `▪️ Andika *menu* kuona Menu Kuu\n` +
            `▪️ Andika *1*, *2*, au *3* kuchagua huduma\n` +
            `▪️ Andika *0* kurudi nyuma`
        );
    } else {
        // Not authenticated at all
        setState(jid, STATES.NEW_USER);
        await handleOnboarding(jid, text, send);
    }
}
