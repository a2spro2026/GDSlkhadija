@extends('layouts.app')

@section('title', 'Équipe & Comptes')
@section('page-title', 'Équipe & Comptes')
@section('page-subtitle', 'Gestion des accès techniciens et administrateurs')

@section('header-actions')
    <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 bg-gds-navy text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-gds-navy-dark transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nouveau compte
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Nom</th>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Identifiant</th>
                <th class="text-left px-6 py-3 font-semibold text-gray-600">Email</th>
                <th class="text-center px-6 py-3 font-semibold text-gray-600">Rôle</th>
                <th class="text-center px-6 py-3 font-semibold text-gray-600">Statut</th>
                <th class="text-right px-6 py-3 font-semibold text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gds-teal/20 text-gds-teal text-xs font-bold flex items-center justify-center">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <span class="font-medium">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $user->username }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'manager' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($user->is_active)
                            <span class="text-green-600 text-xs font-medium">Actif</span>
                        @else
                            <span class="text-red-500 text-xs font-medium">Inactif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('users.edit', $user) }}" class="text-xs text-gds-teal hover:underline">Modifier</a>
                        <form method="POST" action="{{ route('users.reset-password', $user) }}" class="inline" onsubmit="return confirm('Réinitialiser le mot de passe ?')">
                            @csrf
                            <button type="submit" class="text-xs text-amber-600 hover:underline">Reset MDP</button>
                        </form>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('Supprimer ce compte ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:underline">Suppr.</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if($users->hasPages())
        <div class="px-6 py-4 border-t">{{ $users->links() }}</div>
    @endif
</div>
@endsection
