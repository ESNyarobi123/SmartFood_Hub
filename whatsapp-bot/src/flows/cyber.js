import { api } from '../api.js';
import { STATES, setState, getSession, clearTemp } from '../state.js';
import {
    formatMealSlots,
    formatMenuItems,
    formatCurrency,
    formatOrderConfirmation,
    mainMenuMessage,
} from '../utils/formatter.js';

/**
 * Handle Monana Food (Cyber Cafe) flow
 */
export async function handleCyber(jid, text, send) {
    const session = getSession(jid);

    switch (session.state) {
        // ─── Show available meal slots ───
        case STATES.CYBER_SLOTS: {
            try {
                const result = await api.getMealSlots();
                const slots = result.data || [];
                const openSlots = slots.filter(s => s.is_open);

                if (openSlots.length === 0) {
                    await send(
                        `⚠️ *Hakuna muda wa mlo ulio wazi sasa hivi.*\n\n` +
                        `Muda wa mlo:\n` +
                        slots.map(s => `  ${s.is_open ? '🟢' : '🔴'} ${s.display_name} — ${s.delivery_time}`).join('\n') +
                        `\n\nAndika *0* kurudi kwenye Menu Kuu.`
                    );
                    setState(jid, STATES.MAIN_MENU);
                    return true;
                }

                setState(jid, STATES.CYBER_MENU, { slots: openSlots });
                await send(formatMealSlots(openSlots));
                return true;

            } catch (err) {
                console.error('getMealSlots error:', err.message);
                await send('⚠️ Imeshindwa kupata muda wa mlo. Jaribu tena.');
                setState(jid, STATES.MAIN_MENU);
                return true;
            }
        }

        // ─── User selected a meal slot, show menu ───
        case STATES.CYBER_MENU: {
            // Handle back
            if (text === '0') {
                setState(jid, STATES.MAIN_MENU);
                await send(mainMenuMessage(session.userName));
                return true;
            }

            const { slots, selectedSlot, menuItems } = session.temp;

            // If we already have menu items, user is selecting a food item
            if (menuItems && menuItems.length > 0) {
                const itemIdx = parseInt(text) - 1;
                if (isNaN(itemIdx) || itemIdx < 0 || itemIdx >= menuItems.length) {
                    await send(`⚠️ Chaguo si sahihi. Andika namba kati ya 1-${menuItems.length} au *0* kurudi.`);
                    return true;
                }

                const selectedItem = menuItems[itemIdx];
                setState(jid, STATES.CYBER_QUANTITY, {
                    ...session.temp,
                    selectedItem,
                    cart: session.temp.cart || [],
                });

                await send(
                    `🍽️ *${selectedItem.name}* — ${formatCurrency(selectedItem.price)}\n\n` +
                    `Unataka ngapi? Andika idadi (mfano *1* au *2*):`
                );
                return true;
            }

            // User is selecting a slot
            if (slots && slots.length > 0) {
                const slotIdx = parseInt(text) - 1;
                if (isNaN(slotIdx) || slotIdx < 0 || slotIdx >= slots.length) {
                    await send(`⚠️ Chaguo si sahihi. Andika namba kati ya 1-${slots.length}.`);
                    return true;
                }

                const slot = slots[slotIdx];
                try {
                    const result = await api.getMenu(slot.id);
                    const items = result.data || [];

                    if (items.length === 0) {
                        await send(`⚠️ Hakuna vyakula kwa *${slot.display_name}* kwa sasa.\nAndika namba nyingine au *0* kurudi.`);
                        return true;
                    }

                    setState(jid, STATES.CYBER_MENU, {
                        ...session.temp,
                        selectedSlot: slot,
                        menuItems: items,
                        cart: [],
                    });
                    await send(formatMenuItems(items, slot.display_name));
                    return true;

                } catch (err) {
                    console.error('getMenu error:', err.message);
                    await send('⚠️ Imeshindwa kupata menu. Jaribu tena.');
                    return true;
                }
            }

            return false;
        }

        // ─── User entering quantity ───
        case STATES.CYBER_QUANTITY: {
            const qty = parseInt(text);
            if (isNaN(qty) || qty < 1 || qty > 20) {
                await send('⚠️ Andika idadi sahihi (1-20):');
                return true;
            }

            const { selectedItem, cart, menuItems, selectedSlot } = session.temp;
            cart.push({
                menu_item_id: selectedItem.id,
                quantity: qty,
                name: selectedItem.name,
                price: selectedItem.price,
                total: selectedItem.price * qty,
            });

            const cartTotal = cart.reduce((sum, c) => sum + c.total, 0);

            let cartMsg = `🛒 *Kikapu Chako:*\n`;
            cart.forEach((c, i) => {
                cartMsg += `  ${i + 1}. ${c.name} x${c.quantity} = ${formatCurrency(c.total)}\n`;
            });
            cartMsg += `\n💰 *Jumla: ${formatCurrency(cartTotal)}*\n\n`;
            cartMsg += `1️⃣  *Ongeza chakula kingine*\n`;
            cartMsg += `2️⃣  *Thibitisha na Agiza*\n`;
            cartMsg += `0️⃣  *Ghairi na rudi Menu Kuu*\n\n`;
            cartMsg += `Andika *1*, *2*, au *0*.`;

            setState(jid, STATES.CYBER_CONFIRM, {
                ...session.temp,
                cart,
                cartTotal,
            });

            await send(cartMsg);
            return true;
        }

        // ─── Confirm order or add more ───
        case STATES.CYBER_CONFIRM: {
            if (text === '0') {
                clearTemp(jid);
                setState(jid, STATES.MAIN_MENU);
                await send(mainMenuMessage(session.userName));
                return true;
            }

            if (text === '1') {
                // Go back to menu to add more
                setState(jid, STATES.CYBER_MENU, session.temp);
                await send(formatMenuItems(session.temp.menuItems, session.temp.selectedSlot?.display_name));
                return true;
            }

            if (text === '2') {
                // Create the order
                const { cart, selectedSlot } = session.temp;
                try {
                    const result = await api.createCyberOrder({
                        userId: session.userId,
                        mealSlotId: selectedSlot.id,
                        items: cart.map(c => ({
                            menu_item_id: c.menu_item_id,
                            quantity: c.quantity,
                        })),
                        deliveryAddress: session.userAddress || 'WhatsApp',
                    });

                    if (result.success) {
                        setState(jid, STATES.CYBER_PAYMENT_METHOD, {
                            orderId: result.data.order_id,
                            orderNumber: result.data.order_number,
                            totalAmount: result.data.total_amount,
                            paymentType: 'cyber_order',
                        });

                        await send(formatOrderConfirmation(result.data, 'cyber'));
                        return true;
                    } else {
                        await send(`⚠️ ${result.message || 'Imeshindwa kuunda order.'}`);
                        return true;
                    }
                } catch (err) {
                    const errMsg = err.response?.data?.message || err.message;
                    console.error('createCyberOrder error:', errMsg);
                    await send(
                        `⚠️ *Imeshindwa kuunda order:*\n${errMsg}\n\n` +
                        `1️⃣  *Jaribu tena*\n` +
                        `0️⃣  *Rudi Menu Kuu*\n\n` +
                        `Andika *2* kujaribu tena au *0* kurudi.`
                    );
                    return true;
                }
            }

            await send('⚠️ Andika *1* (ongeza), *2* (agiza), au *0* (ghairi).');
            return true;
        }

        // ─── Payment method selection ───
        case STATES.CYBER_PAYMENT_METHOD: {
            return handlePaymentMethod(jid, text, send, session, 'CYBER');
        }

        // ─── Payment pending / polling ───
        case STATES.CYBER_PAYMENT_PENDING: {
            if (text === '0' || text.toLowerCase() === 'menu') {
                clearTemp(jid);
                setState(jid, STATES.MAIN_MENU);
                await send(mainMenuMessage(session.userName));
                return true;
            }
            await send('⏳ Bado tunasubiri malipo yako. Utapokea ujumbe malipo yakipokelewa.\n\nAndika *0* kurudi Menu Kuu.');
            return true;
        }
    }

    return false;
}

