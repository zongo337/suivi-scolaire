@extends('layouts.app')

@section('title', 'Inscrire un élève')
@section('page-title', 'Inscrire un élève')

@section('topbar-actions')
  <a href="{{ route('eleves.index') }}" class="btn-secondary">
    <i class="ti ti-arrow-left"></i> Retour
  </a>
@endsection

@section('content')

<div style="max-width:760px;">
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="ti ti-user-plus"></i> Informations de l'élève</span>
    </div>

    <div style="padding:24px;">
      <form method="POST" action="{{ route('eleves.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- INFOS PERSONNELLES -->
        <div style="margin-bottom:24px;">
          <div style="font-size:13px; font-weight:600; color:var(--green-dark);
                      text-transform:uppercase; letter-spacing:0.8px; margin-bottom:16px;
                      padding-bottom:8px; border-bottom:1px solid var(--border);">
            Informations personnelles
          </div>

          <div class="form-grid-2">
            <div class="form-group">
              <label class="form-label">Nom <span style="color:var(--red);">*</span></label>
              <input type="text" name="nom" class="form-control"
                     placeholder="Ex: KABORÉ" value="{{ old('nom') }}" required>
              @error('nom') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Prénom <span style="color:var(--red);">*</span></label>
              <input type="text" name="prenom" class="form-control"
                     placeholder="Ex: Aminata" value="{{ old('prenom') }}" required>
              @error('prenom') <div class="form-error">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="form-grid-2">
            <div class="form-group">
              <label class="form-label">Date de naissance <span style="color:var(--red);">*</span></label>
              <input type="date" name="date_naissance" class="form-control"
                     value="{{ old('date_naissance') }}" required>
              @error('date_naissance') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Sexe <span style="color:var(--red);">*</span></label>
              <select name="sexe" class="form-select" required>
                <option value="">Choisir...</option>
                <option value="M" {{ old('sexe') === 'M' ? 'selected' : '' }}>Masculin</option>
                <option value="F" {{ old('sexe') === 'F' ? 'selected' : '' }}>Féminin</option>
              </select>
              @error('sexe') <div class="form-error">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Classe <span style="color:var(--red);">*</span></label>
            <select name="classe_id" class="form-select" required>
              <option value="">Sélectionner une classe...</option>
              @foreach($classes as $classe)
                <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                  {{ $classe->nom }} — Frais : {{ number_format($classe->frais_scolarite, 0, ',', ' ') }} FCFA
                </option>
              @endforeach
            </select>
            @error('classe_id') <div class="form-error">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label class="form-label">Photo de l'élève</label>
            <input type="file" name="photo" class="form-control" accept="image/*">
            @error('photo') <div class="form-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <!-- INFOS PARENT -->
        <div style="margin-bottom:24px;">
          <div style="font-size:13px; font-weight:600; color:var(--green-dark);
                      text-transform:uppercase; letter-spacing:0.8px; margin-bottom:16px;
                      padding-bottom:8px; border-bottom:1px solid var(--border);">
            Informations du parent / tuteur
          </div>

          <div class="form-grid-2">
            <div class="form-group">
              <label class="form-label">Nom du parent <span style="color:var(--red);">*</span></label>
              <input type="text" name="nom_parent" class="form-control"
                     placeholder="Ex: KABORÉ Issa" value="{{ old('nom_parent') }}" required>
              @error('nom_parent') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Téléphone <span style="color:var(--red);">*</span></label>
              <input type="text" name="telephone_parent" class="form-control"
                     placeholder="Ex: 70 00 00 00" value="{{ old('telephone_parent') }}" required>
              @error('telephone_parent') <div class="form-error">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <!-- BOUTONS -->
        <div style="display:flex; gap:12px; justify-content:flex-end; padding-top:16px; border-top:1px solid var(--border);">
          <a href="{{ route('eleves.index') }}" class="btn-secondary">Annuler</a>
          <button type="submit" class="btn-primary">
            <i class="ti ti-check"></i> Inscrire l'élève
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

@endsection