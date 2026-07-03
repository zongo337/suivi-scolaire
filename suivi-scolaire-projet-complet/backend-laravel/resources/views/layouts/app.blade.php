<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>ScolaritéPro – @yield('title', 'Tableau de bord')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --green-dark: #0F6E56;
    --green-mid: #1D9E75;
    --green-light: #E1F5EE;
    --sidebar-bg: #0a5c46;
    --sidebar-hover: #0e7560;
    --white: #ffffff;
    --bg: #f4f6f5;
    --text-primary: #1a2e26;
    --text-secondary: #5a7068;
    --text-muted: #8fa89f;
    --border: #dde8e4;
    --blue: #185FA5;
    --blue-light: #E6F1FB;
    --orange: #BA7517;
    --orange-light: #FAEEDA;
    --red: #A32D2D;
    --red-light: #FCEBEB;
  }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: var(--text-primary);
    display: flex;
    height: 100vh;
    overflow: hidden;
  }

  /* SIDEBAR */
  .sidebar {
    width: 240px;
    min-width: 240px;
    background: var(--sidebar-bg);
    display: flex;
    flex-direction: column;
    height: 100vh;
    overflow-y: auto;
  }

  .sidebar-brand {
    padding: 20px 20px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
  }

  .brand-name {
    font-size: 17px;
    font-weight: 600;
    color: #fff;
  }

  .brand-sub {
    font-size: 11px;
    color: rgba(255,255,255,0.55);
    margin-top: 2px;
    line-height: 1.4;
  }

  .sidebar-section {
    padding: 16px 12px 4px;
  }

  .sidebar-label {
    font-size: 10px;
    font-weight: 600;
    color: rgba(255,255,255,0.4);
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 0 8px;
    margin-bottom: 6px;
  }

  .nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    color: rgba(255,255,255,0.7);
    cursor: pointer;
    transition: background 0.15s, color 0.15s;
    margin-bottom: 2px;
    text-decoration: none;
  }

  .nav-item i { font-size: 18px; }

  .nav-item:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
  }

  .nav-item.active {
    background: var(--green-mid);
    color: #fff;
  }

  .sidebar-footer {
    margin-top: auto;
    padding: 12px;
    border-top: 1px solid rgba(255,255,255,0.1);
  }

  /* MAIN */
  .main {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  /* TOPBAR */
  .topbar {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 0 28px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
  }

  .topbar-left {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--text-secondary);
  }

  .topbar-left .page-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-primary);
  }

  .topbar-right {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .btn-primary {
    background: var(--green-dark);
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.15s;
    font-family: inherit;
    text-decoration: none;
  }

  .btn-primary:hover { background: var(--green-mid); color: #fff; }

  .btn-secondary {
    background: #fff;
    color: var(--text-primary);
    border: 1px solid var(--border);
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: inherit;
    text-decoration: none;
    transition: background 0.12s;
  }

  .btn-secondary:hover { background: var(--bg); }

  .btn-danger {
    background: var(--red-light);
    color: var(--red);
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    font-family: inherit;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }

  .user-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: var(--green-light);
    color: var(--green-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
  }

  /* CONTENT */
  .content {
    flex: 1;
    overflow-y: auto;
    padding: 24px 28px;
  }

  /* STATS CARDS */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }

  .stat-card {
    background: var(--white);
    border-radius: 12px;
    border: 1px solid var(--border);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    position: relative;
    overflow: hidden;
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 4px; height: 100%;
  }

  .stat-card.green::before { background: var(--green-mid); }
  .stat-card.blue::before  { background: var(--blue); }
  .stat-card.orange::before { background: var(--orange); }
  .stat-card.red::before   { background: var(--red); }

  .stat-value { font-size: 28px; font-weight: 600; line-height: 1; }
  .stat-card.green .stat-value  { color: var(--green-dark); }
  .stat-card.blue .stat-value   { color: var(--blue); }
  .stat-card.orange .stat-value { color: var(--orange); }
  .stat-card.red .stat-value    { color: var(--red); }

  .stat-label {
    font-size: 13px;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 6px;
  }

  /* CARDS */
  .card {
    background: var(--white);
    border-radius: 12px;
    border: 1px solid var(--border);
    overflow: hidden;
    margin-bottom: 20px;
  }

  .card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
  }

  .card-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .card-title i { font-size: 18px; color: var(--green-mid); }

  .btn-outline {
    background: none;
    border: 1px solid var(--border);
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    color: var(--text-secondary);
    cursor: pointer;
    font-family: inherit;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }

  .btn-outline:hover { background: var(--bg); }

  /* TABLE */
  table { width: 100%; border-collapse: collapse; }

  thead th {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--text-muted);
    text-align: left;
    padding: 10px 20px;
    background: #f9fafb;
    border-bottom: 1px solid var(--border);
  }

  tbody td {
    padding: 12px 20px;
    font-size: 13.5px;
    color: var(--text-primary);
    border-bottom: 1px solid #f0f4f2;
    vertical-align: middle;
  }

  tbody tr:last-child td { border-bottom: none; }
  tbody tr:hover td { background: #f8fcfa; }

  /* AVATAR */
  .avatar-circle {
    width: 32px; height: 32px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 600; flex-shrink: 0;
  }

  .avatar-cell { display: flex; align-items: center; gap: 10px; }
  .avatar-name { font-weight: 500; font-size: 13.5px; }
  .avatar-sub  { font-size: 12px; color: var(--text-secondary); }

  /* BADGES */
  .badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
  }

  .badge-success { background: var(--green-light); color: var(--green-dark); }
  .badge-danger  { background: var(--red-light);   color: var(--red); }
  .badge-warning { background: var(--orange-light); color: var(--orange); }
  .badge-info    { background: var(--blue-light);  color: var(--blue); }
  .badge-gray    { background: #f0f4f2; color: var(--text-secondary); }

  /* PROGRESS */
  .progress-bar {
    height: 6px; background: var(--bg);
    border-radius: 10px; overflow: hidden; width: 100px;
  }
  .progress-fill { height: 100%; border-radius: 10px; background: var(--green-mid); }

  /* ALERTS */
  .alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 13.5px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .alert-success { background: var(--green-light); color: var(--green-dark); }
  .alert-danger  { background: var(--red-light);   color: var(--red); }

  /* FORM */
  .form-group { margin-bottom: 18px; }
  .form-label { font-size: 13px; font-weight: 500; color: var(--text-primary); margin-bottom: 6px; display: block; }
  .form-control {
    width: 100%; padding: 9px 12px;
    border: 1px solid var(--border); border-radius: 8px;
    font-size: 14px; font-family: inherit; color: var(--text-primary);
    background: #fff; transition: border 0.15s;
    outline: none;
  }
  .form-control:focus { border-color: var(--green-mid); }
  .form-select {
    width: 100%; padding: 9px 12px;
    border: 1px solid var(--border); border-radius: 8px;
    font-size: 14px; font-family: inherit; color: var(--text-primary);
    background: #fff; outline: none; cursor: pointer;
  }
  .form-select:focus { border-color: var(--green-mid); }
  .form-error { font-size: 12px; color: var(--red); margin-top: 4px; }

  .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

  /* TWO COL */
  .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
  .three-col { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px; }

  /* SCROLLBAR */
  ::-webkit-scrollbar { width: 5px; }
  ::-webkit-scrollbar-track { background: transparent; }
  ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-brand">
    @php $ecole = App\Models\Ecole::active(); @endphp
<div class="brand-name">🏫 {{ $ecole?->nom ?? 'ScolaritéPro' }}</div>
<div class="brand-sub">Gestion Scolarité & Notes<br>Cycle Primaire (CP1–CM2)</div>
  </div>

@if(auth()->user()->isEnseignant())
  <div class="sidebar-section">
    <div class="sidebar-label">Ma classe</div>

    <a class="nav-item {{ request()->routeIs('enseignant.notes') ? 'active' : '' }}"
       href="{{ route('enseignant.notes') }}">
      <i class="ti ti-pencil"></i> Saisie des notes
    </a>

    <a class="nav-item {{ request()->routeIs('enseignant.moyennes') ? 'active' : '' }}"
       href="{{ route('enseignant.moyennes') }}">
      <i class="ti ti-trophy"></i> Moyennes & Classement
    </a>

  </div>
@endif

{{-- Sidebar pour admin --}}
@if(auth()->user()->isAdmin())
<div class="sidebar-section">
  <div class="sidebar-label">Navigation</div>
  <a class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
     href="{{ route('dashboard') }}">
    <i class="ti ti-layout-dashboard"></i> Tableau de bord
  </a>
</div>

  <div class="sidebar-section">
    <div class="sidebar-label">Gestion Administrative</div>
    <a class="nav-item {{ request()->routeIs('eleves.*') ? 'active' : '' }}" href="{{ route('eleves.index') }}">
      <i class="ti ti-users"></i> Élèves
    </a>
    <a class="nav-item {{ request()->routeIs('classes.*') ? 'active' : '' }}" href="{{ route('classes.index') }}">
      <i class="ti ti-school"></i> Classes
    </a>
    <a class="nav-item {{ request()->routeIs('parents.*') ? 'active' : '' }}" href="{{ route('parents.index') }}">
      <i class="ti ti-user-heart"></i> Comptes parents
    </a>
    <a class="nav-item {{ request()->routeIs('paiements.*') ? 'active' : '' }}" href="{{ route('paiements.index') }}">
      <i class="ti ti-cash"></i> Paiements
    </a>
    <a class="nav-item {{ request()->routeIs('paiements.impayes') ? 'active' : '' }}" href="{{ route('paiements.impayes') }}">
      <i class="ti ti-alert-circle"></i> Impayés
    </a>
    <a class="nav-item {{ request()->routeIs('absences.*') ? 'active' : '' }}" href="{{ route('absences.index') }}">
      <i class="ti ti-calendar-x"></i> Absences
    </a>
    <a class="nav-item {{ request()->routeIs('annonces.*') ? 'active' : '' }}" href="{{ route('annonces.index') }}">
      <i class="ti ti-speakerphone"></i> Annonces
    </a>
  </div>
@endif

@if(auth()->user()->isEnseignant())
  <div class="sidebar-section">
    <div class="sidebar-label">Ma classe</div>
    <a class="nav-item {{ request()->routeIs('enseignant.absences') ? 'active' : '' }}"
       href="{{ route('enseignant.absences') }}">
      <i class="ti ti-calendar-x"></i> Absences
    </a>
  </div>
@endif

  <div class="sidebar-section">
    <div class="sidebar-label">Gestion Pédagogique</div>
    <a class="nav-item {{ request()->routeIs('matieres.*') ? 'active' : '' }}" href="{{ route('matieres.index') }}">
      <i class="ti ti-book"></i> Matières
    </a>
    <a class="nav-item {{ request()->routeIs('notes.index') ? 'active' : '' }}" href="{{ route('notes.index') }}">
      <i class="ti ti-pencil"></i> Saisie des notes
    </a>
    <a class="nav-item {{ request()->routeIs('notes.moyennes') ? 'active' : '' }}" href="{{ route('notes.moyennes') }}">
      <i class="ti ti-trophy"></i> Moyennes & Classement
    </a>
  </div>
  <div class="sidebar-section">
  <div class="sidebar-label">Configuration</div>
  <a class="nav-item {{ request()->routeIs('ecoles.*') ? 'active' : '' }}"
     href="{{ route('ecoles.index') }}">
    <i class="ti ti-building"></i> Écoles
  </a>
</div>

@if(auth()->user()->isAdmin())
  <a class="nav-item {{ request()->routeIs('enseignants.*') ? 'active' : '' }}"
     href="{{ route('enseignants.index') }}">
    <i class="ti ti-chalkboard"></i> Enseignants
  </a>
@endif

  <div class="sidebar-footer">
    <a class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}"
   href="{{ route('profile') }}">
  <i class="ti ti-user"></i> {{ auth()->user()->name }}
</a>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="nav-item" style="width:100%; background:none; border:none; cursor:pointer;">
        <i class="ti ti-logout"></i> Déconnexion
      </button>
    </form>
  </div>
</aside>

{{-- Dans la section Gestion Administrative, après Classes --}}
@if(auth()->user()->isAdmin())
<a class="nav-item {{ request()->routeIs('enseignants.*') ? 'active' : '' }}"
   href="{{ route('enseignants.index') }}">
  <i class="ti ti-chalkboard"></i> Enseignants
</a>
@endif

<!-- MAIN -->
<div class="main">
  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-left">
      <span style="color:var(--text-muted);">Accueil</span>
      <i class="ti ti-chevron-right" style="font-size:14px; color:var(--text-muted);"></i>
      <span class="page-title">@yield('page-title', 'Tableau de bord')</span>
    </div>
    <div class="topbar-right">
      @yield('topbar-actions')
      <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
    </div>
  </header>

  <!-- CONTENT -->
  <main class="content">
    @if(session('success'))
      <div class="alert alert-success">
        <i class="ti ti-check"></i> {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger">
        <i class="ti ti-x"></i> {{ session('error') }}
      </div>
    @endif

    @yield('content')
  </main>
</div>

</body>
</html>