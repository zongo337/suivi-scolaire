@extends('layouts.app')

@section('title', 'Modifier parent')
@section('page-title', 'Modifier le compte parent')

@section('topbar-actions')
  <a href="{{ route('parents.index') }}" class="btn-secondary">
    <i class="ti ti-arrow-left"></i> Retour
  </a>
@endsection

@section('content')

<div style="max-width:640px;">
  <div class="card">
    <div class="card-header">
      <span class="card-title">
        <i class="ti ti-user-edit"></i> Informations du parent
      </span>
    </div>
    <div style="padding:24px;">
      <form method="POST" action="{{ route('parents.update', $parent) }}">
        @csrf @method('PUT')

        <div class="form-grid-2">
          <div class="form-group">
            <label class="form-label">Nom <span style="color:var(--red);">*</span></label>
            <input type="text" name="nom" class="form-control"
                   value="{{ old('nom', $parent->nom) }}" required>
            @error('nom') <div class="form-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Prénom <span style="color:var(--red);">*</span></label>
            <input type="text" name="prenom" class="form-control"
                   value="{{ old('prenom', $parent->prenom) }}" required>
            @error('prenom') <div class="form-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-grid-2">
          <div class="form-group">
            <label class="form-label">Email <span style="color:var(--red);">*</span></label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email', $parent->email) }}" required>
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-control"
                   value="{{ old('telephone', $parent->telephone) }}">
          </div>
        </div>

        <div class="form-grid-2">
          <div class="form-group">
            <label class="form-label">Nouveau mot de passe</label>
            <input type="password" name="password" class="form-control" placeholder="Laisser vide pour ne pas changer">
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Répéter le mot de passe">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">
            Enfant(s) associé(s) <span style="color:var(--red);">*</span>
          </label>
          @php $idsActuels = $parent->eleves->pluck('id')->toArray(); @endphp
          <div style="border:1px solid var(--border); border-radius:8px; max-height:240px; overflow-y:auto; padding:8px;">
            @foreach($eleves as $eleve)
              <label style="display:flex; align-items:center; gap:8px; padding:8px; border-radius:6px; cursor:pointer;">
                <input type="checkbox" name="eleves[]" value="{{ $eleve->id }}"
                  {{ in_array($eleve->id, old('eleves', $idsActuels)) ? 'checked' : '' }}>
                <span style="font-size:13.5px;">
                  {{ $eleve->prenom }} {{ $eleve->nom }}
                  <span class="badge badge-gray" style="margin-left:6px;">{{ $eleve->classe->nom ?? '—' }}</span>
                </span>
              </label>
            @endforeach
          </div>
          @error('eleves') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;
                    padding-top:16px; border-top:1px solid var(--border);">
          <a href="{{ route('parents.index') }}" class="btn-secondary">Annuler</a>
          <button type="submit" class="btn-primary">
            <i class="ti ti-check"></i> Enregistrer
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
