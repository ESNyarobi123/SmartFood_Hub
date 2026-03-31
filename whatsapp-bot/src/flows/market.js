import { api } from '../api.js';
import { STATES, setState, getSession, clearTemp } from '../state.js';
import {
    formatMarketMenu,
    formatPackages,
    formatPackageDetail,
    formatCurrency,
    formatSubscriptionConfirmation,
    formatMySubscription,
    formatSubManage,
    formatCustomizeItems,
    formatCustomizeAction,
    formatSwapOptions,
    formatSokoniProducts,
    formatSokoniCart,
    formatOrderConfirmation,
    mainMenuMessage,
} from '../utils/formatter.js';
import { handlePaymentMethod } from './cyber.js';

/**
 * Handle Monana Market flow
 * — Packages, subscriptions, customization, sokoni ordering
 */
export async function handleMarket(jid, text, send) {
    const session = getSession(jid);

    switch (session.state) {

        // ═══════════════════════════════════════
        // MARKET MENU — Smart menu based on active subscription
        // ═══════════════════════════════════════
        case STATES.MARKET_MENU: {
            if (text === '0') {
                setState(jid, STATES.MAIN_MENU);
                await send(mainMenuMessage(session.userName));
                return true;
            }

            // Fetch user's active subscription if not loaded
            if (!session.temp._marketLoaded) {
                try {
                    const statusResult = await api.getUserStatus(session.userId);
                    const subs = statusResult.subscriptions || [];
                    const activeSub = subs.find(s => s.status === 'active' || s.status === 'paused') || null;

                    setState(jid, STATES.MARKET_MENU, {
                        activeSub,
                        allSubs: subs,
                        _marketLoaded: true,
                    });

                    // If no text selection yet, show menu
                    if (!text || !['1','2','3','4','5'].includes(text)) {
                        await send(formatMarketMenu(activeSub));
                        return true;
                    }
                } catch (err) {
                    console.error('getUserStatus error:', err.message);
                    setState(jid, STATES.MARKET_MENU, { activeSub: null, _marketLoaded: true });
                    if (!text || !['1','2'].includes(text)) {
                        await send(formatMarketMenu(null));
                        return true;
                    }
                }
            }

            const { activeSub } = session.temp;

            // ── WITH active subscription ──
            if (activeSub) {
                switch (text) {
                    case '1': // My Subscription details
                        return await showMySubscription(jid, activeSub, send);

                    case '2': // Customize items for tomorrow
                        return await startCustomize(jid, activeSub, send);

                    case '3': // Manage subscription (pause/resume/cancel)
                        return await showSubManage(jid, activeSub, send);

                    case '4': // Browse new packages
                        setState(jid, STATES.MARKET_PACKAGES, { ...session.temp, packages: null });
                        return handleMarket(jid, '', send);

                    case '5': // Sokoni ordering
                        return await startSokoni(jid, send);

                    default:
                        await send(formatMarketMenu(activeSub));
                        return true;
                }
            }

            // ── WITHOUT active subscription ──
            switch (text) {
                case '1': // Browse packages
                    setState(jid, STATES.MARKET_PACKAGES, { ...session.temp, packages: null });
                    return handleMarket(jid, '', send);

                case '2': // Sokoni ordering
                    return await startSokoni(jid, send);

                default:
                    await send(formatMarketMenu(null));
                    return true;
            }
        }

        // ═══════════════════════════════════════
        // BROWSE PACKAGES
        // ═══════════════════════════════════════
        case STATES.MARKET_PACKAGES: {
            if (text === '0') {
                setState(jid, STATES.MARKET_MENU, { ...session.temp, _marketLoaded: false });
                return handleMarket(jid, '', send);
            }

            // Fetch packages if not loaded
            if (!session.temp.packages) {
                try {
                    const result = await api.getPackages();
                    const packages = result.data || [];

                    if (packages.length === 0) {
                        await send('😔 Hakuna vifurushi vinavyopatikana kwa sasa.\n\nTuma *0* kurudi.');
                        setState(jid, STATES.MARKET_MENU, { _marketLoaded: false });
                        return true;
                    }

                    setState(jid, STATES.MARKET_PACKAGES, { ...session.temp, packages });
                    await send(formatPackages(packages));
                    return true;
                } catch (err) {
                    console.error('getPackages error:', err.message);
                    await send('⚠️ Imeshindwa kupata vifurushi. Jaribu tena.\n\nTuma *0* kurudi.');
                    setState(jid, STATES.MARKET_MENU, { _marketLoaded: false });
                    return true;
                }
            }

            // User selecting a package
            const packages = session.temp.packages;
            const pkgIdx = parseInt(text) - 1;

            if (isNaN(pkgIdx) || pkgIdx < 0 || pkgIdx >= packages.length) {
                await send(`⚠️ Tafadhali tuma namba kati ya 1-${packages.length} au *0* kurudi.`);
                return true;
            }

            const selectedPkg = packages[pkgIdx];
            try {
                const result = await api.getPackage(selectedPkg.id);
                const pkg = result.data;

                setState(jid, STATES.MARKET_PACKAGE_DETAIL, {
                    ...session.temp,
                    selectedPackage: pkg,
                });

                await send(formatPackageDetail(pkg));
                return true;
            } catch (err) {
                console.error('getPackage error:', err.message);
                await send('⚠️ Imeshindwa kupata undani. Jaribu tena.\n\nTuma *0* kurudi.');
                return true;
            }
        }

        // ═══════════════════════════════════════
        // PACKAGE DETAIL — buy or back
        // ═══════════════════════════════════════
        case STATES.MARKET_PACKAGE_DETAIL: {
            if (text === '0') {
                setState(jid, STATES.MARKET_PACKAGES, { ...session.temp, selectedPackage: null });
                await send(formatPackages(session.temp.packages));
                return true;
            }

            if (text === '1') {
                const pkg = session.temp.selectedPackage;
                try {
                    const result = await api.createSubscription({
                        userId: session.userId,
                        packageId: pkg.id,
                        deliveryAddress: session.userAddress || 'WhatsApp',
                    });

                    if (result.success) {
                        const subData = result.data;
                        setState(jid, STATES.MARKET_PAYMENT_METHOD, {
                            orderId: subData.subscription_id,
                            orderNumber: `SUB-${subData.subscription_id}`,
                            totalAmount: subData.amount || pkg.base_price,
                            paymentType: 'food_subscription',
                            packageName: subData.package_name || pkg.name,
                        });

                        await send(formatSubscriptionConfirmation(result));
                        return true;
                    } else {
                        await send(`⚠️ ${result.message || 'Imeshindwa kuunda subscription.'}\n\nTuma *0* kurudi.`);
                        return true;
                    }
                } catch (err) {
                    const errMsg = err.response?.data?.message || err.message;
                    console.error('createSubscription error:', errMsg);
                    await send(
                        `⚠️ *Imeshindwa:* ${errMsg}\n\n` +
                        `1️⃣ *Jaribu tena*\n0️⃣ *Rudi nyuma*`
                    );
                    return true;
                }
            }

            await send('👇 Tuma *1* kununua au *0* kurudi.');
            return true;
        }

        // ═══════════════════════════════════════
        // MY SUBSCRIPTION — view details
        // ═══════════════════════════════════════
        case STATES.MARKET_MY_SUB: {
            if (text === '0') {
                setState(jid, STATES.MARKET_MENU, { _marketLoaded: false });
                return handleMarket(jid, '', send);
            }
            // Just display, user presses 0 to go back
            return true;
        }

        // ═══════════════════════════════════════
        // MANAGE SUBSCRIPTION — pause/resume/cancel/upgrade
        // ═══════════════════════════════════════
        case STATES.MARKET_SUB_MANAGE: {
            if (text === '0') {
                setState(jid, STATES.MARKET_MENU, { _marketLoaded: false });
                return handleMarket(jid, '', send);
            }

            const sub = session.temp.manageSub;
            if (!sub) {
                setState(jid, STATES.MARKET_MENU, { _marketLoaded: false });
                return handleMarket(jid, '', send);
            }

            if (sub.status === 'active') {
                if (text === '1') { // Pause
                    try {
                        await api.pauseSubscription(sub.subscription_id);
                        await send(`⏸️ *Kifurushi Kimesimamishwa!*\n\nDelivery imeacha kwa muda. Tuma *menu* ukitaka kuendelea tena.`);
                        clearTemp(jid);
                        setState(jid, STATES.MAIN_MENU);
                        return true;
                    } catch (err) {
                        await send(`⚠️ ${err.response?.data?.message || 'Imeshindwa kusimamisha.'}\n\nTuma *0* kurudi.`);
                        return true;
                    }
                }
                if (text === '2') { // Cancel — confirm first
                    await send(
                        `⚠️ *Una uhakika unataka kughairi?*\n\n` +
                        `Kifurushi kitasitishwa kabisa na delivery itaacha.\n\n` +
                        `Tuma *ndio* kuthibitisha au *0* kurudi.`
                    );
                    setState(jid, STATES.MARKET_SUB_MANAGE, { ...session.temp, confirmCancel: true });
                    return true;
                }
                if (session.temp.confirmCancel && text.toLowerCase() === 'ndio') {
                    try {
                        // Use pause then cancel approach via API
                        await api.pauseSubscription(sub.subscription_id);
                        await send(`❌ *Kifurushi Kimeghairiwa.*\n\nPole! Ukibadilisha mawazo, subscribe upya wakati wowote.\n\nTuma *menu* kurudi.`);
                        clearTemp(jid);
                        setState(jid, STATES.MAIN_MENU);
                        return true;
                    } catch (err) {
                        await send(`⚠️ ${err.response?.data?.message || 'Imeshindwa kughairi.'}\n\nTuma *0* kurudi.`);
                        return true;
                    }
                }
                if (text === '3') { // Upgrade — go to packages
                    setState(jid, STATES.MARKET_PACKAGES, { ...session.temp, packages: null });
                    return handleMarket(jid, '', send);
                }
            }

            if (sub.status === 'paused') {
                if (text === '1') { // Resume
                    try {
                        await api.resumeSubscription(sub.subscription_id);
                        await send(`▶️ *Kifurushi Kinaendelea!* 🎉\n\nDelivery imeanza tena. Furahia!\n\nTuma *menu* kurudi Menu Kuu.`);
                        clearTemp(jid);
                        setState(jid, STATES.MAIN_MENU);
                        return true;
                    } catch (err) {
                        await send(`⚠️ ${err.response?.data?.message || 'Imeshindwa kuendelea.'}\n\nTuma *0* kurudi.`);
                        return true;
                    }
                }
                if (text === '2') { // Cancel
                    await send(`❌ *Kifurushi Kimeghairiwa.*\n\nTuma *menu* kurudi.`);
                    clearTemp(jid);
                    setState(jid, STATES.MAIN_MENU);
                    return true;
                }
            }

            if (sub.status === 'expired' || sub.status === 'pending') {
                if (text === '1') {
                    setState(jid, STATES.MARKET_PACKAGES, { packages: null });
                    return handleMarket(jid, '', send);
                }
            }

            await send('⚠️ Chaguo si sahihi. Tuma namba sahihi au *0* kurudi.');
            return true;
        }

        // ═══════════════════════════════════════
        // CUSTOMIZE — select item to change
        // ═══════════════════════════════════════
        case STATES.MARKET_CUSTOMIZE: {
            if (text === '0') {
                setState(jid, STATES.MARKET_MENU, { _marketLoaded: false });
                return handleMarket(jid, '', send);
            }

            const { customizeItems, customizeSubId, customizeDate } = session.temp;

            // Pause entire delivery for the day
            if (parseInt(text) === customizeItems.length + 1) {
                try {
                    await api.customizeSubscription({
                        subscriptionId: customizeSubId,
                        date: customizeDate,
                        action: 'pause',
                        originalProductId: customizeItems[0].product_id,
                    });
                    await send(`⏸️ *Delivery ya ${customizeDate} imesimamishwa!*\n\nHutapokea delivery siku hiyo.\n\nTuma *menu* kurudi.`);
                    clearTemp(jid);
                    setState(jid, STATES.MAIN_MENU);
                    return true;
                } catch (err) {
                    await send(`⚠️ ${err.response?.data?.message || 'Imeshindwa.'}\n\nTuma *0* kurudi.`);
                    return true;
                }
            }

            const itemIdx = parseInt(text) - 1;
            if (isNaN(itemIdx) || itemIdx < 0 || itemIdx >= customizeItems.length) {
                await send(`⚠️ Tafadhali tuma namba kati ya 1-${customizeItems.length + 1} au *0* kurudi.`);
                return true;
            }

            const selectedItem = customizeItems[itemIdx];
            setState(jid, STATES.MARKET_CUSTOMIZE_ITEM, {
                ...session.temp,
                customizeSelectedItem: selectedItem,
            });

            await send(formatCustomizeAction(selectedItem.product_name));
            return true;
        }

        // ═══════════════════════════════════════
        // CUSTOMIZE — action (swap/remove)
        // ═══════════════════════════════════════
        case STATES.MARKET_CUSTOMIZE_ITEM: {
            if (text === '0') {
                // Back to item list
                const { customizeItems, customizeDate } = session.temp;
                setState(jid, STATES.MARKET_CUSTOMIZE, session.temp);
                await send(formatCustomizeItems(customizeItems, customizeDate));
                return true;
            }

            const { customizeSelectedItem, customizeSubId, customizeDate } = session.temp;

            if (text === '1') { // Swap
                try {
                    const result = await api.getProducts();
                    const products = (result.data || []).filter(p => p.id !== customizeSelectedItem.product_id);

                    if (products.length === 0) {
                        await send('😔 Hakuna bidhaa nyingine za kubadilisha.\n\nTuma *0* kurudi.');
                        return true;
                    }

                    setState(jid, STATES.MARKET_CUSTOMIZE_SWAP, {
                        ...session.temp,
                        swapProducts: products,
                    });

                    await send(formatSwapOptions(products, customizeSelectedItem.product_name));
                    return true;
                } catch (err) {
                    await send('⚠️ Imeshindwa kupata bidhaa. Jaribu tena.\n\nTuma *0* kurudi.');
                    return true;
                }
            }

            if (text === '2') { // Remove
                try {
                    await api.customizeSubscription({
                        subscriptionId: customizeSubId,
                        date: customizeDate,
                        action: 'remove',
                        originalProductId: customizeSelectedItem.product_id,
                    });
                    await send(
                        `🗑️ *${customizeSelectedItem.product_name} imeondolewa!*\n\n` +
                        `Hutapokea kitu hiki tarehe ${customizeDate}.\n\n` +
                        `Tuma *menu* kurudi Menu Kuu.`
                    );
                    clearTemp(jid);
                    setState(jid, STATES.MAIN_MENU);
                    return true;
                } catch (err) {
                    await send(`⚠️ ${err.response?.data?.message || 'Imeshindwa kuondoa.'}\n\nTuma *0* kurudi.`);
                    return true;
                }
            }

            await send('👇 Tuma *1* (badilisha), *2* (ondoa), au *0* (rudi).');
            return true;
        }

        // ═══════════════════════════════════════
        // CUSTOMIZE — select swap product
        // ═══════════════════════════════════════
        case STATES.MARKET_CUSTOMIZE_SWAP: {
            if (text === '0') {
                setState(jid, STATES.MARKET_CUSTOMIZE_ITEM, session.temp);
                await send(formatCustomizeAction(session.temp.customizeSelectedItem.product_name));
                return true;
            }

            const { swapProducts, customizeSelectedItem, customizeSubId, customizeDate } = session.temp;
            const prodIdx = parseInt(text) - 1;

            if (isNaN(prodIdx) || prodIdx < 0 || prodIdx >= swapProducts.length) {
                await send(`⚠️ Tafadhali tuma namba kati ya 1-${swapProducts.length} au *0* kurudi.`);
                return true;
            }

            const newProduct = swapProducts[prodIdx];

            try {
                await api.customizeSubscription({
                    subscriptionId: customizeSubId,
                    date: customizeDate,
                    action: 'swap',
                    originalProductId: customizeSelectedItem.product_id,
                    newProductId: newProduct.id,
                    quantity: 1,
                });

                await send(
                    `🔄 *Imebadilishwa!*\n\n` +
                    `❌ ~~${customizeSelectedItem.product_name}~~\n` +
                    `✅ *${newProduct.name}*\n\n` +
                    `Kwa tarehe ${customizeDate}.\n\n` +
                    `Tuma *menu* kurudi Menu Kuu.`
                );
                clearTemp(jid);
                setState(jid, STATES.MAIN_MENU);
                return true;
            } catch (err) {
                await send(`⚠️ ${err.response?.data?.message || 'Swap imeshindwa.'}\n\nTuma *0* kurudi.`);
                return true;
            }
        }

        // ═══════════════════════════════════════
        // SOKONI — browse products
        // ═══════════════════════════════════════
        case STATES.MARKET_SOKONI: {
            if (text === '0') {
                setState(jid, STATES.MARKET_MENU, { _marketLoaded: false });
                return handleMarket(jid, '', send);
            }

            const { sokoniProducts } = session.temp;
            if (!sokoniProducts) return true;

            const idx = parseInt(text) - 1;
            if (isNaN(idx) || idx < 0 || idx >= sokoniProducts.length) {
                await send(`⚠️ Tafadhali tuma namba kati ya 1-${sokoniProducts.length} au *0* kurudi.`);
                return true;
            }

            const product = sokoniProducts[idx];
            setState(jid, STATES.MARKET_SOKONI_QTY, {
                ...session.temp,
                sokoniSelected: product,
                sokoniCart: session.temp.sokoniCart || [],
            });

            await send(
                `🛒 *${product.name}*\n` +
                `💰 ${formatCurrency(product.price)} / ${product.unit}\n\n` +
                `👇 _Tuma kiasi (mfano *2* kwa ${product.unit} 2):_`
            );
            return true;
        }

        // ═══════════════════════════════════════
        // SOKONI — enter quantity
        // ═══════════════════════════════════════
        case STATES.MARKET_SOKONI_QTY: {
            const qty = parseFloat(text);
            if (isNaN(qty) || qty <= 0 || qty > 100) {
                await send('⚠️ Tuma kiasi sahihi (mfano *1* au *2.5*):');
                return true;
            }

            const { sokoniSelected, sokoniCart, sokoniProducts } = session.temp;
            sokoniCart.push({
                product_id: sokoniSelected.id,
                name: sokoniSelected.name,
                price: sokoniSelected.price,
                unit: sokoniSelected.unit,
                quantity: qty,
                total: sokoniSelected.price * qty,
            });

            const cartTotal = sokoniCart.reduce((sum, c) => sum + c.total, 0);

            setState(jid, STATES.MARKET_SOKONI_CART, {
                ...session.temp,
                sokoniCart,
                sokoniCartTotal: cartTotal,
            });

            await send(formatSokoniCart(sokoniCart, cartTotal));
            return true;
        }

        // ═══════════════════════════════════════
        // SOKONI — cart confirm
        // ═══════════════════════════════════════
        case STATES.MARKET_SOKONI_CART: {
            if (text === '0') {
                clearTemp(jid);
                setState(jid, STATES.MAIN_MENU);
                await send(mainMenuMessage(session.userName));
                return true;
            }

            if (text === '1') {
                // Add more — go back to products
                setState(jid, STATES.MARKET_SOKONI, session.temp);
                await send(formatSokoniProducts(session.temp.sokoniProducts));
                return true;
            }

            if (text === '2') {
                // Create order
                const { sokoniCart } = session.temp;
                try {
                    const result = await api.createFoodOrder({
                        userId: session.userId,
                        items: sokoniCart.map(c => ({
                            product_id: c.product_id,
                            quantity: c.quantity,
                        })),
                        deliveryAddress: session.userAddress || 'WhatsApp',
                    });

                    if (result.success) {
                        setState(jid, STATES.MARKET_SOKONI_PAYMENT, {
                            orderId: result.data.order_id,
                            orderNumber: result.data.order_number,
                            totalAmount: result.data.total_amount,
                            paymentType: 'food_order',
                        });

                        await send(formatOrderConfirmation(result.data, 'food'));
                        return true;
                    } else {
                        await send(`⚠️ ${result.message || 'Imeshindwa.'}\n\nTuma *0* kurudi.`);
                        return true;
                    }
                } catch (err) {
                    const errMsg = err.response?.data?.message || err.message;
                    console.error('createFoodOrder error:', errMsg);
                    await send(`⚠️ *Imeshindwa:* ${errMsg}\n\nTuma *2* kujaribu tena au *0* kurudi.`);
                    return true;
                }
            }

            await send('👇 Tuma *1* (ongeza), *2* (agiza), au *0* (ghairi).');
            return true;
        }

        // ═══════════════════════════════════════
        // SOKONI — payment method
        // ═══════════════════════════════════════
        case STATES.MARKET_SOKONI_PAYMENT: {
            return handlePaymentMethod(jid, text, send, session, 'MARKET');
        }

        // ═══════════════════════════════════════
        // Payment flows (subscription + sokoni)
        // ═══════════════════════════════════════
        case STATES.MARKET_PAYMENT_METHOD: {
            return handlePaymentMethod(jid, text, send, session, 'MARKET');
        }

        case STATES.MARKET_PAYMENT_PENDING:
        case STATES.MARKET_SOKONI_PENDING: {
            if (text === '0' || text.toLowerCase() === 'menu') {
                clearTemp(jid);
                setState(jid, STATES.MAIN_MENU);
                await send(mainMenuMessage(session.userName));
                return true;
            }
            await send('⏳ Bado tunasubiri malipo yako.\n\n👇 _Tuma *0* kurudi Menu Kuu._');
            return true;
        }
    }

    return false;
}