/**
 * Shared payment method handler
 */
export async function handlePaymentMethod(jid, text, send, session, prefix) {
    // Handle back to main menu
    if (text === '0') {
        clearTemp(jid);
        setState(jid, STATES.MAIN_MENU);
        await send(mainMenuMessage(session.userName));
        return true;
    }

    const methods = { '1': 'mpesa', '2': 'tigopesa', '3': 'airtelmoney' };
    const method = methods[text];

    if (!method) {
        await send(
            `⚠️ Chagua njia ya malipo:\n\n` +
            `1️⃣  *M-Pesa*\n` +
            `2️⃣  *Tigo Pesa*\n` +
            `3️⃣  *Airtel Money*\n` +
            `0️⃣  *Rudi Menu Kuu*\n\n` +
            `Andika *1*, *2*, *3*, au *0*.`
        );
        return true;
    }

    const { orderId, totalAmount, paymentType } = session.temp;
    const phone = session.userPhone;

    if (!phone) {
        await send(
            `⚠️ Namba ya simu haipo kwenye akaunti yako.\n` +
            `Tafadhali wasiliana nasi kwa msaada.\n\n` +
            `Andika *0* kurudi Menu Kuu.`
        );
        return true;
    }

    try {
        const result = await api.initiatePayment({
            userId: session.userId,
            type: paymentType || 'order',
            id: orderId,
            method,
            phoneNumber: phone,
        });

        if (result.status === 'pending') {
            const pendingState = prefix === 'CYBER' ? STATES.CYBER_PAYMENT_PENDING : STATES.MARKET_PAYMENT_PENDING;
            setState(jid, pendingState, {
                ...session.temp,
                paymentId: result.payment_id,
            });

            await send(
                `⏳ *Inasubiri Malipo...*\n\n` +
                `📱 STK Push imetumwa kwenye: *${phone}*\n` +
                `💰 Kiasi: *${formatCurrency(totalAmount)}*\n` +
                `📲 Njia: *${method.toUpperCase()}*\n\n` +
                `Tafadhali weka PIN yako kulipa.\n` +
                `Tutakutumia ujumbe malipo yakipokelewa. ✅`
            );

            // Start polling for payment status
            pollPaymentStatus(jid, result.payment_id, send, session);
            return true;

        } else {
            await send(
                `⚠️ ${result.message || 'Imeshindwa kuanzisha malipo.'}\n\n` +
                `1️⃣  *M-Pesa*\n2️⃣  *Tigo Pesa*\n3️⃣  *Airtel Money*\n0️⃣  *Rudi Menu Kuu*\n\n` +
                `Jaribu tena au andika *0* kurudi.`
            );
            return true;
        }
    } catch (err) {
        const errMsg = err.response?.data?.message || err.message;
        console.error('initiatePayment error:', errMsg);

        // Detect specific errors and give friendly messages
        const isGatewayError = errMsg.toLowerCase().includes('not configured') ||
                               errMsg.toLowerCase().includes('gateway');

        if (isGatewayError) {
            await send(
                `⚠️ *Mfumo wa Malipo Haujaandaliwa Bado*\n\n` +
                `Samahani, huduma ya malipo ya mobile money bado haijawezeshwa.\n` +
                `Agizo lako limehifadhiwa — unaweza kulipa baadaye.\n\n` +
                `📞 Wasiliana nasi kwa msaada: +255 XXX XXX XXX\n\n` +
                `0️⃣  *Rudi Menu Kuu*\n\n` +
                `Andika *0* kurudi.`
            );
        } else {
            await send(
                `⚠️ *Malipo Yameshindwa*\n\n` +
                `Sababu: ${errMsg}\n\n` +
                `1️⃣  *M-Pesa*\n2️⃣  *Tigo Pesa*\n3️⃣  *Airtel Money*\n0️⃣  *Rudi Menu Kuu*\n\n` +
                `Jaribu tena au andika *0* kurudi.`
            );
        }
        return true;
    }
}

