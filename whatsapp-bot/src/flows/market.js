import { api } from '../api.js';
import { STATES, setState, getSession, clearTemp } from '../state.js';
import {
    formatPackages,
    formatPackageDetail,
    formatCurrency,
    formatSubscriptionConfirmation,
    mainMenuMessage,
} from '../utils/formatter.js';
import { handlePaymentMethod } from './cyber.js';

/**
 * Handle Monana Market flow (packages, subscriptions, custom orders)
 */
export async function handleMarket(jid, text, send) {
    const session = getSession(jid);

    switch (session.state) {
        // ─── Market sub-menu ───
        case STATES.MARKET_MENU: {
            if (text === '0') {
                setState(jid, STATES.MAIN_MENU);
                await send(mainMenuMessage(session.userName));
                return true;
            }

            if (text === '1') {
                // Show packages
                setState(jid, STATES.MARKET_PACKAGES);
                return handleMarket(jid, '', send);
            }

            if (text === '2') {
                // Show products for custom order — simplified for now
                try {
                    const result = await api.getProducts();
                    const products = result.data || [];

                    if (products.length === 0) {
                        await send('⚠️ Hakuna bidhaa zinazopatikana kwa sasa.\n\nAndika *0* kurudi.');
                        setState(jid, STATES.MAIN_MENU);
                        return true;
                    }

                    let msg = `🛒 *Bidhaa za Sokoni:*\n\n`;
                    products.forEach((p, i) => {
                        msg += `${i + 1}️⃣  *${p.name}* — ${formatCurrency(p.price)}/${p.unit}\n`;
                    });
                    msg += `\n_Kwa sasa, agiza kupitia vifurushi vyetu au tembelea website._\n`;
                    msg += `🌐 monana.co.tz/food/custom\n\n`;
                    msg += `Andika *0* kurudi nyuma.`;

                    await send(msg);
                    setState(jid, STATES.MARKET_MENU);
                    return true;

                } catch (err) {
                    console.error('getProducts error:', err.message);
                    await send('⚠️ Imeshindwa kupata bidhaa. Jaribu tena.\n\nAndika *0* kurudi Menu Kuu.');
                    setState(jid, STATES.MARKET_MENU);
                    return true;
                }
            }

            // Show market sub-menu
            await send(
                `📦 *Monana Market*\n\n` +
                `Chagua:\n\n` +
                `1️⃣  *Vifurushi* (Wiki/Mwezi)\n` +
                `    _Subscribe na upokee delivery mara kwa mara_\n\n` +
                `2️⃣  *Bidhaa za Sokoni*\n` +
                `    _Angalia bidhaa zinazopatikana_\n\n` +
                `0️⃣  *Rudi Menu Kuu*\n\n` +
                `Andika *1*, *2*, au *0*.`
            );
            return true;
        }

        // ─── Show packages ───
        case STATES.MARKET_PACKAGES: {
            if (text === '0') {
                setState(jid, STATES.MARKET_MENU);
                return handleMarket(jid, '', send);
            }

            // If packages not loaded yet, fetch them
            if (!session.temp.packages) {
                try {
                    const result = await api.getPackages();
                    const packages = result.data || [];

                    if (packages.length === 0) {
                        await send('⚠️ Hakuna vifurushi vinavyopatikana kwa sasa.\n\nAndika *0* kurudi.');
                        setState(jid, STATES.MARKET_MENU);
                        return true;
                    }

                    setState(jid, STATES.MARKET_PACKAGES, { packages });
                    await send(formatPackages(packages));
                    return true;

                } catch (err) {
                    console.error('getPackages error:', err.message);
                    await send('⚠️ Imeshindwa kupata vifurushi. Jaribu tena.\n\nAndika *0* kurudi nyuma.');
                    setState(jid, STATES.MARKET_MENU);
                    return true;
                }
            }

            // User selecting a package
            const packages = session.temp.packages;
            const pkgIdx = parseInt(text) - 1;

            if (isNaN(pkgIdx) || pkgIdx < 0 || pkgIdx >= packages.length) {
                await send(`⚠️ Andika namba kati ya 1-${packages.length} au *0* kurudi.`);
                return true;
            }

            const selectedPkg = packages[pkgIdx];

            // Fetch full package details
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
                await send('⚠️ Imeshindwa kupata undani wa kifurushi. Jaribu tena.\n\nAndika *0* kurudi nyuma.');
                return true;
            }
        }

        // ─── Package detail — buy or go back ───
        case STATES.MARKET_PACKAGE_DETAIL: {
            if (text === '0') {
                // Go back to packages list
                setState(jid, STATES.MARKET_PACKAGES, { packages: session.temp.packages });
                await send(formatPackages(session.temp.packages));
                return true;
            }

            if (text === '1') {
                // Create subscription
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
                            startDate: subData.start_date,
                            endDate: subData.end_date,
                        });

                        await send(formatSubscriptionConfirmation(result));
                        return true;
                    } else {
                        await send(`⚠️ ${result.message || 'Imeshindwa kuunda subscription.'}`);
                        return true;
                    }
                } catch (err) {
                    const errMsg = err.response?.data?.message || err.message;
                    console.error('createSubscription error:', errMsg);
                    await send(
                        `⚠️ *Imeshindwa kuunda subscription:*\n${errMsg}\n\n` +
                        `1️⃣  *Jaribu tena*\n` +
                        `0️⃣  *Rudi nyuma*\n\n` +
                        `Andika *1* au *0*.`
                    );
                    return true;
                }
            }

            await send('⚠️ Andika *1* kununua au *0* kurudi nyuma.');
            return true;
        }

        // ─── Payment method for subscription ───
        case STATES.MARKET_PAYMENT_METHOD: {
            return handlePaymentMethod(jid, text, send, session, 'MARKET');
        }

        // ─── Payment pending ───
        case STATES.MARKET_PAYMENT_PENDING: {
            if (text === '0' || text.toLowerCase() === 'menu') {
                clearTemp(jid);
                setState(jid, STATES.MAIN_MENU);
                await send(mainMenuMessage(session.userName));
                return true;
            }
            await send('⏳ Bado tunasubiri malipo yako.\n\nAndika *0* kurudi Menu Kuu.');
            return true;
        }
    }

    return false;
}