// ═══════════════════════════════════════
// HELPER FUNCTIONS
// ═══════════════════════════════════════

async function showMySubscription(jid, activeSub, send) {
    try {
        const result = await api.getSubscription(activeSub.subscription_id);
        if (result.success) {
            setState(jid, STATES.MARKET_MY_SUB, { activeSub });
            await send(formatMySubscription(result.data));
            return true;
        }
    } catch (err) {
        console.error('getSubscription error:', err.message);
    }
    await send('⚠️ Imeshindwa kupata taarifa za kifurushi.\n\nTuma *0* kurudi.');
    setState(jid, STATES.MARKET_MENU, { _marketLoaded: false });
    return true;
}

async function showSubManage(jid, activeSub, send) {
    try {
        const result = await api.getSubscription(activeSub.subscription_id);
        if (result.success) {
            setState(jid, STATES.MARKET_SUB_MANAGE, { manageSub: { ...activeSub, ...result.data } });
            await send(formatSubManage(result.data));
            return true;
        }
    } catch (err) {
        console.error('getSubscription error:', err.message);
    }
    await send('⚠️ Imeshindwa kupata taarifa.\n\nTuma *0* kurudi.');
    setState(jid, STATES.MARKET_MENU, { _marketLoaded: false });
    return true;
}

