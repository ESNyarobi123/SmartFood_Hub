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
        switch ($step) {
            case 1: // Name
                $validated = $request->validate([
                    'answer' => 'required|string|max:255',
                ]);
                $registrationData['name'] = $validated['answer'];
                session()->put('registration_step', 2);
                break;

            case 2: // Email
                $validated = $request->validate([
                    'answer' => 'required|string|email|max:255|unique:users,email',
                ], [
                    'answer.unique' => 'Barua pepe hii tayari imetumika. Tafadhali tumia nyingine.',
                    'answer.email' => 'Tafadhali ingiza barua pepe halali.',
                ]);
                $registrationData['email'] = $validated['answer'];
                session()->put('registration_step', 3);
                break;

            case 3: // Phone (optional)
                if ($request->has('answer') && trim($request->answer) !== '') {
                    $validated = $request->validate([
                        'answer' => 'nullable|string|max:20',
                    ]);
                    $registrationData['phone'] = trim($validated['answer']) ?: null;
                } else {
                    $registrationData['phone'] = null;
                }
                session()->put('registration_step', 4);
                break;

            case 4: // Address (optional)
                if ($request->has('answer') && trim($request->answer) !== '') {
                    $validated = $request->validate([
                        'answer' => 'nullable|string|max:1000',
                    ]);
                    $registrationData['address'] = trim($validated['answer']) ?: null;
                } else {
                    $registrationData['address'] = null;
                }
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
                    'phone' => $registrationData['phone'] ?? null,
                    'address' => $registrationData['address'] ?? null,
                    'password' => Hash::make($registrationData['password']),
                ]);

                // Clear registration session
                session()->forget('registration_data');
                session()->forget('registration_step');

                // Auto login and redirect
                Auth::login($user);

                return response()->json([
                    'success' => true,
                    'completed' => true,
                    'message' => 'Akaunti yako imeundwa kwa mafanikio! Unakuja kwenye ukurasa wa kuingia...',
                    'redirect' => route('home'),
                ]);
        }

        // Save registration data to session
        session()->put('registration_data', $registrationData);

        // Return next question
        return response()->json([
            'success' => true,
            'step' => session()->get('registration_step'),
            'message' => $this->getQuestionForStep(session()->get('registration_step')),
            'isOptional' => in_array(session()->get('registration_step'), [3, 4]),
        ]);
    }

    protected function getQuestionForStep(int $step): string
    {
        return match ($step) {
            1 => 'Habari! Karibu SmartFood Hub! 🍽️<br>Jina lako nani?',
            2 => 'Asante! Barua pepe yako ni ipi?',
            3 => 'Namba ya simu yako? (Si lazima)',
            4 => 'Anwani yako ya uwekaji? (Si lazima)',
            5 => 'Weka password ya siri:',
            6 => 'Thibitisha password yako:',
            default => 'Tafadhali jibu swali hili...',
        };
    }

    public function skipOptional(Request $request): \Illuminate\Http\JsonResponse
    {
        $step = session()->get('registration_step', 1);

        if (in_array($step, [3, 4])) {
            $registrationData = session()->get('registration_data', []);

            if ($step === 3) {
                $registrationData['phone'] = null;
                session()->put('registration_step', 4);
            } elseif ($step === 4) {
                $registrationData['address'] = null;
                session()->put('registration_step', 5);
            }

            session()->put('registration_data', $registrationData);

            return response()->json([
                'success' => true,
                'step' => session()->get('registration_step'),
                'message' => $this->getQuestionForStep(session()->get('registration_step')),
                'isOptional' => in_array(session()->get('registration_step'), [3, 4]),
            ]);
        }

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

        return redirect()->route('home')
            ->with('success', 'Account created successfully! Welcome to SmartFood Hub.');
    }
}
