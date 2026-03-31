/**
 * ✨ Monana WhatsApp Bot — Message Formatting
 * Beautiful, engaging Swahili UX with rich emojis
 */

// ═══════════════════════════════════════
// HELPERS
// ═══════════════════════════════════════

export function formatCurrency(amount) {
    return `TZS ${Number(amount).toLocaleString('en-US')}`;
}

export function statusEmoji(status) {
    const map = {
        pending: '🟡', approved: '🔵', preparing: '🧑‍🍳',
        ready: '✅', on_delivery: '🚴', delivered: '📬',
        cancelled: '❌', active: '💚', paused: '⏸️',
        expired: '⌛', paid: '💰', failed: '🚫', unpaid: '💳',
    };
    return map[status] || '⚪';
}

export function statusLabel(status) {
    const map = {
        pending: 'Inasubiri', approved: 'Imekubaliwa', preparing: 'Inaandaliwa',
        ready: 'Iko Tayari', on_delivery: 'Inakuja Kwako', delivered: 'Imefika',
        cancelled: 'Imeghairiwa', active: 'Inatumika', paused: 'Imesimamishwa',
        expired: 'Imeisha Muda', paid: 'Imelipwa', failed: 'Imeshindwa',
        unpaid: 'Haijalipwa',
    };
    return map[status] || status;
}

function line() { return `━━━━━━━━━━━━━━━━━━━━`; }
function thinLine() { return `┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄`; }

function numberEmoji(n) {
    const nums = ['0️⃣','1️⃣','2️⃣','3️⃣','4️⃣','5️⃣','6️⃣','7️⃣','8️⃣','9️⃣','🔟'];
    return nums[n] || `*${n}.*`;
}

// ═══════════════════════════════════════
// MAIN MENU
// ═══════════════════════════════════════

export function getGreeting(userName) {
    const hour = new Date().getHours();
    let salamu = 'Habari za wakati huu';
    if (hour >= 0 && hour < 12) salamu = 'Habari za asubuhi';
    else if (hour >= 12 && hour < 16) salamu = 'Habari za mchana';
    else if (hour >= 16 && hour < 19) salamu = 'Habari za jioni';
    else salamu = 'Usiku mwema';
    
    return userName ? `🌟 *${salamu}, ${userName}!*` : `🌟 *${salamu}!*`;
}

export function mainMenuMessage(userName) {
    return (
        `${getGreeting(userName)} 👋\n\n` +
        `😋 *Ni nini ungependa tufanye leo?*\n` +
        `${line()}\n\n` +

        `${numberEmoji(1)} 🥘 *Monana Food*\n` +
        `      _Agiza msosi mtamu kwa wakati_\n\n` +

        `${numberEmoji(2)} 🧺 *Monana Market*\n` +
        `      _Vifurushi na bidhaa za sokoni_\n\n` +

        `${numberEmoji(3)} 👤 *Akaunti Yangu*\n` +
        `      _Status, orders na usimamizi_\n\n` +

        `${thinLine()}\n` +
        `👇 _Tafadhali jibu kwa kutuma (1, 2 au 3)_`
    );
}

// ═══════════════════════════════════════
// MONANA FOOD (CYBER) — MEAL SLOTS
// ═══════════════════════════════════════

export function formatMealSlots(slots) {
    if (!slots || slots.length === 0) {
        return `😔 *Samahani!*\n\nHakuna muda wa mlo ulio wazi sasa hivi.\nRudi tena baadaye! 🕐`;
    }

    let msg = `🥘 *MONANA FOOD — Agiza Mlo*\n${line()}\n\n`;
    msg += `👇 _Chagua muda ungependa chakula kikufikie:_\n\n`;

    slots.forEach((slot, i) => {
        const icon = slot.is_open ? '🟢' : '🔴';
        msg += `${numberEmoji(i + 1)} ${icon} *${slot.display_name}*\n`;
        msg += `      ⌚ _Kufika kwako: ${slot.delivery_time}_\n`;
        if (slot.is_open && slot.time_remaining) {
            msg += `      ⏳ _Inafungwa baada ya: ${slot.time_remaining}_\n`;
        }
        msg += `\n`;
    });

    msg += `${thinLine()}\n`;
    msg += `👇 _Tuma namba kuchagua (mfano *1*)_\n`;
    msg += `${numberEmoji(0)}  🔙 _Rudi Menu_`;
    return msg;
}

// ═══════════════════════════════════════
// MONANA FOOD — MENU ITEMS
// ═══════════════════════════════════════

export function formatMenuItems(items, slotName) {
    if (!items || items.length === 0) {
        return `😔 Hakuna vyakula vinavyopatikana kwa sasa.\n\nTuma *0* kurudi.`;
    }

    let msg = `😋 *Menu ya ${slotName || 'Leo'}*\n${line()}\n\n`;

    items.forEach((item, i) => {
        msg += `${numberEmoji(i + 1)} 🍛 *${item.name}*\n`;
        if (item.description) msg += `      💡 _${item.description}_\n`;
        msg += `      🏷️ ${formatCurrency(item.price)}\n\n`;
    });

    msg += `${thinLine()}\n`;
    msg += `👇 _Tuma namba ya chakula (mfano *1*)_\n`;
    msg += `${numberEmoji(0)}  🔙 _Rudi_`;
    return msg;
}

// ═══════════════════════════════════════
// MONANA FOOD — CART
// ═══════════════════════════════════════

export function formatCart(cart, cartTotal) {
    let msg = `🛒 *KIKAPU CHAKO*\n${line()}\n\n`;

    cart.forEach((c, i) => {
        msg += `> 🍲 *${c.name}*\n`;
        msg += `> Kiasi: ${c.quantity} | Jumla: ${formatCurrency(c.total)}\n\n`;
    });

    msg += `\n${thinLine()}\n`;
    msg += `💎 *JUMLA: ${formatCurrency(cartTotal)}*\n`;
    msg += `${thinLine()}\n\n`;

    msg += `${numberEmoji(1)}  ➕ *Ongeza kingine*\n`;
    msg += `${numberEmoji(2)}  ✅ *Thibitisha & Agiza*\n`;
    msg += `${numberEmoji(0)}  ❌ *Ghairi*\n\n`;
    msg += `👇 _Tuma *1*, *2*, au *0*_`;
    return msg;
}

// ═══════════════════════════════════════
// ORDER CONFIRMATION
// ═══════════════════════════════════════

export function formatOrderConfirmation(order, type) {
    const icon = type === 'cyber' ? '🍽️' : '🛒';
    const title = type === 'cyber' ? 'MLO' : 'ORDER';

    return (
        `${icon} *${title} IMEPOKELEWA!* ✅\n` +
        `${line()}\n\n` +
        `> 📋 Namba: *${order.order_number}*\n` +
        `> 💰 Jumla: *${formatCurrency(order.total_amount)}*\n` +
        `> 📍 Status: ${statusEmoji(order.status)} ${statusLabel(order.status)}\n\n` +
        `${thinLine()}\n` +
        `💳 *Chagua njia ya kulipa:*\n\n` +
        `${numberEmoji(1)}  📲 *M-Pesa*\n` +
        `${numberEmoji(2)}  📲 *Tigo Pesa*\n` +
        `${numberEmoji(3)}  📲 *Airtel Money*\n\n` +
        `${numberEmoji(0)}  🔙 _Rudi Menu Kuu_\n\n` +
        `👇 _Tuma *1*, *2*, au *3* kulipa_`
    );
}

// ═══════════════════════════════════════
// MONANA MARKET — SUB MENU
// ═══════════════════════════════════════

export function formatMarketMenu(activeSub) {
    let msg = `🧺 *MONANA MARKET*\n${line()}\n\n`;

    if (activeSub) {
        const daysIcon = activeSub.days_remaining <= 3 ? '⚠️' : '📅';
        msg += `💚 *Plan Yako Inayotumika:*\n`;
        msg += `> 🎁 *${activeSub.package_name}*\n`;
        msg += `> ${daysIcon} Siku *${activeSub.days_remaining}* zimebaki\n`;
        msg += `> 🚚 ${activeSub.start_date} → ${activeSub.end_date}\n\n`;
    }

    msg += `_Chagua huduma:_\n\n`;

    if (activeSub) {
        msg += `${numberEmoji(1)}  📋 *Kifurushi Changu*\n`;
        msg += `      _Ona vitu unavyopokea_\n\n`;
        msg += `${numberEmoji(2)}  ✏️  *Badili Vitu vya Kesho*\n`;
        msg += `      _Swap au ondoa_\n\n`;
        msg += `${numberEmoji(3)}  ⚙️  *Simamia Kifurushi*\n`;
        msg += `      _Pause, Cancel au Upgrade_\n\n`;
        msg += `${numberEmoji(4)}  🆕 *Vifurushi Vipya*\n`;
        msg += `      _Angalia offers zingine_\n\n`;
        msg += `${numberEmoji(5)}  🛒 *Agiza Sokoni*\n`;
        msg += `      _Nunua bidhaa moja moja_\n\n`;
    } else {
        msg += `${numberEmoji(1)}  🎁 *Vifurushi (Packages)*\n`;
        msg += `      _Sajili mpango wa chakula wa wiki/mwezi_\n\n`;
        msg += `${numberEmoji(2)}  🍅 *Agiza Sokoni*\n`;
        msg += `      _Nunua bidhaa moja moja_\n\n`;
    }

    msg += `${numberEmoji(0)}  🔙 _Rudi Menu_\n\n`;
    msg += `👇 _Tuma namba kuchagua_`;
    return msg;
}

// ═══════════════════════════════════════
// PACKAGES LIST
// ═══════════════════════════════════════

export function formatPackages(packages) {
    if (!packages || packages.length === 0) {
        return `😔 Hakuna vifurushi vinavyopatikana kwa sasa.\n\nTuma *0* kurudi.`;
    }

    let msg = `📦 *VIFURUSHI VYETU*\n${line()}\n\n`;

    packages.forEach((pkg, i) => {
        const dur = pkg.duration_type === 'weekly' ? 'Kila Wiki' : 'Kila Mwezi';
        msg += `${numberEmoji(i + 1)} 🎁 *${pkg.name}*\n`;
        msg += `      💵 ${formatCurrency(pkg.base_price)} / ${dur}\n`;
        msg += `      🚚 Vyakuja mara ${pkg.delivery_days || 'kadhaa'} \n\n`;
    });

    msg += `${thinLine()}\n`;
    msg += `👇 _Tuma namba kuona zaidi (mfano *1*)_\n`;
    msg += `${numberEmoji(0)}  🔙 _Rudi_`;
    return msg;
}

// ═══════════════════════════════════════
// PACKAGE DETAIL
// ═══════════════════════════════════════

export function formatPackageDetail(pkg) {
    const dur = pkg.duration_type === 'weekly' ? 'Wiki 1' : 'Mwezi 1';

    let msg = `🎁 *${pkg.name}*\n${line()}\n\n`;
    msg += `💵 Bei: *${formatCurrency(pkg.base_price)}*\n`;
    msg += `⏳ Muda: ${dur} (siku ${pkg.duration_days})\n`;
    msg += `🚚 Kufikisha: mara ${pkg.deliveries_per_week || 'kadhaa'} kwa wiki\n\n`;

    if (pkg.items && pkg.items.length > 0) {
        msg += `📋 *Utapewa hivi:*\n`;
        pkg.items.forEach(item => {
            msg += `   🔸 ${item.product_name} — ${item.quantity} ${item.unit}\n`;
        });
    }

    msg += `\n${thinLine()}\n\n`;
    msg += `${numberEmoji(1)}  🛍️ *Chukua Kifurushi Hiki*\n`;
    msg += `${numberEmoji(0)}  🔙 *Rudi Nyuma*\n\n`;
    msg += `👇 _Tuma *1* kununua au *0* kurudi_`;
    return msg;
}

// ═══════════════════════════════════════
// SUBSCRIPTION CONFIRMATION
// ═══════════════════════════════════════

export function formatSubscriptionConfirmation(sub) {
    const pkgName = sub.package_name || sub.data?.package_name;
    const amount = sub.amount || sub.data?.amount;
    const startDate = sub.start_date || sub.data?.start_date;
    const endDate = sub.end_date || sub.data?.end_date;

    return (
        `📦 *KIFURUSHI KIMECHAGULIWA!*\n` +
        `${line()}\n\n` +
        `> 📋 Kifurushi: *${pkgName}*\n` +
        `> 💰 Bei: *${formatCurrency(amount)}*\n` +
        `> 📅 Muda: ${startDate} → ${endDate}\n\n` +
        `⚠️ *Hakijaanza bado* — lipa kwanza ili delivery ianze!\n\n` +
        `${thinLine()}\n` +
        `💳 *Chagua njia ya kulipa:*\n\n` +
        `${numberEmoji(1)}  📲 *M-Pesa*\n` +
        `${numberEmoji(2)}  📲 *Tigo Pesa*\n` +
        `${numberEmoji(3)}  📲 *Airtel Money*\n\n` +
        `👇 _Tuma *1*, *2*, au *3* kulipa_`
    );
}

// ═══════════════════════════════════════
// MY SUBSCRIPTION DETAIL
// ═══════════════════════════════════════

export function formatMySubscription(sub) {
    let msg = `📦 *KIFURUSHI CHANGU*\n${line()}\n\n`;

    // Status banner
    switch (sub.status) {
        case 'active':
            msg += `💚 *Status: KINATUMIKA*\n`;
            if (sub.days_remaining <= 3) {
                msg += `⚠️ _Siku ${sub.days_remaining} tu zimebaki!_\n`;
            } else {
                msg += `📅 Siku *${sub.days_remaining}* zimebaki\n`;
            }
            break;
        case 'pending':
            msg += `🟡 *Status: INASUBIRI MALIPO*\n`;
            msg += `💳 _Lipa ili delivery ianze_\n`;
            break;
        case 'paused':
            msg += `⏸️ *Status: KIMESIMAMISHWA*\n`;
            msg += `_Endelea tena ukitaka_\n`;
            break;
        case 'expired':
            msg += `⌛ *Status: KIMEISHA MUDA*\n`;
            msg += `_Subscribe upya kuendelea_\n`;
            break;
        default:
            msg += `${statusEmoji(sub.status)} *Status: ${statusLabel(sub.status)}*\n`;
    }

    msg += `\n📋 *${sub.package_name}*\n`;
    msg += `🚚 ${sub.start_date} → ${sub.end_date}\n\n`;

    // Items
    if (sub.items && sub.items.length > 0) {
        msg += `🧺 *Vitu Unavyopokea Kila Delivery:*\n`;
        sub.items.forEach(item => {
            msg += `   🔸 ${item.product_name} — ${item.quantity} ${item.unit}\n`;
        });
        msg += `\n`;
    }

    msg += `${thinLine()}\n`;
    msg += `${numberEmoji(0)}  🔙 _Rudi nyuma_\n\n`;
    msg += `👇 _Tuma *0* kurudi_`;
    return msg;
}

// ═══════════════════════════════════════
// SUBSCRIPTION MANAGEMENT
// ═══════════════════════════════════════

export function formatSubManage(sub) {
    let msg = `⚙️ *SIMAMIA KIFURUSHI*\n${line()}\n\n`;
    msg += `📦 *${sub.package_name}*\n`;
    msg += `${statusEmoji(sub.status)} ${statusLabel(sub.status)}\n\n`;

    if (sub.status === 'active') {
        msg += `${numberEmoji(1)}  ⏸️ *Simamisha* (Pause)\n`;
        msg += `      _Acha delivery kwa muda_\n\n`;
        msg += `${numberEmoji(2)}  ❌ *Ghairi* (Cancel)\n`;
        msg += `      _Sitisha kifurushi kabisa_\n\n`;
        msg += `${numberEmoji(3)}  🆙 *Upgrade*\n`;
        msg += `      _Badili kwenda kifurushi kingine_\n\n`;
    } else if (sub.status === 'paused') {
        msg += `${numberEmoji(1)}  ▶️ *Endelea* (Resume)\n`;
        msg += `      _Anza kupokea delivery tena_\n\n`;
        msg += `${numberEmoji(2)}  ❌ *Ghairi* (Cancel)\n`;
        msg += `      _Sitisha kifurushi kabisa_\n\n`;
    } else if (sub.status === 'expired') {
        msg += `⌛ Kifurushi hiki kimeisha muda.\n\n`;
        msg += `${numberEmoji(1)}  🆕 *Subscribe Upya*\n`;
        msg += `      _Chagua kifurushi kipya_\n\n`;
    } else if (sub.status === 'pending') {
        msg += `🟡 Inasubiri malipo.\n\n`;
        msg += `${numberEmoji(1)}  💳 *Lipa Sasa*\n\n`;
    }

    msg += `${numberEmoji(0)}  🔙 _Rudi nyuma_\n\n`;
    msg += `👇 _Tuma namba kuchagua_`;
    return msg;
}

// ═══════════════════════════════════════
// CUSTOMIZATION — SELECT ITEM
// ═══════════════════════════════════════

export function formatCustomizeItems(items, date) {
    let msg = `✏️ *BADILISHA VITU — ${date}*\n${line()}\n\n`;
    msg += `_Chagua kitu unachotaka kubadilisha:_\n\n`;

    items.forEach((item, i) => {
        msg += `${numberEmoji(i + 1)}  🔸 *${item.product_name}* — ${item.quantity} ${item.unit}\n`;
    });

    msg += `\n${thinLine()}\n`;
    msg += `${numberEmoji(items.length + 1)}  ⏸️ *Simamisha delivery yote ya siku hii*\n\n`;
    msg += `${numberEmoji(0)}  🔙 _Rudi nyuma_\n\n`;
    msg += `👇 _Tuma namba ya kitu_`;
    return msg;
}

// ═══════════════════════════════════════
// CUSTOMIZATION — ACTION FOR ITEM
// ═══════════════════════════════════════

export function formatCustomizeAction(itemName) {
    return (
        `✏️ *${itemName}*\n${thinLine()}\n\n` +
        `_Unataka kufanya nini?_\n\n` +
        `${numberEmoji(1)}  🔄 *Badilisha* (Swap)\n` +
        `      _Weka kitu kingine badala yake_\n\n` +
        `${numberEmoji(2)}  🗑️ *Ondoa* (Remove)\n` +
        `      _Usilete kitu hiki siku hii_\n\n` +
        `${numberEmoji(0)}  🔙 _Rudi nyuma_\n\n` +
        `👇 _Tuma *1*, *2*, au *0*_`
    );
}

// ═══════════════════════════════════════
// CUSTOMIZATION — SWAP OPTIONS
// ═══════════════════════════════════════

export function formatSwapOptions(products, originalName) {
    let msg = `🔄 *Badilisha ${originalName} na:*\n${thinLine()}\n\n`;

    products.forEach((p, i) => {
        msg += `${numberEmoji(i + 1)}  *${p.name}* — ${formatCurrency(p.price)}/${p.unit}\n`;
    });

    msg += `\n${numberEmoji(0)}  🔙 _Rudi nyuma_\n\n`;
    msg += `👇 _Tuma namba ya bidhaa_`;
    return msg;
}

// ═══════════════════════════════════════
// SOKONI — PRODUCTS LIST
// ═══════════════════════════════════════

export function formatSokoniProducts(products) {
    if (!products || products.length === 0) {
        return `😔 Hakuna bidhaa za sokoni kwa sasa.\n\nTuma *0* kurudi.`;
    }

    let msg = `🛒 *SOKONI — Jipatie Bidhaa*\n${line()}\n\n`;

    products.forEach((p, i) => {
        msg += `${numberEmoji(i + 1)} 🍅 *${p.name}*\n`;
        msg += `      🏷️ ${formatCurrency(p.price)} / ${p.unit}\n`;
        if (p.description) msg += `      💡 _${p.description}_\n`;
        msg += `\n`;
    });

    msg += `${thinLine()}\n`;
    msg += `👇 _Tuma namba ya bidhaa (mfano *1*)_\n`;
    msg += `${numberEmoji(0)}  🔙 _Rudi_`;
    return msg;
}

// ═══════════════════════════════════════
// SOKONI — CART
// ═══════════════════════════════════════

export function formatSokoniCart(cart, cartTotal) {
    let msg = `🛒 *KIKAPU CHA SOKONI*\n${line()}\n\n`;

    cart.forEach((c, i) => {
        msg += `> 🛍️ *${c.name}*\n`;
        msg += `> Kiasi: ${c.quantity} ${c.unit} | Jumla: ${formatCurrency(c.total)}\n\n`;
    });

    msg += `\n${thinLine()}\n`;
    msg += `💎 *JUMLA: ${formatCurrency(cartTotal)}*\n`;
    msg += `${thinLine()}\n\n`;

    msg += `${numberEmoji(1)}  ➕ *Ongeza bidhaa nyingine*\n`;
    msg += `${numberEmoji(2)}  ✅ *Thibitisha & Agiza*\n`;
    msg += `${numberEmoji(0)}  ❌ *Ghairi*\n\n`;
    msg += `👇 _Tuma *1*, *2*, au *0*_`;
    return msg;
}

// ═══════════════════════════════════════
// ACCOUNT STATUS (RICH)
// ═══════════════════════════════════════

export function formatUserStatus(data) {
    let msg = `👤 *AKAUNTI YANGU*\n${line()}\n`;
    msg += `Jina: *${data.user.name}*\n`;
    msg += `📱 ${data.user.phone}\n`;
    msg += `📍 ${data.user.address || 'Haijawekwa'}\n`;

    // ── Active Subscription Banner ──
    const activeSubs = (data.subscriptions || []).filter(s => s.status === 'active');
    if (activeSubs.length > 0) {
        msg += `\n💚 *PLAN YANGU:*\n`;
        activeSubs.forEach(sub => {
            const warn = sub.days_remaining <= 3 ? ' ⚠️' : '';
            msg += `> 📦 *${sub.package_name}*\n`;
            msg += `> 📅 Siku *${sub.days_remaining}* zimebaki${warn}\n`;
            msg += `> 🚚 ${sub.start_date} → ${sub.end_date}\n`;
        });
        msg += `\n`;
    }

    // ── Pending subs ──
    const pendingSubs = (data.subscriptions || []).filter(s => s.status === 'pending');
    if (pendingSubs.length > 0) {
        msg += `\n🟡 *Vifurushi Vinasubiri Malipo:*\n`;
        pendingSubs.forEach(sub => {
            msg += `  💳 ${sub.package_name} — _Lipa ili kianze!_\n`;
        });
    }

    // ── Paused subs ──
    const pausedSubs = (data.subscriptions || []).filter(s => s.status === 'paused');
    if (pausedSubs.length > 0) {
        msg += `\n⏸️ *Vimesimamishwa:*\n`;
        pausedSubs.forEach(sub => {
            msg += `  📦 ${sub.package_name} — _Tuma *2* kwenye Menu > Market kuendelea_\n`;
        });
    }

    // ── Expired subs ──
    const expiredSubs = (data.subscriptions || []).filter(s => s.status === 'expired');
    if (expiredSubs.length > 0) {
        msg += `\n⌛ *Vimeisha Muda:*\n`;
        expiredSubs.forEach(sub => {
            msg += `  📦 ${sub.package_name} — imeisha ${sub.expired_at || sub.end_date}\n`;
            msg += `     _Subscribe upya: Menu > Market_\n`;
        });
    }

    // ── Cyber orders ──
    if (data.cyber_orders && data.cyber_orders.length > 0) {
        msg += `\n🍽️ *Order za Monana Food:*\n`;
        data.cyber_orders.forEach(order => {
            msg += `  ${statusEmoji(order.status)} *${order.order_number}*\n`;
            msg += `    ${statusLabel(order.status)} • ${formatCurrency(order.total_amount)}\n`;
        });
    }

    // ── Food orders ──
    if (data.food_orders && data.food_orders.length > 0) {
        msg += `\n🛒 *Order za Sokoni:*\n`;
        data.food_orders.forEach(order => {
            msg += `  ${statusEmoji(order.status)} *${order.order_number}*\n`;
            msg += `    ${statusLabel(order.status)} • ${formatCurrency(order.total_amount)}\n`;
        });
    }

    // ── Empty state ──
    const hasNothing = (!data.cyber_orders || data.cyber_orders.length === 0) &&
                       (!data.food_orders || data.food_orders.length === 0) &&
                       (!data.subscriptions || data.subscriptions.length === 0);

    if (hasNothing) {
        msg += `\n📭 _Huna order au kifurushi kwa sasa._\n`;
        msg += `_Tuma *1* au *2* kwenye Menu Kuu kuanza!_\n`;
    }

    msg += `\n${thinLine()}\n`;
    msg += `${numberEmoji(0)}  🔙 _Rudi Menu Kuu_`;
    return msg;
}

// ═══════════════════════════════════════
// PAYMENT
// ═══════════════════════════════════════

export function paymentMethodMessage() {
    return (
        `💳 *CHAGUA NJIA YA KULIPA*\n${thinLine()}\n\n` +
        `${numberEmoji(1)}  📲 *M-Pesa*\n` +
        `${numberEmoji(2)}  📲 *Tigo Pesa*\n` +
        `${numberEmoji(3)}  📲 *Airtel Money*\n\n` +
        `${numberEmoji(0)}  🔙 _Rudi Menu Kuu_\n\n` +
        `👇 _Tuma *1*, *2*, *3*, au *0*_`
    );
}

export function paymentPendingMessage(phone, amount, method) {
    return (
        `⏳ *INASUBIRI MALIPO...*\n${line()}\n\n` +
        `> 📱 Simu: *${phone}*\n` +
        `> 💰 Kiasi: *${formatCurrency(amount)}*\n` +
        `> 📲 Njia: *${method.toUpperCase()}*\n\n` +
        `🔐 _Weka PIN kwenye simu yako kulipa._\n` +
        `✅ _Tutakutumia ujumbe malipo yakikamilika._`
    );
}

export function paymentSuccessMessage(amount, orderNumber) {
    return (
        `🎉 *MALIPO YAMEPOKELEWA!* ✅\n${line()}\n\n` +
        `💵 Kiasi: *${formatCurrency(amount)}*\n` +
        `📋 Namba: *${orderNumber || ''}*\n\n` +
        `Asante sana! 🌟\n` +
        `Tunaandaa agizo lako.\n\n` +
        `👇 _Tuma *menu* kurudi_`
    );
}

export function paymentFailedMessage() {
    return (
        `🚫 *MALIPO YAMESHINDWA*\n${thinLine()}\n\n` +
        `Malipo hayakukamilika. Jaribu tena:\n\n` +
        `${numberEmoji(1)}  📲 *M-Pesa*\n` +
        `${numberEmoji(2)}  📲 *Tigo Pesa*\n` +
        `${numberEmoji(3)}  📲 *Airtel Money*\n\n` +
        `${numberEmoji(0)}  🔙 _Rudi Menu Kuu_`
    );
}

export function paymentGatewayError() {
    return (
        `⚠️ *Mfumo wa Malipo Haujaandaliwa*\n${thinLine()}\n\n` +
        `Samahani, huduma ya mobile money bado haijawezeshwa.\n` +
        `Agizo lako limehifadhiwa — unaweza kulipa baadaye.\n\n` +
        `📞 _Wasiliana nasi kwa msaada_\n\n` +
        `${numberEmoji(0)}  🔙 _Rudi Menu Kuu_`
    );
}

export function paymentTimeoutMessage() {
    return (
        `⏰ *Muda wa kusubiri umekwisha*\n${thinLine()}\n\n` +
        `Kama umelipa, subiri dakika chache.\n` +
        `Tutakutumia ujumbe malipo yakipokelewa. ✅\n\n` +
        `👇 _Tuma *menu* kurudi Menu Kuu_`
    );
}
