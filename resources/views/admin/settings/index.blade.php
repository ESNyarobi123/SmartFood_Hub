@extends('admin.layout')

@section('title', 'Settings')

@section('content')
<div class="space-y-8">

    {{-- ═══════════════════════════════════════ --}}
    {{-- PAGE HEADER --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold bg-clip-text text-transparent" style="background-image: var(--gradient-purple);">
                ⚙️ Settings
            </h1>
            <p class="text-[var(--text-secondary)] mt-1">Manage platform configuration, API keys & operations</p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- DAILY OPERATIONS LINK --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="card p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 opacity-10" style="background: radial-gradient(circle, var(--accent-cyber) 0%, transparent 70%);"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(0,255,200,0.15), rgba(0,217,245,0.08)); border: 1px solid rgba(0,255,200,0.3);">
                    <svg class="w-6 h-6 text-[#00ffc8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">📊 Daily Operations Link</h2>
                    <p class="text-sm text-[var(--text-secondary)]">Generate a shareable link to view all today's orders, packages & delivery status</p>
                </div>
            </div>

            @if($dailyOpsUrl)
                <div class="rounded-xl p-4 mb-4" style="background: rgba(0,255,200,0.05); border: 1px solid rgba(0,255,200,0.15);">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#00ffc8] status-dot"></div>
                        <span class="text-sm font-semibold text-[#00ffc8]">Link Active</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="text" id="opsUrl" value="{{ $dailyOpsUrl }}" readonly
                               class="flex-1 px-4 py-2.5 rounded-lg text-sm font-mono text-white/90" 
                               style="background: rgba(0,0,0,0.3) !important; border: 1px solid rgba(0,255,200,0.2) !important;">
                        <button onclick="copyOpsLink()" id="copyBtn" 
                                class="px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 hover:scale-105"
                                style="background: linear-gradient(135deg, #00ffc8, #00d9f5); color: #0a0a0f;">
                            📋 Copy
                        </button>
                        <a href="{{ $dailyOpsUrl }}" target="_blank" 
                           class="px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 hover:scale-105"
                           style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                            🔗 Open
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.settings.revoke-ops-link') }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:scale-105"
                            style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #ef4444;"
                            onclick="return confirm('Revoke this link? Anyone with it will lose access.')">
                        🗑️ Revoke Link
                    </button>
                </form>
            @else
                <div class="rounded-xl p-4 mb-4" style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1);">
                    <p class="text-sm text-[var(--text-muted)] mb-1">No active link. Generate one to share with your team.</p>
                    <p class="text-xs text-[var(--text-muted)]">The link shows real-time daily orders, subscriptions, customizations & delivery status.</p>
                </div>
                <form action="{{ route('admin.settings.generate-ops-link') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold text-white transition-all duration-300">
                        🔗 Generate Operations Link
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- SETTINGS FORM --}}
    {{-- ═══════════════════════════════════════ --}}
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Payment Gateway --}}
            <div class="card p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 opacity-10" style="background: radial-gradient(circle, var(--accent-food) 0%, transparent 70%);"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(255,123,84,0.15), rgba(254,225,64,0.08)); border: 1px solid rgba(255,123,84,0.3);">
                            <svg class="w-5 h-5 text-[#ff7b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">💳 Payment Gateway</h3>
                            <p class="text-xs text-[var(--text-secondary)]">ZenoPay mobile money integration</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1.5">ZenoPay API Key</label>
                            <div class="relative">
                                <input type="password" name="zenopay_api_key" id="zenopay_api_key" 
                                       value="{{ $zenoPayApiKey }}" required
                                       class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-white/30 pr-12">
                                <button type="button" onclick="toggleVisibility('zenopay_api_key', 'zenoEye')" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-lg opacity-50 hover:opacity-100 transition-opacity">
                                    <span id="zenoEye">👁️</span>
                                </button>
                            </div>
                            @error('zenopay_api_key')
                                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 p-3 rounded-lg" style="background: rgba(255,123,84,0.05); border: 1px solid rgba(255,123,84,0.1);">
                        <p class="text-xs text-[var(--text-muted)]">
                            💡 Payment polling checks every 30 seconds automatically until payment is completed or fails.
                        </p>
                    </div>
                </div>
            </div>

            {{-- WhatsApp & Bot --}}
            <div class="card p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 opacity-10" style="background: radial-gradient(circle, #25D366 0%, transparent 70%);"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(37,211,102,0.15), rgba(37,211,102,0.05)); border: 1px solid rgba(37,211,102,0.3);">
                            <svg class="w-5 h-5 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">📱 WhatsApp Bot</h3>
                            <p class="text-xs text-[var(--text-secondary)]">Bot configuration & support number</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1.5">WhatsApp Support Number</label>
                            <input type="text" name="whatsapp_number" value="{{ $whatsappNumber }}"
                                   placeholder="+255 7XX XXX XXX"
                                   class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-white/30">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1.5">Bot API Token</label>
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1">
                                    <input type="password" name="bot_super_token" id="bot_super_token" 
                                           value="{{ $botSuperToken }}"
                                           class="w-full px-4 py-2.5 rounded-xl text-sm text-white font-mono placeholder-white/30 pr-12">
                                    <button type="button" onclick="toggleVisibility('bot_super_token', 'botEye')" 
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-lg opacity-50 hover:opacity-100 transition-opacity">
                                        <span id="botEye">👁️</span>
                                    </button>
                                </div>
                                <button type="button" onclick="generateBotToken()"
                                        class="px-3 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-300 hover:scale-105"
                                        style="background: linear-gradient(135deg, rgba(37,211,102,0.2), rgba(37,211,102,0.1)); border: 1px solid rgba(37,211,102,0.3); color: #25D366;">
                                    🔄 Generate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Save Button --}}
        <div class="mt-6 flex justify-end">
            <button type="submit" class="btn-primary px-8 py-3 rounded-xl text-sm font-bold text-white transition-all duration-300">
                💾 Save Settings
            </button>
        </div>
    </form>

</div>

<script>
function toggleVisibility(inputId, eyeId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(eyeId);
    if (input.type === 'password') {
        input.type = 'text';
        eye.textContent = '🙈';
    } else {
        input.type = 'password';
        eye.textContent = '👁️';
    }
}

function generateBotToken() {
    const array = new Uint8Array(32);
    window.crypto.getRandomValues(array);
    const token = Array.from(array, byte => byte.toString(16).padStart(2, '0')).join('');
    const input = document.getElementById('bot_super_token');
    input.value = token;
    input.type = 'text';
    document.getElementById('botEye').textContent = '🙈';
}

function copyOpsLink() {
    const input = document.getElementById('opsUrl');
    navigator.clipboard.writeText(input.value).then(() => {
        const btn = document.getElementById('copyBtn');
        btn.textContent = '✅ Copied!';
        btn.style.background = 'linear-gradient(135deg, #10b981, #06b6d4)';
        setTimeout(() => {
            btn.textContent = '📋 Copy';
            btn.style.background = 'linear-gradient(135deg, #00ffc8, #00d9f5)';
        }, 2000);
    });
}
</script>
@endsection