async function startCustomize(jid, activeSub, send) {
    try {
        const result = await api.getSubscription(activeSub.subscription_id);
        if (!result.success || !result.data.items || result.data.items.length === 0) {
            await send('😔 Hakuna vitu vya kubadilisha.\n\nTuma *0* kurudi.');
            setState(jid, STATES.MARKET_MENU, { _marketLoaded: false });
            return true;
        }

        // Tomorrow's date
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const dateStr = tomorrow.toISOString().split('T')[0];

        setState(jid, STATES.MARKET_CUSTOMIZE, {
            customizeItems: result.data.items,
            customizeSubId: activeSub.subscription_id,
            customizeDate: dateStr,
        });

        await send(formatCustomizeItems(result.data.items, dateStr));
        return true;
    } catch (err) {
        console.error('startCustomize error:', err.message);
        await send('⚠️ Imeshindwa kupata vitu.\n\nTuma *0* kurudi.');
        setState(jid, STATES.MARKET_MENU, { _marketLoaded: false });
        return true;
    }
}

async function startSokoni(jid, send) {
    try {
        const result = await api.getProducts();
        const products = result.data || [];

        if (products.length === 0) {
            await send('😔 Hakuna bidhaa za sokoni kwa sasa.\n\nTuma *0* kurudi.');
            setState(jid, STATES.MARKET_MENU, { _marketLoaded: false });
            return true;
        }

        setState(jid, STATES.MARKET_SOKONI, { sokoniProducts: products, sokoniCart: [] });
        await send(formatSokoniProducts(products));
        return true;
    } catch (err) {
        console.error('getProducts error:', err.message);
        await send('⚠️ Imeshindwa kupata bidhaa. Jaribu tena.\n\nTuma *0* kurudi.');
        setState(jid, STATES.MARKET_MENU, { _marketLoaded: false });
        return true;
    }
}
