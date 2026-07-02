<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        if ($request->hasSession()) {
            $request->session()->regenerateToken();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Le nom d\'utilisateur est requis.',
            'password.required' => 'Le mot de passe est requis.',
        ]);

        $remember = $request->boolean('remember');
        $username = trim($credentials['username']);
        $password = $credentials['password'];

        $usernames = [$username];
        if (! str_contains($username, '@')) {
            $usernames[] = strtolower($username).'@gds.com';
        }

        $authenticated = false;
        foreach (array_unique($usernames) as $attemptUsername) {
            if (Auth::attempt(['username' => $attemptUsername, 'password' => $password], $remember)) {
                $authenticated = true;
                break;
            }
        }

        if (! $authenticated) {
            throw ValidationException::withMessages([
                'username' => 'Identifiants incorrects.',
            ]);
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => 'Votre compte est désactivé. Contactez l\'administrateur.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
