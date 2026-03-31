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
        await send('🔄 Session imefutwa. Tuma *hi* kuanza upya.');
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
                // Monana Market → show smart market menu
                setState(jid, STATES.MARKET_MENU);
                await handleMarket(jid, '', send);
                return;

            case '3':
                // Akaunti Yangu → fetch and show status
                setState(jid, STATES.ACCOUNT_STATUS);
                await handleAccount(jid, text, send);
                return;

            default:
                if (!text || !['1', '2', '3'].includes(text)) {
                    if (session.userId) {
                        await send(mainMenuMessage(session.userName));
                    } else {
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
        STATES.CYBER_TRACK,
    ];
    if (cyberStates.includes(session.state)) {
        const handled = await handleCyber(jid, text, send);
        if (handled) return;
    }

    // ─── Market (Monana Market) flow ───
    const marketStates = [
        STATES.MARKET_MENU, STATES.MARKET_PACKAGES, STATES.MARKET_PACKAGE_DETAIL,
        STATES.MARKET_CONFIRM_SUB, STATES.MARKET_PAYMENT_METHOD, STATES.MARKET_PAYMENT_PENDING,
        STATES.MARKET_MY_SUB, STATES.MARKET_SUB_MANAGE,
        STATES.MARKET_CUSTOMIZE, STATES.MARKET_CUSTOMIZE_ITEM, STATES.MARKET_CUSTOMIZE_SWAP,
        STATES.MARKET_SOKONI, STATES.MARKET_SOKONI_QTY, STATES.MARKET_SOKONI_CART,
        STATES.MARKET_SOKONI_PAYMENT, STATES.MARKET_SOKONI_PENDING,
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
            `🤔 _Samahani, sijaelewa jibu lako._\n\n` +
            `👇 Tuma *menu* kuona Menu Kuu\n` +
            `👇 Tuma *1*, *2*, au *3* kuchagua huduma\n` +
            `👇 Tuma *0* kurudi nyuma`
        );
    } else {
        setState(jid, STATES.NEW_USER);
        await handleOnboarding(jid, text, send);
    }
}
