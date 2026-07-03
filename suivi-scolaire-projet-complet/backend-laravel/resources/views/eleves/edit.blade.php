@extends('layouts.app')

@section('title', 'Modifier l\'élève')
@section('page-title', 'Modifier l\'élève')

@section('topbar-actions')
  <a href="{{ route('eleves.index') }}" class="btn-secondary">
    <i class="ti ti-arrow-left"></i> Retour
  </a>
@endsection

@section('content')

<div style="max-width:760px;">
  <div class="card">
    <div class="card-header">
      <span class="card-title">
        <i class="ti ti-pencil"></i>
        Modifier — {{ $eleve->prenom }} {{ $eleve->nom }}
      </span>
    </div>

    <div style="padding:24px;">
      <form method="POST" action="{{ route('eleves.update', $eleve) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                     value="{{ old('nom', $eleve->nom) }}" required>
              @error('nom') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Prénom <span style="color:var(--red);">*</span></label>
              <input type="text" name="prenom" class="form-control"
                     value="{{ old('prenom', $eleve->prenom) }}" required>
              @error('prenom') <div class="form-error">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="form-grid-2">
            <div class="form-group">
              <label class="form-label">Date de naissance <span style="color:var(--red);">*</span></label>
              <input type="date" name="date_naissance" class="form-control"
                     value="{{ old('date_naissance', $eleve->date_naissance->format('Y-m-d')) }}" required>
              @error('date_naissance') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Sexe <span style="color:var(--red);">*</span></label>
              <select name="sexe" class="form-select" required>
                <option value="M" {{ old('sexe', $eleve->sexe) === 'M' ? 'selected' : '' }}>Masculin</option>
                <option value="F" {{ old('sexe', $eleve->sexe) === 'F' ? 'selected' : '' }}>Féminin</option>
              </select>
              @error('sexe') <div class="form-error">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Classe <span style="color:var(--red);">*</span></label>
            <select name="classe_id" class="form-select" required>
              @foreach($classes as $classe)
                <option value="{{ $classe->id }}"
                  {{ old('classe_id', $eleve->classe_id) == $classe->id ? 'selected' : '' }}>
                  {{ $classe->nom }} — {{ number_format($classe->frais_scolarite, 0, ',', ' ') }} FCFA
                </option>
              @endforeach
            </select>
            @error('classe_id') <div class="form-error">{{ $message }}</div> @enderror
          </div>

          <!-- PHOTO ACTUELLE -->
          <div class="form-group">
            <label class="form-label">Photo</label>
            @if($eleve->photo)
              <div style="margin-bottom:10px;">
                <img src="{{ asset('storage/' . $eleve->photo) }}"
                     style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:2px solid var(--border);">
                <div style="font-size:12px; color:var(--text-muted); margin-top:4px;">Photo actuelle</div>
              </div>
            @endif
            <input type="file" name="photo" class="form-control" accept="image/*">
            <div style="font-size:12px; color:var(--text-muted); margin-top:4px;">
              Laisser vide pour garder la photo actuelle
            </div>
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
                     value="{{ old('nom_parent', $eleve->nom_parent) }}" required>
              @error('nom_parent') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Téléphone <span style="color:var(--red);">*</span></label>
              <input type="text" name="telephone_parent" class="form-control"
                     value="{{ old('telephone_parent', $eleve->telephone_parent) }}" required>
              @error('telephone_parent') <div class="form-error">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <!-- BOUTONS -->
        <div style="display:flex; gap:12px; justify-content:flex-end; padding-top:16px; border-top:1px solid var(--border);">
          <a href="{{ route('eleves.index') }}" class="btn-secondary">Annuler</a>
          <button type="submit" class="btn-primary">
            <i class="ti ti-check"></i> Enregistrer les modifications
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

@endsection