<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function showForm(): View
    {
        return view('home');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'regex:/^\+?[0-9]{7,15}$/'],
        ]);

        $registration = Registration::create([
            'username' => $validated['username'],
            'phone_number' => $validated['phone_number'],
            'token' => Registration::generateToken(),
            'is_active' => true,
            'expires_at' => now()->addDays(7),
        ]);

        return redirect()->route('page-a.show', ['token' => $registration->token]);
    }
}
