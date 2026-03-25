<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        // Reset registration session
        session()->forget('registration_data');
        session()->put('registration_step', 1);

        return view('auth.register');
    }

    public function handleStep(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $step = session()->get('registration_step', 1);
        $registrationData = session()->get('registration_data', []);

        // Handle different steps
        // New order: 1=Name, 2=Phone(required), 3=Address(required), 4=Email, 5=Password, 6=Confirm
        switch ($step) {
            case 1: // Name
                $validated = $request->validate([
                    'answer' => 'required|string|max:255',
                ]);
                $registrationData['name'] = $validated['answer'];
                session()->put('registration_step', 2);
                break;

            case 2: // Phone (REQUIRED)
                $validated = $request->validate([
                    'answer' => 'required|string|max:20',
                ], [
                    'answer.required' => 'Namba ya simu inahitajika.',
                ]);

                // Format phone number
                $phone = preg_replace('/[^0-9]/', '', $validated['answer']);
                if (str_starts_with($phone, '255')) {
                    $phone = '0'.substr($phone, 3);
                } elseif (! str_starts_with($phone, '0')) {
                    $phone = '0'.$phone;
                }

                // Check if phone already exists
                if (User::where('phone', $phone)->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Namba hii ya simu tayari imetumika. Tafadhali tumia nyingine au ingia kwenye akaunti yako.',
                    ], 422);
                }

                $registrationData['phone'] = $phone;
                session()->put('registration_step', 3);
                break;

            case 3: // Address (REQUIRED)
                $validated = $request->validate([
                    'answer' => 'required|string|max:1000',
                ], [
                    'answer.required' => 'Eneo lako linahitajika kwa delivery.',
                ]);
                $registrationData['address'] = $validated['answer'];
                session()->put('registration_step', 4);
                break;

            case 4: // Email
                $validated = $request->validate([
                    'answer' => 'required|string|email|max:255|unique:users,email',
                ], [
                    'answer.unique' => 'Barua pepe hii tayari imetumika. Tafadhali tumia nyingine.',
                    'answer.email' => 'Tafadhali ingiza barua pepe halali.',
                ]);
                $registrationData['email'] = $validated['answer'];
                session()->put('registration_step', 5);
                break;

            case 5: // Password
                $validated = $request->validate([
                    'answer' => ['required', Password::defaults()],
                ], [
                    'answer.min' => 'Password lazima iwe na angalau :min herufi.',
                ]);
                $registrationData['password'] = $validated['answer'];
                session()->put('registration_step', 6);
                break;

            case 6: // Confirm Password
                $password = $registrationData['password'] ?? '';
                $validated = $request->validate([
                    'answer' => 'required|string',
                ]);

                if ($validated['answer'] !== $password) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password hazifanani. Tafadhali jaribu tena.',
                    ], 422);
                }

                // Create user account
                $user = User::create([
                    'name' => $registrationData['name'],
                    'email' => $registrationData['email'],
                    'phone' => $registrationData['phone'],
                    'address' => $registrationData['address'],
                    'password' => Hash::make($registrationData['password']),
                    'source' => 'web',
                ]);

                // Clear registration session
                session()->forget('registration_data');
                session()->forget('registration_step');

                // Auto login and redirect
                Auth::login($user);

                $redirectTo = $user->is_admin
                    ? route('admin.dashboard')
                    : route('dashboard');

                return response()->json([
                    'success' => true,
                    'completed' => true,
                    'message' => 'Akaunti yako imeundwa kwa mafanikio! Unakuja kwenye ukurasa wa kuingia...',
                    'redirect' => $redirectTo,
                ]);
        }

        // Save registration data to session
        session()->put('registration_data', $registrationData);

        // Return next question
        return response()->json([
            'success' => true,
            'step' => session()->get('registration_step'),
            'message' => $this->getQuestionForStep(session()->get('registration_step')),
            'isOptional' => false,
        ]);
    }

    protected function getQuestionForStep(int $step): string
    {
        return match ($step) {
            1 => 'Habari! Karibu Monana Platform! 🍽️<br>Jina lako nani?',
            2 => 'Asante! Namba yako ya simu ni ipi?<br><small class="text-[#6b6b6b]">Mfano: 0712345678</small>',
            3 => 'Uko eneo gani? (Kwa delivery)<br><small class="text-[#6b6b6b]">Mfano: Kijitonyama, Makumbusho</small>',
            4 => 'Barua pepe yako ni ipi?',
            5 => 'Weka password ya siri:',
            6 => 'Thibitisha password yako:',
            default => 'Tafadhali jibu swali hili...',
        };
    }

    public function skipOptional(Request $request): \Illuminate\Http\JsonResponse
    {
        // No optional steps anymore — all fields are required
        return response()->json(['success' => false]);
    }

    // Legacy method for traditional form submission (keeping for backward compatibility)
    public function register(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        $redirectTo = $user->is_admin
            ? route('admin.dashboard')
            : route('dashboard');

        return redirect($redirectTo)
            ->with('success', 'Account created successfully! Welcome to SmartFood Hub.');
    }
}
