@extends('layouts.app')

@section('title', 'Modifier la classe')
@section('page-title', 'Modifier la classe')

@section('topbar-actions')
  <a href="{{ route('classes.index') }}" class="btn-secondary">
    <i class="ti ti-arrow-left"></i> Retour
  </a>
@endsection

@section('content')

<div style="max-width:540px;">
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="ti ti-pencil"></i> Modifier — {{ $classe->nom }}</span>
    </div>
    <div style="padding:24px;">
      <form method="POST" action="{{ route('classes.update', $classe) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label class="form-label">Nom de la classe <span style="color:var(--red);">*</span></label>
          <input type="text" name="nom" class="form-control"
                 value="{{ old('nom', $classe->nom) }}" required>
          @error('nom') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Effectif maximum <span style="color:var(--red);">*</span></label>
          <input type="number" name="effectif_max" class="form-control"
                 value="{{ old('effectif_max', $classe->effectif_max) }}" min="1" required>
          @error('effectif_max') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Frais de scolarité (FCFA) <span style="color:var(--red);">*</span></label>
          <input type="number" name="frais_scolarite" class="form-control"
                 value="{{ old('frais_scolarite', $classe->frais_scolarite) }}" min="0" required>
          @error('frais_scolarite') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;
                    padding-top:16px; border-top:1px solid var(--border);">
          <a href="{{ route('classes.index') }}" class="btn-secondary">Annuler</a>
          <button type="submit" class="btn-primary">
            <i class="ti ti-check"></i> Enregistrer
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection