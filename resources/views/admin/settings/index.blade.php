@extends('admin.layout')

@section('title', 'Settings')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-blue-900 dark:text-blue-100">Settings</h1>
    <p class="text-slate-600 dark:text-slate-400 mt-2">Configure payment gateway and system settings</p>
</div>

<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-6">ZenoPay Configuration</h2>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        <div class="mb-6">
            <label for="zenopay_api_key" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                ZenoPay API Key <span class="text-red-500">*</span>
            </label>
            <input 
                type="password" 
                name="zenopay_api_key" 
                id="zenopay_api_key" 
                value="{{ old('zenopay_api_key', $zenoPayApiKey) }}" 
                required
                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter your ZenoPay API key"
            >
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Get your API key from <a href="https://zenoapi.com" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">ZenoPay Dashboard</a>
            </p>
            @error('zenopay_api_key')
                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="whatsapp_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                WhatsApp Number
            </label>
            <input 
                type="text" 
                name="whatsapp_number" 
                id="whatsapp_number" 
                value="{{ old('whatsapp_number', $whatsappNumber) }}" 
                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="e.g., 255712345678"
            >
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Enter WhatsApp number with country code (e.g., 255712345678). This will be used for the WhatsApp chat button on the website.
            </p>
            @error('whatsapp_number')
                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="bot_super_token" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Bot Super Token
            </label>
            <div class="flex gap-2">
                <input 
                    type="password" 
                    name="bot_super_token" 
                    id="bot_super_token" 
                    value="{{ old('bot_super_token', $botSuperToken) }}" 
                    class="flex-1 px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter bot super token for WhatsApp bot API"
                >
                <button 
                    type="button" 
                    id="generate-token-btn"
                    onclick="generateBotToken()"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium transition-colors duration-200 whitespace-nowrap"
                    title="Generate a new secure token"
                >
                    Generate Token
                </button>
                <button 
                    type="button" 
                    id="toggle-token-visibility"
                    onclick="toggleTokenVisibility()"
                    class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-md font-medium transition-colors duration-200"
                    title="Show/Hide token"
                >
                    <span id="toggle-icon">👁️</span>
                </button>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Enter the super token for WhatsApp bot API authentication. This token will be used in the Authorization header: <code class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded">Bearer YOUR_TOKEN</code>
            </p>
            @error('bot_super_token')
                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-4 mb-6">
            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">API Information</h3>
            <ul class="list-disc list-inside space-y-1 text-sm text-blue-800 dark:text-blue-200">
                <li>Endpoint: <code class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded">https://zenoapi.com/api/payments/mobile_money_tanzania</code></li>
                <li>Status Check: <code class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded">https://zenoapi.com/api/payments/order-status</code></li>
                <li>Phone Format: <code class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded">07XXXXXXXX</code></li>
            </ul>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-2 border border-slate-300 dark:border-slate-600 rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium">
                Save Settings
            </button>
        </div>
    </form>
</div>

<div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 mt-6">
    <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-4">Payment Status Checking</h2>
    <div class="bg-slate-50 dark:bg-slate-700 rounded-md p-4">
        <p class="text-slate-600 dark:text-slate-400">
            <strong>Note:</strong> The system uses polling to check payment status. After a payment is initiated, 
            the system automatically checks the status every 30 seconds until the payment is completed or fails.
        </p>
    </div>
</div>

<script>
    /**
     * Generate a secure random token for the bot
     */
    function generateBotToken() {
        // Generate a secure random token (64 characters)
        const array = new Uint8Array(32);
        window.crypto.getRandomValues(array);
        const token = Array.from(array, byte => byte.toString(16).padStart(2, '0')).join('');
        
        // Set the token in the input field
        const tokenInput = document.getElementById('bot_super_token');
        tokenInput.value = token;
        
        // Change input type to text temporarily to show the token
        tokenInput.type = 'text';
        
        // Update toggle button state
        const toggleIcon = document.getElementById('toggle-icon');
        toggleIcon.textContent = '🙈';
        
        // Visual feedback
        const generateBtn = document.getElementById('generate-token-btn');
        const originalText = generateBtn.textContent;
        generateBtn.textContent = '✓ Generated!';
        generateBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
        generateBtn.classList.add('bg-green-500');
        
        setTimeout(() => {
            generateBtn.textContent = originalText;
            generateBtn.classList.remove('bg-green-500');
            generateBtn.classList.add('bg-green-600', 'hover:bg-green-700');
        }, 2000);
    }
    
    /**
     * Toggle token visibility (show/hide)
     */
    function toggleTokenVisibility() {
        const tokenInput = document.getElementById('bot_super_token');
        const toggleIcon = document.getElementById('toggle-icon');
        
        if (tokenInput.type === 'password') {
            tokenInput.type = 'text';
            toggleIcon.textContent = '🙈';
        } else {
            tokenInput.type = 'password';
            toggleIcon.textContent = '👁️';
        }
    }
</script>
@endsection
