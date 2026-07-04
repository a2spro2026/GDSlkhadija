<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Tableau de bord') — GROUPE DLIMI SERVICES</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; margin: 0; }
        .font-poppins { font-family: 'Poppins', sans-serif; }

        .app-shell {
            min-height: 100vh;
            background: #eef2f7;
        }

        /* ── Sidebar ── */
        .app-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 40;
            width: 300px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(185deg, #020617 0%, #071A35 42%, #0a2d5c 100%);
            border-right: 1px solid rgba(96, 165, 250, 0.18);
            box-shadow: 4px 0 24px rgba(2, 6, 23, 0.35);
            transform: translateX(0);
            transition: transform 0.32s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @media (min-width: 768px) {
            .app-sidebar { transform: translateX(0); }
        }

        /* ── Toggle sidebar (icône réseau) ── */
        .sidebar-network-toggle {
            position: fixed;
            top: 1.15rem;
            left: 300px;
            z-index: 45;
            transform: translateX(-50%);
            width: 2rem;
            height: 2rem;
            padding: 0;
            border: none;
            border-radius: 0.55rem;
            background: linear-gradient(145deg, #0c1e3d 0%, #1e40af 45%, #2563EB 100%);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow:
                0 0 0 1px rgba(147, 197, 253, 0.3),
                0 2px 10px rgba(37, 99, 235, 0.35),
                0 0 14px rgba(6, 182, 212, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.18);
            transition: left 0.32s cubic-bezier(0.4, 0, 0.2, 1), transform 0.32s ease, box-shadow 0.25s, background 0.25s;
            overflow: hidden;
        }
        .sidebar-network-toggle::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 20%, rgba(255,255,255,0.18), transparent 55%);
            pointer-events: none;
        }
        .sidebar-network-toggle::after {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            border: 1.5px solid transparent;
            background: linear-gradient(135deg, #f59e0b, #22d3ee, #2563EB) border-box;
            -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0.65;
            pointer-events: none;
            transition: opacity 0.25s;
        }
        .sidebar-network-toggle:hover {
            background: linear-gradient(145deg, #1e3a8a 0%, #2563EB 40%, #06b6d4 100%);
            box-shadow:
                0 0 0 1px rgba(147, 197, 253, 0.5),
                0 3px 14px rgba(37, 99, 235, 0.45),
                0 0 18px rgba(245, 158, 11, 0.2);
            transform: translateX(-50%) scale(1.04);
        }
        .sidebar-network-toggle:hover::after { opacity: 1; }
        .sidebar-network-toggle:focus-visible {
            outline: 1.5px solid #fbbf24;
            outline-offset: 2px;
        }
        .sidebar-network-toggle .network-icon {
            width: 1.1rem;
            height: 1.1rem;
            display: block;
            position: relative;
            z-index: 1;
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-network-toggle .net-flow {
            stroke-dasharray: 4 6;
            animation: net-flow 1.8s linear infinite;
        }
        @keyframes net-flow {
            to { stroke-dashoffset: -20; }
        }
        .sidebar-network-toggle .net-glow {
            animation: net-glow 2.2s ease-in-out infinite;
        }
        @keyframes net-glow {
            0%, 100% { opacity: 0.35; }
            50% { opacity: 0.9; }
        }
        .app-shell.sidebar-collapsed .sidebar-network-toggle {
            left: 0.45rem;
            transform: translateX(0);
            border-radius: 0.55rem;
        }
        .app-shell.sidebar-collapsed .sidebar-network-toggle:hover {
            transform: translateX(0) scale(1.04);
        }
        .app-shell.sidebar-collapsed .sidebar-network-toggle .network-icon {
            transform: rotate(180deg);
        }
        .app-shell.sidebar-collapsed .app-sidebar {
            transform: translateX(-100%);
        }
        .app-shell.sidebar-collapsed .app-main {
            margin-left: 0 !important;
        }
        @media (max-width: 767px) {
            .sidebar-network-toggle { left: 260px; }
            .app-shell.sidebar-collapsed .sidebar-network-toggle { left: 0.45rem; }
        }

        .sidebar-brand {
            flex-shrink: 0;
            padding: 1.35rem 1.15rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.03);
        }
        .sidebar-brand-inner {
            display: flex;
            align-items: center;
            gap: 1.15rem;
        }
        .sidebar-brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            padding: 0.55rem 0.7rem;
            border-radius: 0.7rem;
            background: rgba(37, 99, 235, 0.28);
            border: 1px solid rgba(147, 197, 253, 0.35);
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.2);
            backdrop-filter: blur(4px);
        }
        .sidebar-brand img {
            height: 2.5rem;
            width: auto;
            max-width: 7.5rem;
            object-fit: contain;
            filter: brightness(1.15) contrast(1.08);
        }

        .sidebar-nav {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            padding: 1rem 0.85rem 1.25rem;
        }
        .sidebar-nav::-webkit-scrollbar { width: 5px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(96,165,250,0.35); border-radius: 4px; }

        .nav-group {
            margin-bottom: 0.5rem;
            border-radius: 0.85rem;
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.05);
            overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .nav-group.is-open {
            border-color: rgba(96, 165, 250, 0.22);
            box-shadow: 0 4px 18px rgba(2, 6, 23, 0.25);
        }
        .nav-group.has-active .nav-group-toggle {
            background: rgba(37, 99, 235, 0.12);
        }

        .nav-group-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            gap: 0.65rem;
            padding: 0.8rem 0.85rem;
            margin: 0;
            border: 0;
            border-radius: 0;
            background: transparent;
            cursor: pointer;
            text-align: left;
            transition: background 0.2s, color 0.2s;
        }
        .nav-group-toggle:hover { background: rgba(255,255,255,0.06); }

        .nav-group-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
            flex: 1;
        }

        .nav-group-label {
            font-family: 'Poppins', sans-serif;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            color: rgba(255,255,255,0.92);
            text-transform: none;
        }

        .nav-group-arrow {
            width: 1.65rem;
            height: 1.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            color: rgba(147, 197, 253, 0.9);
            background: rgba(255,255,255,0.06);
            border-radius: 0.45rem;
            transition: transform 0.25s ease, background 0.2s;
            flex-shrink: 0;
        }
        .nav-group-toggle:hover .nav-group-arrow { background: rgba(255,255,255,0.1); }
        .nav-group.is-open .nav-group-arrow { transform: rotate(0deg); }
        .nav-group:not(.is-open) .nav-group-arrow { transform: rotate(-90deg); }

        .nav-group-body {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.28s ease;
        }
        .nav-group.is-open .nav-group-body { grid-template-rows: 1fr; }
        .nav-group-inner { overflow: hidden; min-height: 0; }

        .nav-icon {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.9rem;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.12);
        }
        .nav-icon--sm {
            width: 1.85rem;
            height: 1.85rem;
            border-radius: 0.55rem;
            font-size: 0.78rem;
        }
        .nav-icon--dashboard { background: linear-gradient(135deg, #2563EB, #1d4ed8); color: #fff; }
        .nav-icon--fournisseur { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
        .nav-icon--depot { background: linear-gradient(135deg, #06b6d4, #0891b2); color: #fff; }
        .nav-icon--gestion { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; }
        .nav-icon--operations { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
        .nav-icon--systeme { background: linear-gradient(135deg, #64748b, #475569); color: #fff; }
        .nav-icon--logout { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 0.85rem;
            margin: 0;
            border-radius: 0.65rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: rgba(255,255,255,0.72);
            text-decoration: none;
            transition: background 0.2s, color 0.2s, transform 0.15s;
            line-height: 1.35;
            border: 1px solid transparent;
        }
        .nav-item:hover {
            color: #fff;
            background: rgba(255,255,255,0.07);
            transform: translateX(2px);
        }
        .nav-item.is-active {
            color: #fff;
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.35), rgba(59, 130, 246, 0.18));
            border-color: rgba(96, 165, 250, 0.35);
            box-shadow: 0 2px 12px rgba(37, 99, 235, 0.2);
        }
        .nav-item.is-active .nav-icon--sm {
            background: rgba(255,255,255,0.18);
            color: #fff;
            box-shadow: none;
        }
        .nav-item-text { flex: 1; min-width: 0; }

        .nav-item--standalone {
            margin-bottom: 0.65rem;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
        }
        .nav-item--standalone.is-active {
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.32), rgba(59, 130, 246, 0.15));
        }

        .nav-subgroup {
            padding: 0.35rem 0.65rem 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            background: rgba(0,0,0,0.12);
        }
        .nav-subgroup .nav-item {
            font-size: 0.8125rem;
            padding: 0.62rem 0.7rem;
        }
        .nav-subgroup .nav-icon--sm {
            background: rgba(255,255,255,0.08);
            color: rgba(191, 219, 254, 0.95);
        }

        .sidebar-footer {
            flex-shrink: 0;
            padding: 1rem 0.85rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            background: rgba(0,0,0,0.15);
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.7rem;
            margin-bottom: 0.5rem;
            border-radius: 0.65rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-user-avatar {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            flex-shrink: 0;
            overflow: hidden;
            border: 2px solid rgba(147, 197, 253, 0.45);
            box-shadow: 0 4px 12px rgba(2, 6, 23, 0.35);
        }
        .sidebar-user-avatar img,
        .topbar-profile-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .sidebar-user-role {
            font-size: 0.6875rem;
            color: rgba(191, 219, 254, 0.75);
            margin: 0.15rem 0 0;
            line-height: 1.3;
        }
        .sidebar-user-name {
            font-size: 0.8125rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.25;
        }

        .topbar-profile {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.35rem 0.5rem 0.35rem 0.35rem;
            border-radius: 0.75rem;
            background: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.05);
        }
        .topbar-profile-photo-wrap {
            width: 2.35rem;
            height: 2.35rem;
            border-radius: 0.6rem;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid #bfdbfe;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15);
        }
        .topbar-profile-name {
            margin: 0;
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #071A35;
            line-height: 1.2;
            white-space: nowrap;
        }
        .topbar-profile-role {
            margin: 0.1rem 0 0;
            font-size: 0.625rem;
            font-weight: 500;
            color: #64748b;
            line-height: 1.2;
            white-space: nowrap;
        }

        /* ── Main ── */
        .app-main {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }
        @media (min-width: 768px) {
            .app-main { margin-left: 300px; transition: margin-left 0.32s cubic-bezier(0.4, 0, 0.2, 1); }
        }
        @media (max-width: 767px) {
            .app-sidebar { width: 260px; }
            .app-main { margin-left: 260px; transition: margin-left 0.32s cubic-bezier(0.4, 0, 0.2, 1); }
        }

        .app-topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            flex-shrink: 0;
            background: linear-gradient(90deg, #ffffff 0%, #f8fafc 55%, #f0f7ff 100%);
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 2px 12px rgba(7, 26, 53, 0.06);
        }
        .app-topbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #020617, #0F4C81, #2563EB, #06b6d4);
        }
        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.65rem 1rem;
            min-height: 4.25rem;
            position: relative;
        }
        @media (min-width: 768px) { .topbar-inner { padding: 0.65rem 1.5rem; } }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-width: 0;
            flex: 1;
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-width: 0;
        }
        .topbar-brand-logo {
            height: 2.5rem;
            width: auto;
            max-width: 7.5rem;
            object-fit: contain;
            flex-shrink: 0;
        }
        .topbar-brand-text { min-width: 0; }
        .topbar-brand-eyebrow {
            margin: 0;
            font-size: 0.625rem;
            font-weight: 600;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: #64748b;
            line-height: 1.2;
        }
        .topbar-brand-title {
            margin: 0.15rem 0 0;
            font-family: 'Poppins', sans-serif;
            font-size: clamp(0.95rem, 2.2vw, 1.2rem);
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #071A35;
            line-height: 1.25;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }
        .topbar-brand-year {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.55rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #1e40af;
            background: linear-gradient(135deg, #dbeafe, #eff6ff);
            border: 1px solid #93c5fd;
            border-radius: 999px;
            white-space: nowrap;
        }
        .topbar-page-title {
            margin: 0.2rem 0 0;
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748b;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .topbar-divider {
            display: none;
            width: 1px;
            height: 2.25rem;
            background: linear-gradient(180deg, transparent, #cbd5e1, transparent);
            flex-shrink: 0;
        }
        @media (min-width: 768px) { .topbar-divider { display: block; } }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .page-body {
            flex: 1;
            padding: 1rem;
        }
        @media (min-width: 768px) { .page-body { padding: 1.5rem; } }

        .page-container {
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
        }

        /* ── Composants ── */
        .stats-row {
            display: grid;
            gap: 0.625rem;
            margin-bottom: 1.25rem;
        }
        .stats-row.cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .stats-row.cols-5 { grid-template-columns: repeat(5, minmax(0, 1fr)); }
        @media (max-width: 900px) {
            .stats-row { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .stats-row.cols-3,
            .stats-row.cols-5 {
                grid-template-columns: none;
                grid-auto-flow: column;
                grid-auto-columns: minmax(120px, 1fr);
            }
        }
        .stat-card {
            background: #fff;
            border-radius: 0.5rem;
            padding: 0.625rem 0.75rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px rgba(15,23,42,0.04);
            min-width: 0;
        }
        .stat-label {
            font-size: 0.6875rem;
            color: #64748b;
            font-weight: 500;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .stat-value {
            font-size: 1.125rem;
            font-weight: 700;
            color: #071A35;
            margin: 0.125rem 0 0;
            font-family: 'Poppins', sans-serif;
            line-height: 1.2;
        }

        .content-panel {
            background: #fff;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(15,23,42,0.04);
            overflow: hidden;
        }
        .content-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            background: #fafbfc;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(90deg, #2563EB, #3B82F6);
            border-radius: 0.5rem;
            text-decoration: none;
            white-space: nowrap;
        }
        .btn-primary:hover { opacity: 0.92; }

        .overlay {
            display: none !important;
        }
    </style>
    @stack('styles')
</head>
<body class="text-slate-800 antialiased">

@php
    $mod = fn (string $k) => route('module.show', $k);
    $activeMod = fn (string $k) => request()->routeIs('module.show') && request()->route('module') === $k;

    $openFournisseur = request()->routeIs('fournisseurs.fiche.*') || request()->routeIs('fournisseurs.bons-achats.*')
        || request()->routeIs('fournisseurs.reglement.*') || request()->routeIs('fournisseurs.balance.*')
        || $activeMod('etat-fournisseur');
    $openDepot = $activeMod('depot.iam') || $activeMod('depot.divers');
    $openGestion = $activeMod('fiche-technicien') || $activeMod('etat-travaux')
        || $activeMod('rapport-travaux') || $activeMod('rapport-technicien');
    $openSysteme = $activeMod('configuration') || request()->routeIs('systeme.utilisateurs.*');
    $openOperations = request()->routeIs('products.*') || request()->routeIs('tasks.*') || request()->routeIs('users.*');
@endphp

<div class="app-shell">

    {{-- Sidebar --}}
    <aside id="sidebar" class="app-sidebar text-white">
        <div class="sidebar-brand">
            <div class="sidebar-brand-inner">
                <div class="sidebar-brand-logo">
                    <img src="{{ asset('images/logo-gds.png') }}" alt="GDS">
                </div>
                <div class="min-w-0">
                    <p class="font-poppins font-bold text-sm leading-tight truncate">GROUPE DLIMI</p>
                    <p class="text-[10px] text-blue-300/70 uppercase tracking-widest">Services</p>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-item nav-item--standalone {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                <span class="nav-icon nav-icon--dashboard"><i class="fa-solid fa-gauge-high"></i></span>
                <span class="nav-item-text font-poppins font-semibold">Tableau de bord</span>
            </a>

            <div class="nav-group {{ $openFournisseur ? 'is-open has-active' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" aria-expanded="{{ $openFournisseur ? 'true' : 'false' }}">
                    <span class="nav-group-left">
                        <span class="nav-icon nav-icon--fournisseur"><i class="fa-solid fa-truck-field"></i></span>
                        <span class="nav-group-label">Fournisseur</span>
                    </span>
                    <span class="nav-group-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                </button>
                <div class="nav-group-body">
                    <div class="nav-group-inner">
                        <div class="nav-subgroup">
                            <a href="{{ route('fournisseurs.fiche.index') }}" class="nav-item {{ request()->routeIs('fournisseurs.fiche.*') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-id-card"></i></span>
                                <span class="nav-item-text">Fiche Fournisseur</span>
                            </a>
                            <a href="{{ route('fournisseurs.bons-achats.index') }}" class="nav-item {{ request()->routeIs('fournisseurs.bons-achats.*') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-file-invoice"></i></span>
                                <span class="nav-item-text">Bon d'achats</span>
                            </a>
                            <a href="{{ route('fournisseurs.reglement.index') }}" class="nav-item {{ request()->routeIs('fournisseurs.reglement.*') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-money-check-dollar"></i></span>
                                <span class="nav-item-text">Règlement</span>
                            </a>
                            <a href="{{ route('fournisseurs.balance.index') }}" class="nav-item {{ request()->routeIs('fournisseurs.balance.*') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-scale-balanced"></i></span>
                                <span class="nav-item-text">Balance</span>
                            </a>
                            <a href="{{ $mod('etat-fournisseur') }}" class="nav-item {{ $activeMod('etat-fournisseur') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-chart-pie"></i></span>
                                <span class="nav-item-text">État Fournisseur</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nav-group {{ $openDepot ? 'is-open has-active' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" aria-expanded="{{ $openDepot ? 'true' : 'false' }}">
                    <span class="nav-group-left">
                        <span class="nav-icon nav-icon--depot"><i class="fa-solid fa-warehouse"></i></span>
                        <span class="nav-group-label">Dépôt</span>
                    </span>
                    <span class="nav-group-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                </button>
                <div class="nav-group-body">
                    <div class="nav-group-inner">
                        <div class="nav-subgroup">
                            <a href="{{ $mod('depot.iam') }}" class="nav-item {{ $activeMod('depot.iam') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-server"></i></span>
                                <span class="nav-item-text">Dépôt IAM</span>
                            </a>
                            <a href="{{ $mod('depot.divers') }}" class="nav-item {{ $activeMod('depot.divers') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-boxes-packing"></i></span>
                                <span class="nav-item-text">Dépôt Divers</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nav-group {{ $openGestion ? 'is-open has-active' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" aria-expanded="{{ $openGestion ? 'true' : 'false' }}">
                    <span class="nav-group-left">
                        <span class="nav-icon nav-icon--gestion"><i class="fa-solid fa-chart-line"></i></span>
                        <span class="nav-group-label">Gestion</span>
                    </span>
                    <span class="nav-group-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                </button>
                <div class="nav-group-body">
                    <div class="nav-group-inner">
                        <div class="nav-subgroup">
                            <a href="{{ $mod('fiche-technicien') }}" class="nav-item {{ $activeMod('fiche-technicien') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-id-badge"></i></span>
                                <span class="nav-item-text">Fiche Technicien</span>
                            </a>
                            <a href="{{ $mod('etat-travaux') }}" class="nav-item {{ $activeMod('etat-travaux') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-hard-hat"></i></span>
                                <span class="nav-item-text">État Travaux</span>
                            </a>
                            <a href="{{ $mod('rapport-travaux') }}" class="nav-item {{ $activeMod('rapport-travaux') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-file-lines"></i></span>
                                <span class="nav-item-text">Rapport Travaux</span>
                            </a>
                            <a href="{{ $mod('rapport-technicien') }}" class="nav-item {{ $activeMod('rapport-technicien') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-clipboard-user"></i></span>
                                <span class="nav-item-text">Rapport Technicien</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->canManage() || auth()->user()->isTechnician())
            <div class="nav-group {{ $openOperations ? 'is-open has-active' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" aria-expanded="{{ $openOperations ? 'true' : 'false' }}">
                    <span class="nav-group-left">
                        <span class="nav-icon nav-icon--operations"><i class="fa-solid fa-screwdriver-wrench"></i></span>
                        <span class="nav-group-label">Opérations</span>
                    </span>
                    <span class="nav-group-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                </button>
                <div class="nav-group-body">
                    <div class="nav-group-inner">
                        <div class="nav-subgroup">
                            @if(auth()->user()->canManage())
                            <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-boxes-stacked"></i></span>
                                <span class="nav-item-text">Gestion Stock</span>
                            </a>
                            @endif
                            <a href="{{ route('tasks.index') }}" class="nav-item {{ request()->routeIs('tasks.*') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-list-check"></i></span>
                                <span class="nav-item-text">Tâches</span>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-users-gear"></i></span>
                                <span class="nav-item-text">Équipe</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="nav-group {{ $openSysteme ? 'is-open has-active' : '' }}" data-nav-group>
                <button type="button" class="nav-group-toggle" aria-expanded="{{ $openSysteme ? 'true' : 'false' }}">
                    <span class="nav-group-left">
                        <span class="nav-icon nav-icon--systeme"><i class="fa-solid fa-sliders"></i></span>
                        <span class="nav-group-label">Système</span>
                    </span>
                    <span class="nav-group-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                </button>
                <div class="nav-group-body">
                    <div class="nav-group-inner">
                        <div class="nav-subgroup">
                            <a href="{{ $mod('configuration') }}" class="nav-item {{ $activeMod('configuration') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-gear"></i></span>
                                <span class="nav-item-text">Configuration</span>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('systeme.utilisateurs.index') }}" class="nav-item {{ request()->routeIs('systeme.utilisateurs.*') ? 'is-active' : '' }}">
                                <span class="nav-icon nav-icon--sm"><i class="fa-solid fa-user-shield"></i></span>
                                <span class="nav-item-text">Utilisateur</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    <img src="{{ asset('images/profile-khadija.jpg') }}"
                         alt="Mme Khadija"
                         onerror="this.onerror=null;this.src='{{ asset('images/profile-khadija.svg') }}';">
                </div>
                <div class="min-w-0">
                    <p class="sidebar-user-name truncate">Mme Khadija</p>
                    <p class="sidebar-user-role truncate">Directrice Générale</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item w-full border-0 bg-transparent cursor-pointer">
                    <span class="nav-icon nav-icon--sm nav-icon--logout"><i class="fa-solid fa-right-from-bracket"></i></span>
                    <span class="nav-item-text">Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

  <button type="button" id="sidebarToggle" class="sidebar-network-toggle" aria-label="Ouvrir ou fermer le menu" aria-expanded="true" title="Menu navigation">
        <svg class="network-icon" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <defs>
                <linearGradient id="netHubGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#fbbf24"/>
                    <stop offset="100%" stop-color="#f59e0b"/>
                </linearGradient>
                <linearGradient id="netNodeGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#67e8f9"/>
                    <stop offset="100%" stop-color="#38bdf8"/>
                </linearGradient>
                <linearGradient id="netLineGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#22d3ee"/>
                    <stop offset="50%" stop-color="#60a5fa"/>
                    <stop offset="100%" stop-color="#f59e0b"/>
                </linearGradient>
            </defs>
            <circle class="net-glow" cx="16" cy="16" r="10" stroke="url(#netLineGrad)" stroke-width="0.6" opacity="0.4"/>
            <line class="net-flow" x1="16" y1="10" x2="8" y2="22" stroke="url(#netLineGrad)" stroke-width="1.5" stroke-linecap="round"/>
            <line class="net-flow" x1="16" y1="10" x2="24" y2="22" stroke="url(#netLineGrad)" stroke-width="1.5" stroke-linecap="round" style="animation-delay: -0.6s"/>
            <line class="net-flow" x1="8" y1="22" x2="24" y2="22" stroke="url(#netLineGrad)" stroke-width="1.5" stroke-linecap="round" style="animation-delay: -1.2s"/>
            <line class="net-flow" x1="16" y1="10" x2="16" y2="6" stroke="url(#netLineGrad)" stroke-width="1.5" stroke-linecap="round" style="animation-delay: -0.3s"/>
            <circle cx="16" cy="6" r="2.2" fill="url(#netNodeGrad)"/>
            <circle cx="8" cy="22" r="2.2" fill="url(#netNodeGrad)"/>
            <circle cx="24" cy="22" r="2.2" fill="url(#netNodeGrad)"/>
            <circle cx="16" cy="10" r="3.2" fill="url(#netHubGrad)"/>
            <circle cx="16" cy="10" r="1.4" fill="#fff" opacity="0.85"/>
        </svg>
    </button>

    {{-- Main --}}
    <div class="app-main">
        <header class="app-topbar">
            <div class="topbar-inner">
                <div class="topbar-left">
                    <div class="topbar-brand">
                        <img src="{{ asset('images/logo-gds.png') }}" alt="GROUPE DLIMI SERVICES" class="topbar-brand-logo">
                        <div class="topbar-brand-text">
                            <p class="topbar-brand-eyebrow">Installation réseautique — Groupe DLIMI Services</p>
                            <h1 class="topbar-brand-title">
                                Suivi opérationnel
                                <span class="topbar-brand-year">Exercice {{ date('Y') }}</span>
                            </h1>
                            @if(trim($__env->yieldContent('page-title')) !== '')
                                <p class="topbar-page-title">@yield('page-title')</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="topbar-right">
                    @yield('header-actions')
                    <span class="hidden md:inline-flex items-center gap-1.5 text-[11px] font-medium text-slate-500 bg-white border border-slate-200 px-2.5 py-1.5 rounded-lg shadow-sm">
                        <i class="fa-solid fa-circle text-[6px] text-emerald-500"></i>
                        Réseau actif
                    </span>
                    <div class="topbar-profile">
                        <div class="topbar-profile-photo-wrap">
                            <img src="{{ asset('images/profile-khadija.jpg') }}"
                                 alt="Mme Khadija"
                                 class="topbar-profile-photo"
                                 onerror="this.onerror=null;this.src='{{ asset('images/profile-khadija.svg') }}';">
                        </div>
                        <div class="hidden sm:block min-w-0">
                            <p class="topbar-profile-name">Mme Khadija</p>
                            <p class="topbar-profile-role">Directrice Générale</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="page-body">
            <div class="page-container">
                @if(session('success'))
                    <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-5 p-3.5 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                        @foreach($errors->all() as $error)<p class="m-0">{{ $error }}</p>@endforeach
                    </div>
                @endif
                @yield('content')
            </div>
        </main>

        <footer class="text-center text-[10px] text-slate-400 py-3 border-t border-slate-200 bg-white">
            &copy; {{ date('Y') }} A2S — Créé par A2SPRO
        </footer>
    </div>
</div>

<script>
    document.querySelectorAll('[data-nav-group] .nav-group-toggle').forEach((btn) => {
        btn.addEventListener('click', () => {
            const group = btn.closest('[data-nav-group]');
            const isOpen = group.classList.toggle('is-open');
            btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    });

    (function () {
        const shell = document.querySelector('.app-shell');
        const toggle = document.getElementById('sidebarToggle');
        if (!shell || !toggle) return;

        const storageKey = 'gds-sidebar-collapsed';

        function setCollapsed(collapsed) {
            shell.classList.toggle('sidebar-collapsed', collapsed);
            toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            toggle.title = collapsed ? 'Ouvrir le menu' : 'Fermer le menu';
            try { localStorage.setItem(storageKey, collapsed ? '1' : '0'); } catch (e) {}
        }

        try {
            if (localStorage.getItem(storageKey) === '1') setCollapsed(true);
        } catch (e) {}

        toggle.addEventListener('click', () => {
            setCollapsed(!shell.classList.contains('sidebar-collapsed'));
        });
    })();
</script>
@stack('scripts')
</body>
</html>
