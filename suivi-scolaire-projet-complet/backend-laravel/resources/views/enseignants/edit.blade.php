@extends('layouts.app')

@section('title', 'Modifier enseignant')
@section('page-title', 'Modifier l\'enseignant')

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
        <i class="ti ti-pencil"></i> Modifier — {{ $enseignant->name }}
      </span>
    </div>
    <div style="padding:24px;">
      <form method="POST" action="{{ route('enseignants.update', $enseignant) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label class="form-label">Nom complet <span style="color:var(--red);">*</span></label>
          <input type="text" name="name" class="form-control"
                 value="{{ old('name', $enseignant->name) }}" required>
          @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Email <span style="color:var(--red);">*</span></label>
          <input type="email" name="email" class="form-control"
                 value="{{ old('email', $enseignant->email) }}" required>
          @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Classe assignée <span style="color:var(--red);">*</span></label>
          <select name="classe_id" class="form-select" required>
            <option value="">Choisir une classe...</option>
            @foreach($classes as $classe)
              <option value="{{ $classe->id }}"
                {{ old('classe_id', $enseignant->classe_id) == $classe->id ? 'selected' : '' }}>
                {{ $classe->nom }}
              </option>
            @endforeach
          </select>
          @error('classe_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        {{-- Mot de passe optionnel --}}
        <div style="font-size:13px; font-weight:600; color:var(--green-dark);
                    text-transform:uppercase; letter-spacing:0.8px; margin-bottom:16px;
                    padding-bottom:8px; border-bottom:1px solid var(--border);">
          Changer le mot de passe (optionnel)
        </div>

        <div class="form-grid-2">
          <div class="form-group">
            <label class="form-label">Nouveau mot de passe</label>
            <input type="password" name="password" class="form-control"
                   placeholder="Laisser vide pour ne pas changer">
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Confirmer</label>
            <input type="password" name="password_confirmation" class="form-control"
                   placeholder="Confirmer le nouveau mot de passe">
          </div>
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;
                    padding-top:16px; border-top:1px solid var(--border);">
          <a href="{{ route('enseignants.index') }}" class="btn-secondary">Annuler</a>
          <button type="submit" class="btn-primary">
            <i class="ti ti-check"></i> Enregistrer
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection