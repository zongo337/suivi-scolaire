@extends('layouts.app')

@section('title', 'Mon profil')
@section('page-title', 'Mon profil')

@section('content')

<div style="max-width:560px;">
  <div class="card">
    <div class="card-header">
      <span class="card-title">
        <i class="ti ti-user"></i> Informations du compte
      </span>
    </div>
    <div style="padding:24px;">

      <!-- AVATAR -->
      <div style="text-align:center; margin-bottom:24px;">
        <div style="width:72px; height:72px; border-radius:50%;
                    background:var(--green-light); color:var(--green-dark);
                    display:flex; align-items:center; justify-content:center;
                    font-size:28px; font-weight:600; margin:0 auto 12px;">
          {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
        <div style="font-size:16px; font-weight:600;">{{ auth()->user()->name }}</div>
        <div style="font-size:13px; color:var(--text-secondary);">{{ auth()->user()->email }}</div>
        <span class="badge badge-success" style="margin-top:6px;">Administrateur</span>
      </div>

      <!-- FORMULAIRE MODIFIER NOM -->
      <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label class="form-label">Nom complet</label>
          <input type="text" name="name" class="form-control"
                 value="{{ old('name', auth()->user()->name) }}" required>
          @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control"
                 value="{{ old('email', auth()->user()->email) }}" required>
          @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div style="padding-top:16px; border-top:1px solid var(--border); margin-bottom:20px;">
          <button type="submit" class="btn-primary">
            <i class="ti ti-check"></i> Mettre à jour
          </button>
        </div>
      </form>

      <!-- FORMULAIRE CHANGER MOT DE PASSE -->
      <form method="POST" action="{{ route('profile.password') }}">
        @csrf
        @method('PUT')

        <div style="font-size:13px; font-weight:600; color:var(--green-dark);
                    text-transform:uppercase; letter-spacing:0.8px; margin-bottom:16px;
                    padding-bottom:8px; border-bottom:1px solid var(--border);">
          Changer le mot de passe
        </div>

        <div class="form-group">
          <label class="form-label">Mot de passe actuel</label>
          <input type="password" name="current_password" class="form-control"
                 placeholder="••••••••">
          @error('current_password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Nouveau mot de passe</label>
          <input type="password" name="password" class="form-control"
                 placeholder="••••••••">
          @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Confirmer le mot de passe</label>
          <input type="password" name="password_confirmation" class="form-control"
                 placeholder="••••••••">
        </div>

        <button type="submit" class="btn-primary">
          <i class="ti ti-lock"></i> Changer le mot de passe
        </button>
      </form>

    </div>
  </div>
</div>

@endsection