/**
 * Poll payment status in background
 */
async function pollPaymentStatus(jid, paymentId, send, session) {
    let attempts = 0;
    const maxAttempts = 18; // 3 minutes

    const interval = setInterval(async () => {
        attempts++;
        try {
            const result = await api.getPaymentStatus(paymentId);

            if (result.status === 'paid') {
                clearInterval(interval);
                clearTemp(jid);
                setState(jid, STATES.MAIN_MENU);

                await send(
                    `✅ *Malipo Yamepokelewa!*\n\n` +
                    `💰 Kiasi: *${formatCurrency(result.amount)}*\n\n` +
                    `Asante kwa kutumia Monana! Agizo lako linaandaliwa. 🎉\n\n` +
                    `Andika *menu* kurudi kwenye Menu Kuu.`
                );
                return;
            }

            if (result.status === 'failed' || result.status === 'cancelled') {
                clearInterval(interval);
                await send(
                    `❌ *Malipo Yameshindwa*\n\n` +
                    `Malipo yako hayakukamilika. Tafadhali jaribu tena.\n\n` +
                    `1️⃣  *M-Pesa*\n2️⃣  *Tigo Pesa*\n3️⃣  *Airtel Money*\n0️⃣  *Rudi Menu Kuu*`
                );
                return;
            }

            // Still pending
            if (attempts >= maxAttempts) {
                clearInterval(interval);
                await send(
                    `⏰ *Muda wa kusubiri umekwisha.*\n\n` +
                    `Kama umelipa, tafadhali subiri dakika chache. ` +
                    `Tutakutumia ujumbe malipo yakipokelewa.\n\n` +
                    `Andika *menu* kurudi kwenye Menu Kuu.`
                );
            }
        } catch (err) {
            // Silently continue polling
        }
    }, 10000); // Check every 10 seconds
}
