<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users|regex:/^[a-z0-9._-]+$/i',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:admin,manager,technician',
            'phone' => 'nullable|string|max:20',
        ], [
            'username.regex' => 'Le nom d\'utilisateur ne peut contenir que des lettres, chiffres, points, tirets et underscores.',
        ]);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Compte créé avec succès.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,'.$user->id.'|regex:/^[a-z0-9._-]+$/i',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role' => 'required|in:admin,manager,technician',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Compte mis à jour.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Compte supprimé.');
    }

    public function resetPassword(User $user)
    {
        $newPassword = Str::random(10);
        $user->update(['password' => $newPassword]);

        return back()->with('success', "Nouveau mot de passe pour {$user->username} : {$newPassword}");
    }
}
