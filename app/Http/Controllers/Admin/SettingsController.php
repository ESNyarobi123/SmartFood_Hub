<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $zenoPayApiKey = Setting::get('zenopay_api_key', '');
        $whatsappNumber = Setting::get('whatsapp_number', '');
        $botSuperToken = Setting::get('bot_super_token', '');

        return view('admin.settings.index', compact('zenoPayApiKey', 'whatsappNumber', 'botSuperToken'));
    }

    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'zenopay_api_key' => 'required|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'bot_super_token' => 'nullable|string|max:255',
        ]);

        Setting::set('zenopay_api_key', $validated['zenopay_api_key'], 'ZenoPay API Key for mobile money payments');
        Setting::set('whatsapp_number', $validated['whatsapp_number'] ?? '', 'WhatsApp number for customer support');
        Setting::set('bot_super_token', $validated['bot_super_token'] ?? '', 'Bot Super Token for WhatsApp bot API authentication');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
