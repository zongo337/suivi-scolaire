@extends('layouts.app')

@section('title', 'Nouvelle école')
@section('page-title', 'Ajouter une école')

@section('topbar-actions')
  <a href="{{ route('ecoles.index') }}" class="btn-secondary">
    <i class="ti ti-arrow-left"></i> Retour
  </a>
@endsection

@section('content')

<div style="max-width:600px;">
  <div class="card">
    <div class="card-header">
      <span class="card-title">
        <i class="ti ti-building"></i> Informations de l'établissement
      </span>
    </div>
    <div style="padding:24px;">
      <form method="POST" action="{{ route('ecoles.store') }}">
        @csrf

        <div class="form-group">
          <label class="form-label">
            Nom de l'école <span style="color:var(--red);">*</span>
          </label>
          <input type="text" name="nom" class="form-control"
                 placeholder="Ex: École Primaire Publique de la Paix"
                 value="{{ old('nom') }}" required>
          @error('nom') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Adresse</label>
          <input type="text" name="adresse" class="form-control"
                 placeholder="Ex: Secteur 12, Ouagadougou, Burkina Faso"
                 value="{{ old('adresse') }}">
          @error('adresse') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-grid-2">
          <div class="form-group">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-control"
                   placeholder="Ex: 70 00 00 00"
                   value="{{ old('telephone') }}">
            @error('telephone') <div class="form-error">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   placeholder="Ex: ecole@example.bf"
                   value="{{ old('email') }}">
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Nom du directeur</label>
          <input type="text" name="directeur" class="form-control"
                 placeholder="Ex: M. KABORÉ Issa"
                 value="{{ old('directeur') }}">
          @error('directeur') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <!-- INFO -->
        <div style="background:var(--blue-light); border-radius:8px; padding:12px 16px;
                    margin-bottom:18px; font-size:13px; color:var(--blue);
                    display:flex; align-items:center; gap:8px;">
          <i class="ti ti-info-circle" style="font-size:16px;"></i>
          Après l'ajout, activez l'école depuis la liste pour qu'elle apparaisse sur les reçus.
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;
                    padding-top:16px; border-top:1px solid var(--border);">
          <a href="{{ route('ecoles.index') }}" class="btn-secondary">Annuler</a>
          <button type="submit" class="btn-primary">
            <i class="ti ti-check"></i> Enregistrer l'école
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection