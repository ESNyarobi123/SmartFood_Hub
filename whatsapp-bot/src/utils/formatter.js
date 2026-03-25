/**
 * Message formatting utilities for WhatsApp
 */

export function formatCurrency(amount) {
    return `TZS ${Number(amount).toLocaleString('en-US')}`;
}

export function statusEmoji(status) {
    const map = {
        pending: '🟡',
        approved: '🔵',
        preparing: '🟠',
        ready: '🟢',
        on_delivery: '🚴',
        delivered: '✅',
        cancelled: '❌',
        active: '🟢',
        paused: '⏸️',
        expired: '⏰',
        paid: '✅',
        failed: '❌',
        unpaid: '🟡',
    };
    return map[status] || '⚪';
}

export function statusLabel(status) {
    const map = {
        pending: 'Inasubiri',
        approved: 'Imekubaliwa',
        preparing: 'Inaandaliwa',
        ready: 'Iko Tayari',
        on_delivery: 'Njiani',
        delivered: 'Imefika',
        cancelled: 'Imeghairiwa',
        active: 'Inatumika',
        paused: 'Imesimamishwa',
        expired: 'Imeisha',
        paid: 'Imelipwa',
        failed: 'Imeshindwa',
        unpaid: 'Haijalipwa',
    };
    return map[status] || status;
}

export function mainMenuMessage(userName) {
    return `Habari *${userName}*! 👋\n\nKaribu *Monana Platform*. Chagua huduma:\n\n` +
        `1️⃣  *Monana Food* 🍽️\n` +
        `    _Mlo wa siku - Asubuhi/Mchana/Jioni_\n\n` +
        `2️⃣  *Monana Market* 📦\n` +
        `    _Vifurushi vya Wiki/Mwezi & Sokoni_\n\n` +
        `3️⃣  *Akaunti Yangu* 👤\n` +
        `    _Angalia status za order/vifurushi_\n\n` +
        `Andika *1*, *2*, au *3* kuchagua.`;
}

export function formatMealSlots(slots) {
    if (!slots || slots.length === 0) {
        return '⚠️ Hakuna muda wa mlo uliopo sasa hivi.';
    }

    let msg = `🕐 *Muda wa Mlo Uliopo:*\n\n`;
    slots.forEach((slot, i) => {
        const status = slot.is_open ? '🟢 Wazi' : '🔴 Imefungwa';
        msg += `${i + 1}️⃣  *${slot.display_name}*\n`;
        msg += `    ⏰ Delivery: ${slot.delivery_time}\n`;
        msg += `    ${status}`;
        if (slot.is_open && slot.time_remaining) {
            msg += ` — ${slot.time_remaining}`;
        }
        msg += `\n\n`;
    });
    msg += `Andika namba ya muda unaotaka (mfano *1*).`;
    return msg;
}

export function formatMenuItems(items, slotName) {
    if (!items || items.length === 0) {
        return '⚠️ Hakuna vyakula vinavyopatikana kwa muda huu.';
    }

    let msg = `🍽️ *Menu ya ${slotName || 'Leo'}:*\n\n`;
    items.forEach((item, i) => {
        msg += `${i + 1}️⃣  *${item.name}*`;
        if (item.description) msg += ` — _${item.description}_`;
        msg += `\n    💰 ${formatCurrency(item.price)}\n\n`;
    });
    msg += `Andika namba ya chakula (mfano *1*).\nAu andika *0* kurudi nyuma.`;
    return msg;
}

export function formatPackages(packages) {
    if (!packages || packages.length === 0) {
        return '⚠️ Hakuna vifurushi vinavyopatikana kwa sasa.';
    }

    let msg = `📦 *Vifurushi Vinavyopatikana:*\n\n`;
    packages.forEach((pkg, i) => {
        msg += `${i + 1}️⃣  *${pkg.name}*\n`;
        msg += `    💰 ${formatCurrency(pkg.base_price)}\n`;
        msg += `    📅 ${pkg.duration_type === 'weekly' ? 'Kila Wiki' : 'Kila Mwezi'} (siku ${pkg.duration_days})\n`;
        msg += `    🚚 Delivery: ${pkg.delivery_days}\n`;
        msg += `    📋 Bidhaa ${pkg.items_count}\n\n`;
    });
    msg += `Andika namba ya kifurushi (mfano *1*) kuona undani zaidi.\nAu andika *0* kurudi nyuma.`;
    return msg;
}

export function formatPackageDetail(pkg) {
    let msg = `📦 *${pkg.name}*\n\n`;
    msg += `💰 Bei: *${formatCurrency(pkg.base_price)}*\n`;
    msg += `📅 Muda: ${pkg.duration_type === 'weekly' ? 'Wiki 1' : 'Mwezi 1'} (siku ${pkg.duration_days})\n`;
    msg += `🚚 Delivery: ${pkg.delivery_days}\n\n`;

    if (pkg.items && pkg.items.length > 0) {
        msg += `📋 *Bidhaa Zilizomo:*\n`;
        pkg.items.forEach(item => {
            msg += `  ▪️ ${item.product_name} — ${item.quantity} ${item.unit}\n`;
        });
    }

    msg += `\n1️⃣  *Nunua Kifurushi Hiki*\n`;
    msg += `0️⃣  *Rudi Nyuma*\n\n`;
    msg += `Andika *1* kununua au *0* kurudi.`;
    return msg;
}

export function formatUserStatus(data) {
    let msg = `👤 *Akaunti Yangu*\n`;
    msg += `━━━━━━━━━━━━━━━━\n`;
    msg += `Jina: *${data.user.name}*\n`;
    msg += `Simu: ${data.user.phone}\n`;
    msg += `Eneo: ${data.user.address || 'Haijawekwa'}\n\n`;

    // Cyber orders
    if (data.cyber_orders && data.cyber_orders.length > 0) {
        msg += `🍽️ *Order za Monana Food:*\n`;
        data.cyber_orders.forEach(order => {
            msg += `  ${statusEmoji(order.status)} ${order.order_number} — ${statusLabel(order.status)} (${formatCurrency(order.total_amount)})\n`;
        });
        msg += `\n`;
    }

    // Food orders
    if (data.food_orders && data.food_orders.length > 0) {
        msg += `🛒 *Order za Monana Market:*\n`;
        data.food_orders.forEach(order => {
            msg += `  ${statusEmoji(order.status)} ${order.order_number} — ${statusLabel(order.status)} (${formatCurrency(order.total_amount)})\n`;
        });
        msg += `\n`;
    }

    // Subscriptions
    if (data.subscriptions && data.subscriptions.length > 0) {
        msg += `📦 *Vifurushi Vyangu:*\n`;
        data.subscriptions.forEach(sub => {
            msg += `  ${statusEmoji(sub.status)} *${sub.package_name}*\n`;
            msg += `    ${statusLabel(sub.status)} — Siku ${sub.days_remaining} zimebaki\n`;
            msg += `    📅 ${sub.start_date} → ${sub.end_date}\n`;
        });
        msg += `\n`;
    }

    if (data.summary.active_cyber_orders === 0 && data.summary.active_food_orders === 0 && data.summary.active_subscriptions === 0) {
        msg += `📭 Huna order au kifurushi kinachotumika kwa sasa.\n\n`;
    }

    msg += `Andika *0* kurudi kwenye Menu Kuu.`;
    return msg;
}

export function formatOrderConfirmation(order, type) {
    const prefix = type === 'cyber' ? '🍽️' : '🛒';
    return `${prefix} *Order Imepokelewa!*\n\n` +
        `📋 Namba: *${order.order_number}*\n` +
        `💰 Jumla: *${formatCurrency(order.total_amount)}*\n` +
        `📍 Status: ${statusEmoji(order.status)} ${statusLabel(order.status)}\n\n` +
        `Sasa tunahitaji malipo. Chagua njia ya kulipa:\n\n` +
        `1️⃣  *M-Pesa*\n` +
        `2️⃣  *Tigo Pesa*\n` +
        `3️⃣  *Airtel Money*\n\n` +
        `Andika *1*, *2*, au *3*.`;
}

export function formatSubscriptionConfirmation(sub) {
    return `📦 *Kifurushi Kimeundwa!*\n\n` +
        `📋 Kifurushi: *${sub.package_name || sub.data?.package_name}*\n` +
        `💰 Bei: *${formatCurrency(sub.amount || sub.data?.amount)}*\n` +
        `📅 Kuanzia: ${sub.start_date || sub.data?.start_date}\n` +
        `📅 Hadi: ${sub.end_date || sub.data?.end_date}\n\n` +
        `Sasa tunahitaji malipo. Chagua njia ya kulipa:\n\n` +
        `1️⃣  *M-Pesa*\n` +
        `2️⃣  *Tigo Pesa*\n` +
        `3️⃣  *Airtel Money*\n\n` +
        `Andika *1*, *2*, au *3*.`;
}

export function paymentSuccessMessage(amount, orderNumber) {
    return `✅ *Malipo Yamepokelewa!*\n\n` +
        `💰 Kiasi: *${formatCurrency(amount)}*\n` +
        `📋 Namba: *${orderNumber}*\n\n` +
        `Asante kwa kutumia Monana! Agizo lako linaandaliwa. 🎉\n\n` +
        `Andika *menu* kurudi kwenye Menu Kuu.`;
}

export function paymentPendingMessage() {
    return `⏳ *Inasubiri Malipo...*\n\n` +
        `Utapokea STK Push kwenye simu yako. Tafadhali weka PIN yako kulipa.\n\n` +
        `Tutakutumia ujumbe malipo yakipokelewa.`;
}
