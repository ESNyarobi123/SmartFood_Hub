import axios from 'axios';
import { config } from './config.js';

/**
 * Monana API Client — communicates with Laravel backend
 */
class MonanaAPI {
    constructor() {
        this.client = axios.create({
            baseURL: config.apiBaseUrl,
            timeout: 15000,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${config.botToken}`,
            },
        });
    }

    // ═══════════════════════════════════════
    // USER & AUTH
    // ═══════════════════════════════════════

    async resolveUser(phoneNumber, whatsappJid) {
        const payload = { phone_number: phoneNumber };
        if (whatsappJid) payload.whatsapp_jid = whatsappJid;
        const { data } = await this.client.post('/user/resolve', payload);
        return data;
    }

    async registerUser({ name, phone, address, email, whatsappJid }) {
        const payload = { name, phone, address };
        if (email) payload.email = email;
        if (whatsappJid) payload.whatsapp_jid = whatsappJid;
        const { data } = await this.client.post('/user/register', payload);
        return data;
    }

    async getUser(userId) {
        const { data } = await this.client.get(`/user/${userId}`);
        return data;
    }

    async getUserStatus(userId) {
        const { data } = await this.client.get(`/user/${userId}/status`);
        return data;
    }

    // ═══════════════════════════════════════
    // MONANA FOOD (Cyber Cafe)
    // ═══════════════════════════════════════

    async getMealSlots() {
        const { data } = await this.client.get('/cyber/meal-slots');
        return data;
    }

    async getMenu(mealSlotId) {
        const params = mealSlotId ? { meal_slot_id: mealSlotId } : {};
        const { data } = await this.client.get('/cyber/menu', { params });
        return data;
    }

    async createCyberOrder({ userId, mealSlotId, items, deliveryAddress, notes }) {
        const { data } = await this.client.post('/cyber/order/create', {
            user_id: userId,
            meal_slot_id: mealSlotId,
            items,
            delivery_address: deliveryAddress,
            notes,
        });
        return data;
    }

    async getCyberOrder(orderId) {
        const { data } = await this.client.get(`/cyber/order/${orderId}`);
        return data;
    }

    async cancelCyberOrder(orderId) {
        const { data } = await this.client.post('/cyber/order/cancel', { order_id: orderId });
        return data;
    }

    async getCyberOrderHistory(userId) {
        const { data } = await this.client.get(`/cyber/orders/history/${userId}`);
        return data;
    }

    // ═══════════════════════════════════════
    // MONANA MARKET (Food Subscriptions)
    // ═══════════════════════════════════════

    async getProducts() {
        const { data } = await this.client.get('/food/products');
        return data;
    }

    async getPackages() {
        const { data } = await this.client.get('/food/packages');
        return data;
    }

    async getPackage(packageId) {
        const { data } = await this.client.get(`/food/packages/${packageId}`);
        return data;
    }

    async createSubscription({ userId, packageId, deliveryAddress, notes }) {
        const { data } = await this.client.post('/food/subscription/create', {
            user_id: userId,
            package_id: packageId,
            delivery_address: deliveryAddress,
            notes,
        });
        return data;
    }

    async getSubscription(subscriptionId) {
        const { data } = await this.client.get(`/food/subscription/${subscriptionId}`);
        return data;
    }

    async getSubscriptionHistory(userId) {
        const { data } = await this.client.get(`/food/subscriptions/history/${userId}`);
        return data;
    }

    async pauseSubscription(subscriptionId) {
        const { data } = await this.client.post('/food/subscription/pause', {
            subscription_id: subscriptionId,
        });
        return data;
    }

    async resumeSubscription(subscriptionId) {
        const { data } = await this.client.post('/food/subscription/resume', {
            subscription_id: subscriptionId,
        });
        return data;
    }

    async customizeSubscription({ subscriptionId, date, action, originalProductId, newProductId, quantity }) {
        const payload = {
            subscription_id: subscriptionId,
            date,
            action,
        };
        if (originalProductId) payload.original_product_id = originalProductId;
        if (newProductId) payload.new_product_id = newProductId;
        if (quantity) payload.quantity = quantity;
        const { data } = await this.client.post('/food/subscription/customize', payload);
        return data;
    }

    async createFoodOrder({ userId, items, deliveryAddress, notes }) {
        const { data } = await this.client.post('/food/order/create', {
            user_id: userId,
            items,
            delivery_address: deliveryAddress,
            notes,
        });
        return data;
    }

    async getFoodOrder(orderId) {
        const { data } = await this.client.get(`/food/order/${orderId}`);
        return data;
    }

    async getFoodOrderHistory(userId) {
        const { data } = await this.client.get(`/food/orders/history/${userId}`);
        return data;
    }

    async getCyberOrder(orderId) {
        const { data } = await this.client.get(`/cyber/order/${orderId}`);
        return data;
    }

    // ═══════════════════════════════════════
    // PAYMENT
    // ═══════════════════════════════════════

    async initiatePayment({ userId, type, id, method, phoneNumber }) {
        const { data } = await this.client.post('/payment/initiate', {
            user_id: userId,
            type,
            id,
            method: method || 'mpesa',
            phone_number: phoneNumber,
        });
        return data;
    }

    async getPaymentStatus(paymentId) {
        const { data } = await this.client.get(`/payment/${paymentId}`);
        return data;
    }

    // ═══════════════════════════════════════
    // NOTIFICATIONS
    // ═══════════════════════════════════════

    async getNotifications(userId) {
        const { data } = await this.client.get(`/notifications/${userId}`);
        return data;
    }

    // ═══════════════════════════════════════
    // SYSTEM
    // ═══════════════════════════════════════

    async healthCheck() {
        const { data } = await this.client.get('/system/health');
        return data;
    }

    async getBotConfig() {
        const { data } = await this.client.get('/config');
        return data;
    }
}

export const api = new MonanaAPI();
