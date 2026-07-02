@extends('layouts.app')

@section('title', 'Utilisateurs')
@section('page-title', 'Utilisateurs')
@section('page-subtitle', 'Gestion des comptes et droits d\'accès')

@push('styles')
<style>
    .users-page {
        max-width: 1400px;
        margin: 0 auto;
        --users-topbar-h: 4.25rem;
        --users-hero-h: 5.75rem;
    }

    .users-hero-wrap {
        position: fixed;
        top: var(--users-topbar-h);
        left: 300px;
        right: 0;
        z-index: 15;
        padding: 1rem 1.5rem 0.75rem;
        background: #eef2f7;
        border-bottom: 1px solid #e2e8f0;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
    }
    @media (max-width: 767px) {
        .users-hero-wrap {
            left: 260px;
            padding: 0.75rem 1rem 0.65rem;
        }
    }

    .users-hero {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        margin: 0 auto;
        max-width: 1400px;
        border-radius: 1rem;
        background: linear-gradient(135deg, #020617 0%, #071A35 45%, #0F4C81 100%);
        border: 1px solid rgba(96, 165, 250, 0.25);
        box-shadow: 0 8px 32px rgba(7, 26, 53, 0.2);
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .users-page-scroll {
        padding-top: calc(var(--users-hero-h) + 1.25rem);
    }
    .users-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 90% 20%, rgba(37,99,235,0.35), transparent 45%);
        pointer-events: none;
    }
    .users-hero-text { position: relative; z-index: 1; }
    .users-hero-text h2 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
    }
    .users-hero-text p {
        margin: 0.35rem 0 0;
        font-size: 0.8rem;
        color: rgba(191, 219, 254, 0.85);
    }
    .users-hero-stat {
        position: relative;
        z-index: 1;
        text-align: center;
        padding: 0.65rem 1.25rem;
        border-radius: 0.75rem;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        backdrop-filter: blur(6px);
    }
    .users-hero-stat strong {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        color: #93c5fd;
    }
    .users-hero-stat span { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.6); }

    .users-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }
    @media (min-width: 1200px) {
        .users-grid { grid-template-columns: 1.35fr 1fr; align-items: start; }
    }

    .panel {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .panel-head {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fafbfc, #fff);
    }
    .panel-head-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: #fff;
        flex-shrink: 0;
    }
    .panel-head-icon.blue { background: linear-gradient(135deg, #2563EB, #1d4ed8); }
    .panel-head-icon.navy { background: linear-gradient(135deg, #071A35, #0F4C81); }
    .panel-head h3 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        color: #071A35;
    }
    .panel-head p { margin: 0.15rem 0 0; font-size: 0.72rem; color: #64748b; }
    .panel-body { padding: 1.25rem; }

    .cred-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    @media (min-width: 640px) { .cred-row { grid-template-columns: 1fr 1fr; } }

    .field-box label {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #475569;
        margin-bottom: 0.5rem;
    }
    .field-box label i { color: #2563EB; font-size: 0.75rem; }
    .field-input-wrap {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0 0.85rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.7rem;
        background: #f8fafc;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }
    .field-input-wrap:focus-within {
        border-color: #2563EB;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
    .field-input-wrap input {
        flex: 1;
        border: 0;
        background: transparent;
        padding: 0.75rem 0;
        font-size: 0.875rem;
        min-width: 0;
        outline: none;
    }
    .field-suffix {
        font-size: 0.78rem;
        font-weight: 700;
        color: #1e40af;
        padding: 0.35rem 0.6rem;
        border-radius: 0.4rem;
        background: #dbeafe;
        white-space: nowrap;
    }

    .perm-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .perm-tab {
        padding: 0.45rem 0.85rem;
        font-size: 0.72rem;
        font-weight: 600;
        border-radius: 999px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
    }
    .perm-tab:hover { border-color: #93c5fd; color: #1e40af; }
    .perm-tab.is-active {
        background: linear-gradient(135deg, #071A35, #0F4C81);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 4px 12px rgba(7, 26, 53, 0.25);
    }
    .perm-tab-panel { display: none; }
    .perm-tab-panel.is-active { display: block; }

    .perm-tab-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.85rem;
    }
    .perm-tab-head span {
        font-size: 0.72rem;
        font-weight: 700;
        color: #0F4C81;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .perm-tab-head button {
        font-size: 0.68rem;
        font-weight: 600;
        color: #2563EB;
        background: none;
        border: 0;
        cursor: pointer;
        padding: 0;
    }

    .module-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.65rem;
    }
    @media (min-width: 700px) { .module-grid { grid-template-columns: 1fr 1fr; } }

    .module-box {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.75rem;
        background: linear-gradient(180deg, #fff, #f8fafc);
        transition: box-shadow 0.2s;
    }
    .module-box:hover { box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06); }
    .module-box-title {
        font-size: 0.78rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 0.6rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .module-box-title i { color: #2563EB; font-size: 0.7rem; }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 0.35rem;
    }
    @media (max-width: 500px) { .actions-grid { grid-template-columns: repeat(3, 1fr); } }

    .action-chip {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        padding: 0.45rem 0.2rem;
        border-radius: 0.55rem;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        cursor: pointer;
        transition: all 0.18s;
        min-height: 3.75rem;
    }
    .action-chip:hover { transform: translateY(-1px); border-color: #93c5fd; }
    .action-chip input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .action-chip:has(input:checked) {
        border-color: #2563EB;
        background: linear-gradient(180deg, #eff6ff, #fff);
        box-shadow: 0 3px 10px rgba(37, 99, 235, 0.18);
    }
    .action-chip:has(input:checked)::after {
        content: '\f00c';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        top: 0.2rem;
        right: 0.25rem;
        font-size: 0.5rem;
        color: #2563EB;
    }
    .action-chip-icon {
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 0.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        color: #fff;
    }
    .action-chip-icon.voir { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .action-chip-icon.saisir { background: linear-gradient(135deg, #10b981, #059669); }
    .action-chip-icon.modifier { background: linear-gradient(135deg, #2563EB, #1d4ed8); }
    .action-chip-icon.imprimer { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .action-chip-icon.supprimer { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .action-chip span {
        font-size: 0.58rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.02em;
        text-align: center;
        line-height: 1.15;
    }
    .action-chip:has(input:checked) span { color: #1e40af; }

    .validate-bar {
        margin-top: 1.25rem;
        padding: 1rem 1.25rem;
        border-radius: 0.85rem;
        background: linear-gradient(90deg, #f0f7ff, #eff6ff);
        border: 1px solid #bfdbfe;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .validate-bar p {
        margin: 0;
        font-size: 0.75rem;
        color: #475569;
    }
    .btn-validate {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 700;
        color: #fff;
        border: 0;
        border-radius: 0.65rem;
        cursor: pointer;
        background: linear-gradient(135deg, #2563EB, #0F4C81);
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.35);
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .btn-validate:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }

    .registry-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        max-height: 720px;
        overflow-y: auto;
        padding: 0.25rem;
    }
    .registry-list::-webkit-scrollbar { width: 5px; }
    .registry-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    .user-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.85rem;
        padding: 1rem;
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .user-card:hover {
        border-color: #93c5fd;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.08);
    }
    .user-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .user-card-meta {
        display: grid;
        gap: 0.5rem;
        flex: 1;
        min-width: 0;
    }
    .user-card-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.78rem;
    }
    .user-card-row i {
        width: 1rem;
        text-align: center;
        color: #2563EB;
        font-size: 0.7rem;
        flex-shrink: 0;
    }
    .user-card-row .label {
        font-weight: 600;
        color: #64748b;
        min-width: 5.5rem;
    }
    .user-card-row .value {
        font-weight: 600;
        color: #0f172a;
        word-break: break-all;
    }
    .user-card-row .value.mono { font-family: ui-monospace, monospace; }
    .perms-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #1e40af;
        border: 1px solid #93c5fd;
    }
    .btn-delete-user {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #dc2626;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        transition: background 0.15s;
        flex-shrink: 0;
    }
    .btn-delete-user:hover { background: #fee2e2; }

    .registry-empty {
        text-align: center;
        padding: 3rem 1.5rem;
        border: 2px dashed #e2e8f0;
        border-radius: 0.85rem;
        background: #fafbfc;
    }
    .registry-empty-icon {
        width: 4rem;
        height: 4rem;
        margin: 0 auto 1rem;
        border-radius: 1rem;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #2563EB;
    }
    .registry-empty h4 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        color: #334155;
    }
    .registry-empty p {
        margin: 0.4rem 0 0;
        font-size: 0.78rem;
        color: #94a3b8;
    }
</style>
@endpush

@php
    $groupIcons = [
        'Fournisseur' => 'fa-truck-field',
        'Dépôt' => 'fa-warehouse',
        'Gestion' => 'fa-chart-line',
        'Opérations' => 'fa-screwdriver-wrench',
        'Système' => 'fa-sliders',
    ];
@endphp

@section('content')
<div class="users-page">
    <div class="users-hero-wrap">
        <div class="users-hero">
            <div class="users-hero-text">
                <h2><i class="fa-solid fa-user-shield mr-2"></i>Gestion des utilisateurs</h2>
                <p>Créez des comptes @gds.com et attribuez les droits par module et par action</p>
            </div>
            <div class="users-hero-stat">
                <strong id="usersCount">{{ $users->count() }}</strong>
                <span>Comptes actifs</span>
            </div>
        </div>
    </div>

    <div class="users-page-scroll">
    <div class="users-grid">
        <form method="POST" action="{{ route('systeme.utilisateurs.store') }}" class="panel">
            @csrf
            <div class="panel-head">
                <div class="panel-head-icon blue"><i class="fa-solid fa-user-pen"></i></div>
                <div>
                    <h3>Nouveau compte</h3>
                    <p>Identifiants de connexion</p>
                </div>
            </div>
            <div class="panel-body">
                <div class="cred-row">
                    <div class="field-box">
                        <label for="username"><i class="fa-solid fa-at"></i> Nom utilisateur</label>
                        <div class="field-input-wrap">
                            <input type="text" id="username" name="username"
                                   value="{{ old('username') ? preg_replace('/@gds\.com$/i', '', old('username')) : '' }}"
                                   required placeholder="k.benali" autocomplete="off">
                            <span class="field-suffix">@gds.com</span>
                        </div>
                        @error('username')<p class="text-xs text-red-600 mt-1 m-0">{{ $message }}</p>@enderror
                    </div>
                    <div class="field-box">
                        <label for="password"><i class="fa-solid fa-lock"></i> Mot de passe</label>
                        <div class="field-input-wrap">
                            <input type="password" id="password" name="password" required
                                   placeholder="Minimum 6 caractères" autocomplete="new-password">
                        </div>
                        @error('password')<p class="text-xs text-red-600 mt-1 m-0">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="panel-head" style="padding:0 0 0.75rem;margin:0;border:0;background:transparent">
                    <div class="panel-head-icon navy" style="width:2rem;height:2rem;font-size:0.8rem"><i class="fa-solid fa-key"></i></div>
                    <div>
                        <h3 style="font-size:0.85rem">Autorisations</h3>
                        <p>Sélectionnez les droits par module
                            @error('permissions')<span class="text-red-600"> — {{ $message }}</span>@enderror
                        </p>
                    </div>
                </div>

                <div class="perm-tabs" role="tablist">
                    @foreach($permissionGroups as $groupName => $permissions)
                        <button type="button" class="perm-tab {{ $loop->first ? 'is-active' : '' }}"
                                data-tab="{{ $loop->index }}" role="tab">
                            <i class="fa-solid {{ $groupIcons[$groupName] ?? 'fa-folder' }} mr-1"></i>{{ $groupName }}
                        </button>
                    @endforeach
                </div>

                @foreach($permissionGroups as $groupName => $permissions)
                    <div class="perm-tab-panel {{ $loop->first ? 'is-active' : '' }}" data-panel="{{ $loop->index }}">
                        <div class="perm-tab-head">
                            <span><i class="fa-solid {{ $groupIcons[$groupName] ?? 'fa-folder' }}"></i> {{ $groupName }}</span>
                            <button type="button" class="perm-select-all" data-group="{{ $loop->index }}">Tout cocher</button>
                        </div>
                        <div class="module-grid" data-perm-group="{{ $loop->index }}">
                            @foreach($permissions as $moduleKey => $moduleLabel)
                                <div class="module-box">
                                    <p class="module-box-title"><i class="fa-solid fa-cube"></i> {{ $moduleLabel }}</p>
                                    <div class="actions-grid">
                                        @foreach($permissionActions as $actionKey => $action)
                                            @php $permValue = $moduleKey.'.'.$actionKey; @endphp
                                            <label class="action-chip">
                                                <input type="checkbox" name="permissions[]" value="{{ $permValue }}"
                                                       @checked(is_array(old('permissions')) && in_array($permValue, old('permissions')))>
                                                <span class="action-chip-icon {{ $actionKey }}">
                                                    <i class="fa-solid {{ $action['icon'] }}"></i>
                                                </span>
                                                <span>{{ $action['label'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="validate-bar">
                    <p><i class="fa-solid fa-circle-info text-blue-500"></i> Vérifiez les autorisations avant validation</p>
                    <button type="submit" class="btn-validate">
                        <i class="fa-solid fa-circle-check"></i> Valider
                    </button>
                </div>
            </div>
        </form>

        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon navy"><i class="fa-solid fa-users"></i></div>
                <div>
                    <h3>Registre des utilisateurs</h3>
                    <p>Comptes créés manuellement</p>
                </div>
            </div>
            <div class="panel-body" style="padding-top:0.75rem">
                @if($users->isEmpty())
                    <div class="registry-empty">
                        <div class="registry-empty-icon"><i class="fa-solid fa-user-plus"></i></div>
                        <h4>Aucun utilisateur</h4>
                        <p>Le registre se remplit après validation du formulaire</p>
                    </div>
                @else
                    <div class="registry-list">
                        @foreach($users as $user)
                            @php $permCount = count($user->permissions ?? []); @endphp
                            <article class="user-card">
                                <div class="user-card-top">
                                    <div class="user-card-meta">
                                        <div class="user-card-row">
                                            <i class="fa-solid fa-user"></i>
                                            <span class="label">Nom</span>
                                            <span class="value mono">{{ $user->username }}</span>
                                        </div>
                                        <div class="user-card-row">
                                            <i class="fa-solid fa-shield-halved"></i>
                                            <span class="label">Autorisations</span>
                                            <span class="perms-count-badge">
                                                <i class="fa-solid fa-check-double"></i>
                                                {{ $permCount }} droit{{ $permCount > 1 ? 's' : '' }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('systeme.utilisateurs.destroy', $user) }}"
                                              onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-delete-user" title="Supprimer">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>

<script>
    document.querySelectorAll('.perm-tab').forEach((tab) => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.perm-tab').forEach((t) => t.classList.remove('is-active'));
            document.querySelectorAll('.perm-tab-panel').forEach((p) => p.classList.remove('is-active'));
            tab.classList.add('is-active');
            document.querySelector(`[data-panel="${tab.dataset.tab}"]`)?.classList.add('is-active');
        });
    });

    document.querySelectorAll('.perm-select-all').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const group = btn.dataset.group;
            const grid = document.querySelector(`[data-perm-group="${group}"]`);
            const boxes = grid.querySelectorAll('input[type="checkbox"]');
            const allChecked = [...boxes].every((b) => b.checked);
            boxes.forEach((b) => { b.checked = !allChecked; });
            btn.textContent = allChecked ? 'Tout cocher' : 'Tout décocher';
        });
    });
</script>
@endsection
