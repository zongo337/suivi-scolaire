@extends('layouts.app')

@section('title', 'Nouvelle annonce')
@section('page-title', 'Publier une annonce')

@section('topbar-actions')
  <a href="{{ route('annonces.index') }}" class="btn-secondary">
    <i class="ti ti-arrow-left"></i> Retour
  </a>
@endsection

@section('content')

<div style="max-width:600px;">
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="ti ti-speakerphone"></i> Contenu de l'annonce</span>
    </div>
    <div style="padding:24px;">
      <form method="POST" action="{{ route('annonces.store') }}">
        @csrf

        <div class="form-group">
          <label class="form-label">Titre <span style="color:var(--red);">*</span></label>
          <input type="text" name="titre" class="form-control" placeholder="Ex: Réunion parents-enseignants"
                 value="{{ old('titre') }}" required>
          @error('titre') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Contenu <span style="color:var(--red);">*</span></label>
          <textarea name="contenu" class="form-control" rows="5"
                    placeholder="Détails de l'annonce...">{{ old('contenu') }}</textarea>
          @error('contenu') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-grid-2">
          <div class="form-group">
            <label class="form-label">Type <span style="color:var(--red);">*</span></label>
            <select name="type" class="form-select" required>
              <option value="annonce" {{ old('type') == 'annonce' ? 'selected' : '' }}>Annonce générale</option>
              <option value="notification" {{ old('type') == 'notification' ? 'selected' : '' }}>
                Notification importante (examen, réunion, échéance)
              </option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Destinataires</label>
            <select name="classe_id" class="form-select">
              <option value="">Toute l'école</option>
              @foreach($classes as $classe)
                <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                  {{ $classe->nom }} uniquement
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Date de publication</label>
          <input type="datetime-local" name="date_publication" class="form-control"
                 value="{{ old('date_publication', now()->format('Y-m-d\TH:i')) }}">
          <div style="font-size:12px; color:var(--text-muted); margin-top:4px;">
            Laisser la date actuelle pour une publication immédiate.
          </div>
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;
                    padding-top:16px; border-top:1px solid var(--border);">
          <a href="{{ route('annonces.index') }}" class="btn-secondary">Annuler</a>
          <button type="submit" class="btn-primary"><i class="ti ti-check"></i> Publier</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
