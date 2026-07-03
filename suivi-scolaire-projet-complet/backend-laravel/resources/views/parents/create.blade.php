@extends('layouts.app')

@section('title', 'Nouveau parent')
@section('page-title', 'Créer un compte parent')

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
        <i class="ti ti-user-plus"></i> Informations du parent
      </span>
    </div>
    <div style="padding:24px;">
      <form method="POST" action="{{ route('parents.store') }}">
        @csrf

        <div class="form-grid-2">
          <div class="form-group">
            <label class="form-label">Nom <span style="color:var(--red);">*</span></label>
            <input type="text" name="nom" class="form-control" placeholder="Ex: SAWADOGO"
                   value="{{ old('nom') }}" required>
            @error('nom') <div class="form-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Prénom <span style="color:var(--red);">*</span></label>
            <input type="text" name="prenom" class="form-control" placeholder="Ex: Moussa"
                   value="{{ old('prenom') }}" required>
            @error('prenom') <div class="form-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-grid-2">
          <div class="form-group">
            <label class="form-label">Email <span style="color:var(--red);">*</span></label>
            <input type="email" name="email" class="form-control" placeholder="Ex: parent@mail.com"
                   value="{{ old('email') }}" required>
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-control" placeholder="Ex: 70123456"
                   value="{{ old('telephone') }}">
            @error('telephone') <div class="form-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-grid-2">
          <div class="form-group">
            <label class="form-label">Mot de passe <span style="color:var(--red);">*</span></label>
            <input type="password" name="password" class="form-control" placeholder="Min. 6 caractères" required>
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Confirmer le mot de passe <span style="color:var(--red);">*</span></label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Répéter le mot de passe" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">
            Enfant(s) à associer <span style="color:var(--red);">*</span>
          </label>
          <div style="border:1px solid var(--border); border-radius:8px; max-height:240px; overflow-y:auto; padding:8px;">
            @forelse($eleves as $eleve)
              <label style="display:flex; align-items:center; gap:8px; padding:8px; border-radius:6px; cursor:pointer;">
                <input type="checkbox" name="eleves[]" value="{{ $eleve->id }}"
                  {{ in_array($eleve->id, old('eleves', [])) ? 'checked' : '' }}>
                <span style="font-size:13.5px;">
                  {{ $eleve->prenom }} {{ $eleve->nom }}
                  <span class="badge badge-gray" style="margin-left:6px;">{{ $eleve->classe->nom ?? '—' }}</span>
                </span>
              </label>
            @empty
              <p style="color:var(--text-muted); padding:8px;">Aucun élève enregistré pour le moment.</p>
            @endforelse
          </div>
          @error('eleves') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div style="background:var(--blue-light); border-radius:8px; padding:12px 16px;
                    margin-bottom:18px; font-size:13px; color:var(--blue);
                    display:flex; align-items:center; gap:8px;">
          <i class="ti ti-info-circle" style="font-size:16px;"></i>
          Le parent pourra se connecter à l'application mobile avec cet email et ce mot de passe.
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;
                    padding-top:16px; border-top:1px solid var(--border);">
          <a href="{{ route('parents.index') }}" class="btn-secondary">Annuler</a>
          <button type="submit" class="btn-primary">
            <i class="ti ti-check"></i> Créer le compte
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
