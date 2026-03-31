import { makeWASocket, useMultiFileAuthState, DisconnectReason, fetchLatestBaileysVersion } from 'baileys';
import pino from 'pino';
import qrcode from 'qrcode-terminal';
import { config } from './config.js';
import { handleMessage } from './handler.js';
import { api } from './api.js';

const logger = pino({ level: 'silent' });

/**
 * Monana WhatsApp Bot — Baileys v7
 * Advanced multi-service bot for Monana Platform
 */
async function startBot() {
    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    console.log('  🤖 MONANA WHATSAPP BOT v1.0');
    console.log('  📡 API: ' + config.apiBaseUrl);
    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

    // Health check — verify Laravel API is reachable
    try {
        const health = await api.healthCheck();
        console.log('  ✅ API Health:', health.status || 'connected');
    } catch (err) {
        console.warn('  ⚠️  API unreachable:', err.message);
        console.warn('  ⚠️  Bot will start but API calls may fail.');
    }

    // Load auth state
    const { state, saveCreds } = await useMultiFileAuthState(config.sessionDir);

    // Fetch latest Baileys version
    const { version } = await fetchLatestBaileysVersion();
    console.log('  📱 Baileys version:', version.join('.'));

    // Create socket
    const sock = makeWASocket({
        version,
        auth: state,
        logger,
        printQRInTerminal: false,
        browser: ['Monana Bot', 'Chrome', '1.0.0'],
        generateHighQualityLinkPreview: false,
        syncFullHistory: false,
    });

    // ─── Connection Events ───
    sock.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            console.log('\n  📱 Scan QR Code below to connect:\n');
            qrcode.generate(qr, { small: true });
            console.log('\n  Waiting for scan...\n');
        }

        if (connection === 'close') {
            const statusCode = lastDisconnect?.error?.output?.statusCode;
            const shouldReconnect = statusCode !== DisconnectReason.loggedOut;

            console.log(`  ❌ Connection closed. Status: ${statusCode}`);

            if (shouldReconnect) {
                console.log('  🔄 Reconnecting in 3 seconds...');
                setTimeout(startBot, 3000);
            } else {
                console.log('  🚪 Logged out. Delete auth_session folder and restart to re-scan.');
            }
        }

        if (connection === 'open') {
            console.log('  ✅ Bot connected successfully!');
            console.log('  🟢 Ready to receive messages.\n');
        }
    });

    // Save credentials on update
    sock.ev.on('creds.update', saveCreds);

    // ─── Message Handler ───
    sock.ev.on('messages.upsert', async ({ messages, type }) => {
        if (type !== 'notify') return;

        for (const msg of messages) {
            // Skip non-user messages
            if (!msg.message) continue;
            if (msg.key.fromMe) continue;
            if (msg.key.remoteJid?.endsWith('@g.us')) continue; // Skip group messages

            const jid = msg.key.remoteJid;
            const text = extractText(msg);

            if (!text) continue;

            console.log(`  📩 ${jid.split('@')[0]}: ${text.substring(0, 50)}${text.length > 50 ? '...' : ''}`);

            // Create a send helper bound to this conversation
            const send = async (content) => {
                try {
                    await sock.sendMessage(jid, { text: content });
                } catch (err) {
                    console.error(`  ❌ Send error to ${jid}:`, err.message);
                }
            };

            try {
                await handleMessage(jid, text, send);
            } catch (err) {
                console.error(`  ❌ Handler error for ${jid}:`, err.message);
                await send('⚠️ Samahani, kuna tatizo la mfumo. Tafadhali jaribu tena baadaye.\n\nTuma *menu* kuanza upya.');
            }
        }
    });
}

/**
 * Extract text content from various message types
 */
function extractText(msg) {
    const m = msg.message;
    if (!m) return null;

    // Standard text
    if (m.conversation) return m.conversation;
    if (m.extendedTextMessage?.text) return m.extendedTextMessage.text;

    // Button response
    if (m.buttonsResponseMessage?.selectedDisplayText) return m.buttonsResponseMessage.selectedDisplayText;
    if (m.listResponseMessage?.title) return m.listResponseMessage.title;
    if (m.templateButtonReplyMessage?.selectedDisplayText) return m.templateButtonReplyMessage.selectedDisplayText;

    // Image/video with caption (treat caption as text)
    if (m.imageMessage?.caption) return m.imageMessage.caption;
    if (m.videoMessage?.caption) return m.videoMessage.caption;

    return null;
}

// ─── Start ───
startBot().catch(err => {
    console.error('Fatal error:', err);
    process.exit(1);
});
