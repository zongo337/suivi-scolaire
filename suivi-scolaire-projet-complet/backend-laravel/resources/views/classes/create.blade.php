@extends('layouts.app')

@section('title', 'Nouvelle classe')
@section('page-title', 'Nouvelle classe')

@section('topbar-actions')
  <a href="{{ route('classes.index') }}" class="btn-secondary">
    <i class="ti ti-arrow-left"></i> Retour
  </a>
@endsection

@section('content')

<div style="max-width:540px;">
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="ti ti-school"></i> Créer une classe</span>
    </div>
    <div style="padding:24px;">
      <form method="POST" action="{{ route('classes.store') }}">
        @csrf

        <div class="form-group">
          <label class="form-label">Nom de la classe <span style="color:var(--red);">*</span></label>
          <select name="nom" class="form-select" required>
            <option value="">Choisir...</option>
            @foreach(['CP1','CP2','CE1','CE2','CM1','CM2'] as $niveau)
              <option value="{{ $niveau }}" {{ old('nom') === $niveau ? 'selected' : '' }}>
                {{ $niveau }}
              </option>
            @endforeach
          </select>
          @error('nom') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Effectif maximum <span style="color:var(--red);">*</span></label>
          <input type="number" name="effectif_max" class="form-control"
                 placeholder="Ex: 40" value="{{ old('effectif_max', 40) }}" min="1" required>
          @error('effectif_max') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Frais de scolarité (FCFA) <span style="color:var(--red);">*</span></label>
          <input type="number" name="frais_scolarite" class="form-control"
                 placeholder="Ex: 25000" value="{{ old('frais_scolarite', 25000) }}" min="0" required>
          @error('frais_scolarite') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;
                    padding-top:16px; border-top:1px solid var(--border);">
          <a href="{{ route('classes.index') }}" class="btn-secondary">Annuler</a>
          <button type="submit" class="btn-primary">
            <i class="ti ti-check"></i> Créer la classe
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection