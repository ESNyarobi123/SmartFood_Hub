import { api } from '../api.js';
import { STATES, setState, getSession, clearTemp } from '../state.js';
import { formatUserStatus, mainMenuMessage } from '../utils/formatter.js';

/**
 * Handle Account Status flow
 */
export async function handleAccount(jid, text, send) {
    const session = getSession(jid);

    if (session.state !== STATES.ACCOUNT_STATUS) return false;

    if (text === '0' || text.toLowerCase() === 'menu') {
        clearTemp(jid);
        setState(jid, STATES.MAIN_MENU);
        await send(mainMenuMessage(session.userName));
        return true;
    }

    // Guard: userId must exist
    if (!session.userId) {
        await send(
            `⚠️ Hatujakutambua. Tafadhali anza upya.\n\n` +
            `Andika *hi* kuanza upya.`
        );
        setState(jid, STATES.NEW_USER);
        return true;
    }

    // Fetch combined status
    try {
        const result = await api.getUserStatus(session.userId);

        if (result.status === 'success') {
            await send(formatUserStatus(result));
        } else {
            await send(
                `⚠️ Imeshindwa kupata taarifa zako.\n\n` +
                `0️⃣  *Rudi Menu Kuu*\n\n` +
                `Andika *0* kurudi.`
            );
        }
    } catch (err) {
        console.error('getUserStatus error:', err.response?.data?.message || err.message);
        await send(
            `⚠️ Kuna tatizo la mfumo. Jaribu tena baadaye.\n\n` +
            `0️⃣  *Rudi Menu Kuu*\n\n` +
            `Andika *0* kurudi.`
        );
    }

    return true;
}
