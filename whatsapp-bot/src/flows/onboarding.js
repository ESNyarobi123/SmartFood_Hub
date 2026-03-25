import { api } from '../api.js';
import { STATES, setState, setUser, getSession } from '../state.js';
import { mainMenuMessage } from '../utils/formatter.js';

/**
 * Validate Tanzanian phone number format
 * Accepts: 07xx, 06xx, 255xx, +255xx, 0xx (9-10 digit local)
 */
function isValidTzPhone(input) {
    const digits = input.replace(/[^0-9]/g, '');
    // 07xxxxxxxx (10 digits starting with 0)
    if (/^0[67]\d{8}$/.test(digits)) return true;
    // 2557xxxxxxxx (12 digits starting with 255)
    if (/^255[67]\d{8}$/.test(digits)) return true;
    // 7xxxxxxxx (9 digits, missing leading 0)
    if (/^[67]\d{8}$/.test(digits)) return true;
    return false;
}

/**
 * Format phone to 07XXXXXXXX
 */
function formatPhone(input) {
    const digits = input.replace(/[^0-9]/g, '');
    if (digits.startsWith('255') && digits.length === 12) {
        return '0' + digits.substring(3);
    }
    if (digits.startsWith('0') && digits.length === 10) {
        return digits;
    }
    if (digits.length === 9 && (digits.startsWith('6') || digits.startsWith('7'))) {
        return '0' + digits;
    }
    return digits;
}

/**
 * Handle the onboarding flow:
 * NEW_USER → resolve by JID → if found: menu, if not: register
 * Register: name → phone (user enters real number) → address → done
 * AWAITING_IDENTIFY: returning user whose JID didn't match → enter phone to find account
 */
