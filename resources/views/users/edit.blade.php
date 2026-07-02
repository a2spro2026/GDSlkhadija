@extends('layouts.app')

@section('title', 'Modifier compte')
@section('page-title', 'Modifier : ' . $user->name)

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('users.update', $user) }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Identifiant *</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rôle *</label>
                <select name="role" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    <option value="technician" @selected(old('role', $user->role) === 'technician')>Technicien</option>
                    <option value="manager" @selected(old('role', $user->role) === 'manager')>Manager</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Administrateur</option>
                </select>
            </div>
            <div class="flex items-end pb-2">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active)) class="rounded text-gds-teal">
                    Compte actif
                </label>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-4">
            <p class="text-sm text-gray-500 mb-3">Laisser vide pour conserver le mot de passe actuel.</p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                    <input type="password" name="password" minlength="8" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer</label>
                    <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                </div>
            </div>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-gds-navy text-white text-sm font-medium rounded-lg hover:bg-gds-navy-dark">Mettre à jour</button>
            <a href="{{ route('users.index') }}" class="px-6 py-2.5 text-sm text-gray-600">Annuler</a>
        </div>
    </form>
</div>
@endsection
