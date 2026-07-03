@extends('layouts.app')

@section('title', 'Nouvel enseignant')
@section('page-title', 'Créer un enseignant')

@section('topbar-actions')
  <a href="{{ route('enseignants.index') }}" class="btn-secondary">
    <i class="ti ti-arrow-left"></i> Retour
  </a>
@endsection

@section('content')

<div style="max-width:560px;">
  <div class="card">
    <div class="card-header">
      <span class="card-title">
        <i class="ti ti-chalkboard"></i> Informations de l'enseignant
      </span>
    </div>
    <div style="padding:24px;">
      <form method="POST" action="{{ route('enseignants.store') }}">
        @csrf

        <div class="form-group">
          <label class="form-label">
            Nom complet <span style="color:var(--red);">*</span>
          </label>
          <input type="text" name="name" class="form-control"
                 placeholder="Ex: KABORÉ Issa"
                 value="{{ old('name') }}" required>
          @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">
            Email <span style="color:var(--red);">*</span>
          </label>
          <input type="email" name="email" class="form-control"
                 placeholder="Ex: enseignant@ecole.bf"
                 value="{{ old('email') }}" required>
          @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">
            Classe assignée <span style="color:var(--red);">*</span>
          </label>
          <select name="classe_id" class="form-select" required>
            <option value="">Choisir une classe...</option>
            @foreach($classes as $classe)
              <option value="{{ $classe->id }}"
                {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                {{ $classe->nom }}
              </option>
            @endforeach
          </select>
          @error('classe_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-grid-2">
          <div class="form-group">
            <label class="form-label">
              Mot de passe <span style="color:var(--red);">*</span>
            </label>
            <input type="password" name="password" class="form-control"
                   placeholder="Min. 6 caractères" required>
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label class="form-label">
              Confirmer le mot de passe <span style="color:var(--red);">*</span>
            </label>
            <input type="password" name="password_confirmation" class="form-control"
                   placeholder="Répéter le mot de passe" required>
          </div>
        </div>

        {{-- Info --}}
        <div style="background:var(--blue-light); border-radius:8px; padding:12px 16px;
                    margin-bottom:18px; font-size:13px; color:var(--blue);
                    display:flex; align-items:center; gap:8px;">
          <i class="ti ti-info-circle" style="font-size:16px;"></i>
          L'enseignant pourra se connecter avec cet email et mot de passe.
          Il aura accès uniquement aux notes de sa classe.
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;
                    padding-top:16px; border-top:1px solid var(--border);">
          <a href="{{ route('enseignants.index') }}" class="btn-secondary">Annuler</a>
          <button type="submit" class="btn-primary">
            <i class="ti ti-check"></i> Créer l'enseignant
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection