@extends('layouts.app')

@section('title', 'Nouveau compte')
@section('page-title', 'Créer un compte utilisateur')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('users.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Identifiant (login) *</label>
                <input type="text" name="username" value="{{ old('username') }}" required placeholder="ex: j.dupont"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Rôle *</label>
            <select name="role" required class="w-full max-w-xs px-3 py-2 border border-gray-200 rounded-lg text-sm">
                <option value="technician" @selected(old('role') === 'technician')>Technicien</option>
                <option value="manager" @selected(old('role') === 'manager')>Manager</option>
                <option value="admin" @selected(old('role') === 'admin')>Administrateur</option>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe *</label>
                <input type="password" name="password" required minlength="8" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer *</label>
                <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-gds-navy text-white text-sm font-medium rounded-lg hover:bg-gds-navy-dark">Créer le compte</button>
            <a href="{{ route('users.index') }}" class="px-6 py-2.5 text-sm text-gray-600">Annuler</a>
        </div>
    </form>
</div>
@endsection
