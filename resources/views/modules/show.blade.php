@extends('layouts.app')

@section('title', $page['title'])
@section('page-title', $page['title'])
@section('page-subtitle', $page['subtitle'])

@section('content')
<div class="stats-row cols-3">
    <div class="stat-card">
        <p class="stat-label">Enregistrements</p>
        <p class="stat-value">—</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Ce mois</p>
        <p class="stat-value" style="color:#2563EB">—</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Statut</p>
        <p class="stat-value" style="color:#059669">Actif</p>
    </div>
</div>

<div class="content-panel">
    <div class="content-panel-header">
        <h2 class="font-poppins font-semibold text-[#071A35] text-sm m-0">
            @if($page['section'])
                <span class="text-blue-500 font-normal">{{ $page['section'] }} —</span>
            @endif
            {{ $page['title'] }}
        </h2>
        <span class="text-xs px-2.5 py-1 rounded-full bg-blue-50 text-blue-600 font-medium">En développement</span>
    </div>
    <div class="flex flex-col items-center justify-center py-14 px-4 text-center">
        <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center mb-4">
            <i class="fa-solid {{ $page['icon'] }} text-2xl text-blue-400"></i>
        </div>
        <p class="text-slate-600 font-medium m-0">{{ $page['subtitle'] }}</p>
        <p class="text-sm text-slate-400 mt-2 max-w-md m-0">
            Module prêt à recevoir vos formulaires et tableaux de données.
        </p>
    </div>
</div>
@endsection