export async function handleOnboarding(jid, text, send) {
    const session = getSession(jid);
    const whatsappJid = jid; // Full JID e.g. 165515876151525@s.whatsapp.net

    switch (session.state) {
        case STATES.NEW_USER: {
            // Extract raw number from JID
            const rawPhone = jid.split('@')[0];

            try {
                const result = await api.resolveUser(rawPhone, whatsappJid);

                if (result.status === 'registered') {
                    // Existing user — go straight to menu
                    setUser(jid, {
                        userId: result.user_id,
                        userName: result.name,
                        userPhone: result.phone,
                        userAddress: result.address,
                    });
                    setState(jid, STATES.MAIN_MENU);
                    await send(`Karibu tena *${result.name}*! 👋\n`);
                    await send(mainMenuMessage(result.name));
                    return true;
                }

                // New user — ask what they want to do
                setState(jid, STATES.AWAITING_NAME);
                await send(
                    `👋 *Karibu Monana Platform!*\n\n` +
                    `Naona ni mara yako ya kwanza hapa.\n\n` +
                    `1️⃣  *Jisajili* (Unda akaunti mpya)\n` +
                    `2️⃣  *Tayari nina akaunti* (Ingia kwa namba ya simu)\n\n` +
                    `Andika *1* au *2*:`
                );
                return true;

            } catch (err) {
                console.error('resolveUser error:', err.message);
                await send(
                    `⚠️ Samahani, kuna tatizo la mfumo. Tafadhali jaribu tena.\n\n` +
                    `Andika *hi* kuanza upya.`
                );
                return true;
            }
        }

        case STATES.AWAITING_NAME: {
            // Check if user chose option 2 (already has account)
            if (text === '2') {
                setState(jid, STATES.AWAITING_IDENTIFY);
                await send(
                    `📱 Andika *namba yako ya simu* uliyosajili nayo:\n` +
                    `_Mfano: 0712345678_`
                );
                return true;
            }

            // If user typed "1", ask for name
            if (text === '1') {
                await send(`📝 Tafadhali andika *jina lako kamili*:`);
                return true;
            }

            // User is typing their name
            if (!text || text.trim().length < 2) {
                await send(
                    `⚠️ Tafadhali andika jina lako kamili (angalau herufi 2):\n\n` +
                    `Au andika *2* kama tayari una akaunti.`
                );
                return true;
            }

            const name = text.trim();
            setState(jid, STATES.AWAITING_PHONE, { name });
            await send(
                `Asante *${name}*! 🎉\n\n` +
                `� Sasa andika *namba yako ya simu* (ya Tanzania):\n` +
                `_Mfano: 0712345678 au 0678123456_`
            );
            return true;
        }

        case STATES.AWAITING_PHONE: {
            // Back button first
            if (text.trim() === '0') {
                setState(jid, STATES.AWAITING_NAME);
                await send(`📝 Andika *jina lako kamili*:`);
                return true;
            }

            if (!text || !isValidTzPhone(text.trim())) {
                await send(
                    `⚠️ Namba si sahihi. Tafadhali andika namba ya simu ya Tanzania:\n` +
                    `_Mfano: 0712345678 au 0678123456_\n\n` +
                    `Andika *0* kurudi nyuma.`
                );
                return true;
            }

            const phone = formatPhone(text.trim());
            setState(jid, STATES.AWAITING_ADDRESS, { ...session.temp, phone });
            await send(
                `�� Sasa niambie *eneo ulilopo* kwa delivery.\n` +
                `_Mfano: Kijitonyama, Makumbusho, Sinza_\n\n` +
                `Andika *0* kurudi nyuma.`
            );
            return true;
        }

        case STATES.AWAITING_ADDRESS: {
            if (text.trim() === '0') {
                setState(jid, STATES.AWAITING_PHONE, session.temp);
                await send(`📱 Andika *namba yako ya simu*:\n_Mfano: 0712345678_`);
                return true;
            }

            if (!text || text.trim().length < 3) {
                await send(
                    `⚠️ Tafadhali andika eneo lako (angalau herufi 3).\n` +
                    `_Mfano: Kijitonyama_\n\n` +
                    `Andika *0* kurudi nyuma.`
                );
                return true;
            }

            const address = text.trim();
            const { name, phone } = session.temp;

            try {
                const result = await api.registerUser({
                    name,
                    phone,
                    address,
                    whatsappJid: whatsappJid,
                });

                if (result.status === 'success') {
                    setUser(jid, {
                        userId: result.user_id,
                        userName: result.name,
                        userPhone: result.phone,
                        userAddress: result.address,
                    });

                    // Send registration success + web login credentials
                    const webLogin = result.web_login;
                    await send(
                        `✅ *Usajili Umekamilika!*\n\n` +
                        `Karibu *${result.name}*! Akaunti yako imeundwa.\n\n` +
                        `🌐 *Kuingia kwenye Website:*\n` +
                        `📱 Namba: *${webLogin.phone}*\n` +
                        `🔑 Password: *${webLogin.password}*\n` +
                        `🔗 monana.co.tz/login\n\n` +
                        `_Hifadhi taarifa hizi!_`
                    );

                    // Show main menu after short delay
                    setState(jid, STATES.MAIN_MENU);
                    setTimeout(async () => {
                        await send(mainMenuMessage(result.name));
                    }, 1500);
                    return true;

                } else if (result.status === 'already_exists') {
                    // Phone already registered — link JID and proceed
                    setUser(jid, {
                        userId: result.user_id,
                        userName: result.name,
                        userPhone: result.phone,
                        userAddress: result.address,
                    });
                    setState(jid, STATES.MAIN_MENU);
                    await send(
                        `ℹ️ Namba *${result.phone}* tayari imesajiliwa kwa *${result.name}*.\n` +
                        `Tumekuunganisha na akaunti yako! ✅\n`
                    );
                    await send(mainMenuMessage(result.name));
                    return true;
                }

            } catch (err) {
                const errMsg = err.response?.data?.message || err.message;
                console.error('registerUser error:', errMsg);
                await send(
                    `⚠️ Imeshindwa kusajili: ${errMsg}\n\n` +
                    `1️⃣  *Jaribu tena*\n` +
                    `0️⃣  *Rudi mwanzo*\n\n` +
                    `Andika *1* au *0*.`
                );
                // Stay in same state so they can retry
                return true;
            }

            return true;
        }

        // ─── Returning user identification (JID didn't match stored phone) ───
        case STATES.AWAITING_IDENTIFY: {
            if (text.trim() === '0') {
                setState(jid, STATES.AWAITING_NAME);
                await send(
                    `1️⃣  *Jisajili* (Unda akaunti mpya)\n` +
                    `2️⃣  *Tayari nina akaunti*\n\n` +
                    `Andika *1* au *2*:`
                );
                return true;
            }

            if (!text || !isValidTzPhone(text.trim())) {
                await send(
                    `⚠️ Namba si sahihi. Andika namba ya simu ya Tanzania:\n` +
                    `_Mfano: 0712345678_\n\n` +
                    `Andika *0* kurudi nyuma.`
                );
                return true;
            }

            const phone = formatPhone(text.trim());

            try {
                // Try resolving with user-provided phone
                const result = await api.resolveUser(phone, whatsappJid);

                if (result.status === 'registered') {
                    setUser(jid, {
                        userId: result.user_id,
                        userName: result.name,
                        userPhone: result.phone,
                        userAddress: result.address,
                    });
                    setState(jid, STATES.MAIN_MENU);
                    await send(`✅ Tumekupata *${result.name}*! Karibu tena! �\n`);
                    await send(mainMenuMessage(result.name));
                    return true;
                }

                // Not found
                await send(
                    `⚠️ Namba *${phone}* haijasajiliwa.\n\n` +
                    `1️⃣  *Jaribu namba nyingine*\n` +
                    `2️⃣  *Jisajili kwa akaunti mpya*\n` +
                    `0️⃣  *Rudi mwanzo*\n\n` +
                    `Andika *1*, *2*, au *0*.`
                );

                // If they type 2, go to name entry
                setState(jid, STATES.AWAITING_IDENTIFY, { retryChoice: true });
                return true;

            } catch (err) {
                console.error('resolveUser (identify) error:', err.message);
                await send(
                    `⚠️ Kuna tatizo la mfumo. Jaribu tena.\n\n` +
                    `Andika namba yako ya simu au *0* kurudi nyuma.`
                );
                return true;
            }
        }
    }

    return false;
}
