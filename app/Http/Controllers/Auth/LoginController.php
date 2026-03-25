<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('auth.login');
    }

    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = $request->input('login');
        $password = $request->input('password');
        $remember = $request->filled('remember');

        // Determine if login is email or phone
        $credentials = filter_var($loginField, FILTER_VALIDATE_EMAIL)
            ? ['email' => $loginField, 'password' => $password]
            : ['phone' => $this->formatPhone($loginField), 'password' => $password];

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $redirectTo = Auth::user()->is_admin
                ? route('admin.dashboard')
                : route('dashboard');

            return redirect()->intended($redirectTo)
                ->with('success', 'Karibu tena!');
        }

        throw ValidationException::withMessages([
            'login' => ['Taarifa ulizoweka si sahihi. Tafadhali jaribu tena.'],
        ]);
    }

    /**
     * Format phone number to 07XXXXXXXX format.
     */
    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '255')) {
            $phone = '0'.substr($phone, 3);
        } elseif (! str_starts_with($phone, '0')) {
            $phone = '0'.$phone;
        }

        return $phone;
    }

    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'You have been logged out successfully.');
    }
}
