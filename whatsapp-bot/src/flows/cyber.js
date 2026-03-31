import { api } from '../api.js';
import { STATES, setState, getSession, clearTemp } from '../state.js';
import {
    formatMealSlots,
    formatMenuItems,
    formatCurrency,
    formatCart,
    formatOrderConfirmation,
    mainMenuMessage,
    paymentMethodMessage,
    paymentPendingMessage,
    paymentGatewayError,
    paymentFailedMessage,
    paymentTimeoutMessage,
    paymentSuccessMessage,
    statusEmoji,
    statusLabel,
} from '../utils/formatter.js';

/**
 * Handle Monana Food (Cyber Cafe) flow
 * — Meal slots → Menu → Cart → Order → Payment → Track
 */
export async function handleCyber(jid, text, send) {
    const session = getSession(jid);

    switch (session.state) {

        // ═══════════════════════════════════════
        // SHOW AVAILABLE MEAL SLOTS
        // ═══════════════════════════════════════
        case STATES.CYBER_SLOTS: {
            try {
                const result = await api.getMealSlots();
                const slots = result.data || [];
                const openSlots = slots.filter(s => s.is_open);

                if (openSlots.length === 0) {
                    let msg = `😔 *Hakuna muda wa mlo ulio wazi sasa.*\n\n`;
                    msg += `🕐 _Muda wa mlo uliopo:_\n`;
                    slots.forEach(s => {
                        msg += `  ${s.is_open ? '🟢' : '🔴'} *${s.display_name}* — ${s.delivery_time}\n`;
                    });
                    msg += `\n👇 _Tuma *0* kurudi Menu Kuu_`;
                    await send(msg);
                    setState(jid, STATES.MAIN_MENU);
                    return true;
                }

                setState(jid, STATES.CYBER_MENU, { slots: openSlots });
                await send(formatMealSlots(openSlots));
                return true;

            } catch (err) {
                console.error('getMealSlots error:', err.message);
                await send('⚠️ Imeshindwa kupata muda wa mlo. Jaribu tena.\n\n👇 _Tuma *0* kurudi._');
                setState(jid, STATES.MAIN_MENU);
                return true;
            }
        }

        // ═══════════════════════════════════════
        // USER SELECTED SLOT → SHOW MENU
        // ═══════════════════════════════════════
        case STATES.CYBER_MENU: {
            if (text === '0') {
                clearTemp(jid);
                setState(jid, STATES.MAIN_MENU);
                await send(mainMenuMessage(session.userName));
                return true;
            }

            const { slots, selectedSlot, menuItems } = session.temp;

            // If menu items loaded, user is picking a food item
            if (menuItems && menuItems.length > 0) {
                const itemIdx = parseInt(text) - 1;
                if (isNaN(itemIdx) || itemIdx < 0 || itemIdx >= menuItems.length) {
                    await send(`⚠️ Tafadhali tuma namba kati ya 1-${menuItems.length} au *0* kurudi.`);
                    return true;
                }

                const selectedItem = menuItems[itemIdx];
                setState(jid, STATES.CYBER_QUANTITY, {
                    ...session.temp,
                    selectedItem,
                    cart: session.temp.cart || [],
                });

                await send(
                    `🍽️ *${selectedItem.name}* — *${formatCurrency(selectedItem.price)}*\n\n` +
                    `✍️ _Unataka ngapi? Tuma idadi (mfano *1* au *2*):_`
                );
                return true;
            }

            // User is selecting a slot
            if (slots && slots.length > 0) {
                const slotIdx = parseInt(text) - 1;
                if (isNaN(slotIdx) || slotIdx < 0 || slotIdx >= slots.length) {
                    await send(`⚠️ Tafadhali tuma namba kati ya 1-${slots.length} au *0* kurudi.`);
                    return true;
                }

                const slot = slots[slotIdx];
                try {
                    const result = await api.getMenu(slot.id);
                    const items = result.data || [];

                    if (items.length === 0) {
                        await send(`😔 Hakuna vyakula kwa *${slot.display_name}* kwa sasa.\n\n👇 _Tuma namba nyingine au *0* kurudi._`);
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

        // ═══════════════════════════════════════
        // ENTER QUANTITY
        // ═══════════════════════════════════════
        case STATES.CYBER_QUANTITY: {
            const qty = parseInt(text);
            if (isNaN(qty) || qty < 1 || qty > 20) {
                await send('⚠️ Tuma idadi sahihi (1-20):');
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

            setState(jid, STATES.CYBER_CONFIRM, {
                ...session.temp,
                cart,
                cartTotal,
            });

            await send(formatCart(cart, cartTotal));
            return true;
        }

        // ═══════════════════════════════════════
        // CONFIRM ORDER OR ADD MORE
        // ═══════════════════════════════════════
        case STATES.CYBER_CONFIRM: {
            if (text === '0') {
                clearTemp(jid);
                setState(jid, STATES.MAIN_MENU);
                await send(mainMenuMessage(session.userName));
                return true;
            }

            if (text === '1') {
                // Back to menu to add more
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
                        await send(`⚠️ ${result.message || 'Imeshindwa kuunda order.'}\n\nTuma *0* kurudi.`);
                        return true;
                    }
                } catch (err) {
                    const errMsg = err.response?.data?.message || err.message;
                    console.error('createCyberOrder error:', errMsg);
                    await send(
                        `⚠️ *Imeshindwa:* ${errMsg}\n\n` +
                        `Tuma *2* kujaribu tena au *0* kurudi.`
                    );
                    return true;
                }
            }

            await send('👇 Tuma *1* (ongeza), *2* (agiza), au *0* (ghairi).');
            return true;
        }

        // ═══════════════════════════════════════
        // PAYMENT METHOD SELECTION
        // ═══════════════════════════════════════
        case STATES.CYBER_PAYMENT_METHOD: {
            return handlePaymentMethod(jid, text, send, session, 'CYBER');
        }

        // ═══════════════════════════════════════
        // PAYMENT PENDING / POLLING
        // ═══════════════════════════════════════
        case STATES.CYBER_PAYMENT_PENDING: {
            if (text === '0' || text.toLowerCase() === 'menu') {
                clearTemp(jid);
                setState(jid, STATES.MAIN_MENU);
                await send(mainMenuMessage(session.userName));
                return true;
            }

            // Check if user wants to track their order
            if (text.toLowerCase() === 'track' || text === '1') {
                const { orderId } = session.temp;
                if (orderId) {
                    try {
                        const result = await api.getCyberOrder(orderId);
                        if (result.success) {
                            const o = result.data;
                            let msg = `📋 *ORDER ${o.order_number}*\n━━━━━━━━━━━━━━━━━━━━\n\n`;
                            msg += `${statusEmoji(o.status)} *Status: ${statusLabel(o.status)}*\n`;
                            msg += `💰 Jumla: ${formatCurrency(o.total_amount)}\n\n`;
                            if (o.items && o.items.length > 0) {
                                msg += `🧾 *Vitu:*\n`;
                                o.items.forEach(item => {
                                    msg += `   🔸 ${item.name} ×${item.quantity} — ${formatCurrency(item.price)}\n`;
                                });
                            }
                            msg += `\n👇 _Tuma *0* kurudi Menu Kuu_`;
                            await send(msg);
                            return true;
                        }
                    } catch (err) {
                        // ignore, show default
                    }
                }
            }

            await send(
                '⏳ _Bado tunasubiri malipo yako._\n\n' +
                '1️⃣ *Angalia order status*\n' +
                '0️⃣ *Rudi Menu Kuu*'
            );
            return true;
        }

        // ═══════════════════════════════════════
        // ORDER TRACKING
        // ═══════════════════════════════════════
        case STATES.CYBER_TRACK: {
            if (text === '0') {
                clearTemp(jid);
                setState(jid, STATES.MAIN_MENU);
                await send(mainMenuMessage(session.userName));
                return true;
            }

            const { trackOrderId } = session.temp;
            if (trackOrderId) {
                try {
                    const result = await api.getCyberOrder(trackOrderId);
                    if (result.success) {
                        const o = result.data;
                        const progress = getOrderProgress(o.status);
                        let msg = `📋 *ORDER ${o.order_number}*\n━━━━━━━━━━━━━━━━━━━━\n\n`;
                        msg += `${progress}\n\n`;
                        msg += `${statusEmoji(o.status)} *${statusLabel(o.status)}*\n`;
                        msg += `💰 ${formatCurrency(o.total_amount)}\n\n`;
                        msg += `👇 _Tuma *0* kurudi Menu Kuu_`;
                        await send(msg);
                        return true;
                    }
                } catch (err) {
                    console.error('track error:', err.message);
                }
            }

            await send('⚠️ Imeshindwa kupata order.\n\n👇 _Tuma *0* kurudi._');
            return true;
        }
    }

    return false;
}


// ═══════════════════════════════════════
// SHARED PAYMENT METHOD HANDLER
// ═══════════════════════════════════════

export async function handlePaymentMethod(jid, text, send, session, prefix) {
    if (text === '0') {
        clearTemp(jid);
        setState(jid, STATES.MAIN_MENU);
        await send(mainMenuMessage(session.userName));
        return true;
    }

    const methods = { '1': 'mpesa', '2': 'tigopesa', '3': 'airtelmoney' };
    const method = methods[text];

    if (!method) {
        await send(paymentMethodMessage());
        return true;
    }

    const { orderId, totalAmount, paymentType } = session.temp;
    const phone = session.userPhone;

    if (!phone) {
        await send(
            `⚠️ Namba ya simu haipo kwenye akaunti yako.\n` +
            `Tafadhali wasiliana nasi kwa msaada.\n\n` +
            `👇 _Tuma *0* kurudi Menu Kuu_`
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

            await send(paymentPendingMessage(phone, totalAmount, method));

            // Start polling
            pollPaymentStatus(jid, result.payment_id, send, session);
            return true;
        } else {
            await send(
                `⚠️ ${result.message || 'Imeshindwa kuanzisha malipo.'}\n\n` +
                paymentMethodMessage()
            );
            return true;
        }
    } catch (err) {
        const errMsg = err.response?.data?.message || err.message;
        console.error('initiatePayment error:', errMsg);

        const isGatewayError = errMsg.toLowerCase().includes('not configured') ||
                               errMsg.toLowerCase().includes('gateway');

        if (isGatewayError) {
            await send(paymentGatewayError());
        } else {
            await send(
                `⚠️ *Malipo Yameshindwa:* ${errMsg}\n\n` +
                paymentMethodMessage()
            );
        }
        return true;
    }
}


// ═══════════════════════════════════════
// PAYMENT POLLING
// ═══════════════════════════════════════

async function pollPaymentStatus(jid, paymentId, send, session) {
    let attempts = 0;
    const maxAttempts = 18; // 3 minutes

    const interval = setInterval(async () => {
        attempts++;
        try {
            const result = await api.getPaymentStatus(paymentId);

            if (result.status === 'paid') {
                clearInterval(interval);
                
                // Only reset state/send success if user hasn't started a new fresh order
                const currentSession = getSession(jid);
                if (currentSession.state === STATES.CYBER_PAYMENT_PENDING || 
                    currentSession.state === STATES.MARKET_PAYMENT_PENDING ||
                    currentSession.state === STATES.CYBER_TRACK || 
                    currentSession.state === STATES.MAIN_MENU) {
                    
                    clearTemp(jid);
                    setState(jid, STATES.MAIN_MENU);
                    await send(paymentSuccessMessage(
                        result.amount,
                        session.temp?.orderNumber
                    ));
                }
                return;
            }

            if (result.status === 'failed' || result.status === 'cancelled') {
                clearInterval(interval);
                const currentSession = getSession(jid);
                if (currentSession.state === STATES.CYBER_PAYMENT_PENDING || currentSession.state === STATES.MARKET_PAYMENT_PENDING) {
                    await send(paymentFailedMessage());
                }
                return;
            }

            if (attempts >= maxAttempts) {
                clearInterval(interval);
                const currentSession = getSession(jid);
                if (currentSession.state === STATES.CYBER_PAYMENT_PENDING || currentSession.state === STATES.MARKET_PAYMENT_PENDING) {
                    await send(paymentTimeoutMessage());
                }
            }
        } catch (err) {
            // Silently continue polling
        }
    }, 10000);
}


// ═══════════════════════════════════════
// ORDER PROGRESS VISUAL
// ═══════════════════════════════════════

function getOrderProgress(status) {
    const steps = [
        { key: 'pending', label: 'Imepokelewa', icon: '📋' },
        { key: 'approved', label: 'Imekubaliwa', icon: '✅' },
        { key: 'preparing', label: 'Inaandaliwa', icon: '🧑‍🍳' },
        { key: 'ready', label: 'Iko Tayari', icon: '🍽️' },
        { key: 'on_delivery', label: 'Njiani', icon: '🚴' },
        { key: 'delivered', label: 'Imefika', icon: '📬' },
    ];

    const currentIdx = steps.findIndex(s => s.key === status);
    let progress = '📍 *Hatua ya Order:*\n';

    steps.forEach((step, i) => {
        if (i < currentIdx) {
            progress += `  ✅ ~~${step.label}~~\n`;
        } else if (i === currentIdx) {
            progress += `  ${step.icon} *${step.label}* ◄── _Sasa hivi_\n`;
        } else {
            progress += `  ⬜ ${step.label}\n`;
        }
    });

    return progress;
}
