<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion – ScolaritéPro</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'DM Sans', sans-serif;
    background: #f4f6f5;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .login-wrapper {
    display: flex;
    width: 860px;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
  }
  .login-left {
    background: #0a5c46;
    flex: 1;
    padding: 48px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    color: #fff;
  }
  .login-left h1 { font-size: 26px; font-weight: 600; margin-bottom: 12px; }
  .login-left p  { font-size: 14px; color: rgba(255,255,255,0.7); line-height: 1.6; }
  .login-left .info-item {
    display: flex; align-items: center; gap: 10px;
    margin-top: 24px; font-size: 13px; color: rgba(255,255,255,0.8);
  }
  .login-left .info-item i { font-size: 20px; color: #5DCAA5; }

  .login-right { flex: 1; padding: 48px 40px; }
  .login-right h2 { font-size: 22px; font-weight: 600; color: #1a2e26; margin-bottom: 8px; }
  .login-right p  { font-size: 13px; color: #5a7068; margin-bottom: 32px; }

  .form-group { margin-bottom: 18px; }
  .form-label { font-size: 13px; font-weight: 500; color: #1a2e26; margin-bottom: 6px; display: block; }
  .form-control {
    width: 100%; padding: 10px 14px;
    border: 1px solid #dde8e4; border-radius: 8px;
    font-size: 14px; font-family: inherit; color: #1a2e26;
    outline: none; transition: border 0.15s;
  }
  .form-control:focus { border-color: #1D9E75; }
  .form-error { font-size: 12px; color: #A32D2D; margin-top: 4px; }

  .btn-login {
    width: 100%; padding: 11px;
    background: #0F6E56; color: #fff;
    border: none; border-radius: 8px;
    font-size: 15px; font-weight: 600;
    cursor: pointer; font-family: inherit;
    transition: background 0.15s;
    margin-top: 8px;
  }
  .btn-login:hover { background: #1D9E75; }

  .alert-danger {
    background: #FCEBEB; color: #A32D2D;
    padding: 10px 14px; border-radius: 8px;
    font-size: 13px; margin-bottom: 20px;
    display: flex; align-items: center; gap: 8px;
  }
</style>
</head>
<body>
<div class="login-wrapper">
  <!-- GAUCHE -->
  <div class="login-left">
    <div style="font-size:40px; margin-bottom:20px;">🏫</div>
    <h1>ScolaritéPro</h1>
    <p>Système de Gestion de Scolarité et de Notes — Cycle Primaire (CP1 au CM2)</p>
    <div class="info-item"><i class="ti ti-users"></i> Gestion des élèves et classes</div>
    <div class="info-item"><i class="ti ti-cash"></i> Suivi des paiements</div>
    <div class="info-item"><i class="ti ti-pencil"></i> Saisie des notes et moyennes</div>
    <div class="info-item"><i class="ti ti-file-text"></i> Génération de reçus PDF</div>
    <div style="margin-top:40px; font-size:11px; color:rgba(255,255,255,0.4);">
      Université Joseph Ki-Zerbo – UFR/SEA
    </div>
  </div>

  <!-- DROITE -->
  <div class="login-right">
    <h2>Connexion</h2>
    <p>Connectez-vous à votre espace de gestion</p>

    @if($errors->any())
      <div class="alert-danger">
        <i class="ti ti-alert-circle"></i> {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Adresse email</label>
        <input type="email" name="email" class="form-control"
               placeholder="admin@ecole.bf" value="{{ old('email') }}" required>
        @error('email')
          <div class="form-error">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password" class="form-control"
               placeholder="••••••••" required>
        @error('password')
          <div class="form-error">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn-login">
        <i class="ti ti-login"></i> Se connecter
      </button>
    </form>
  </div>
</div>
</body>
</html>