@extends('layouts.app')

@section('title', 'Nouvelle absence')
@section('page-title', 'Déclarer une absence')

@section('topbar-actions')
  <a href="{{ route('absences.index') }}" class="btn-secondary">
    <i class="ti ti-arrow-left"></i> Retour
  </a>
@endsection

@section('content')

<div style="max-width:560px;">
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="ti ti-calendar-x"></i> Détails de l'absence</span>
    </div>
    <div style="padding:24px;">
      <form method="POST" action="{{ route('absences.store') }}">
        @csrf

        <div class="form-group">
          <label class="form-label">Élève <span style="color:var(--red);">*</span></label>
          <select name="eleve_id" class="form-select" required>
            <option value="">Choisir un élève...</option>
            @foreach($eleves as $eleve)
              <option value="{{ $eleve->id }}" {{ old('eleve_id') == $eleve->id ? 'selected' : '' }}>
                {{ $eleve->prenom }} {{ $eleve->nom }} — {{ $eleve->classe->nom ?? '—' }}
              </option>
            @endforeach
          </select>
          @error('eleve_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Date de l'absence <span style="color:var(--red);">*</span></label>
          <input type="date" name="date_absence" class="form-control"
                 value="{{ old('date_absence', now()->format('Y-m-d')) }}" required>
          @error('date_absence') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Motif (si renseigné)</label>
          <input type="text" name="motif" class="form-control" placeholder="Ex: Maladie"
                 value="{{ old('motif') }}">
        </div>

        <div class="form-group">
          <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
            <input type="checkbox" name="justifiee" value="1" {{ old('justifiee') ? 'checked' : '' }}>
            <span class="form-label" style="margin-bottom:0;">Absence justifiée</span>
          </label>
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;
                    padding-top:16px; border-top:1px solid var(--border);">
          <a href="{{ route('absences.index') }}" class="btn-secondary">Annuler</a>
          <button type="submit" class="btn-primary"><i class="ti ti-check"></i> Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
