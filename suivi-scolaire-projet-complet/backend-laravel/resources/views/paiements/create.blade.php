@extends('layouts.app')

@section('title', 'Nouveau paiement')
@section('page-title', 'Enregistrer un paiement')

@section('topbar-actions')
  <a href="{{ route('paiements.index') }}" class="btn-secondary">
    <i class="ti ti-arrow-left"></i> Retour
  </a>
@endsection

@section('content')

<div style="max-width:600px;">
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="ti ti-cash"></i> Nouveau paiement</span>
    </div>
    <div style="padding:24px;">
      <form method="POST" action="{{ route('paiements.store') }}">
        @csrf

        <div class="form-group">
          <label class="form-label">Élève <span style="color:var(--red);">*</span></label>
          <select name="eleve_id" class="form-select" required id="eleveSelect">
            <option value="">Sélectionner un élève...</option>
            @foreach($eleves as $eleve)
              <option value="{{ $eleve->id }}"
                data-frais="{{ $eleve->classe->frais_scolarite }}"
                data-paye="{{ $eleve->total_paye }}"
                data-reste="{{ $eleve->reste_a_payer }}"
                data-classe="{{ $eleve->classe->nom }}"
                {{ (request('eleve_id') == $eleve->id || old('eleve_id') == $eleve->id) ? 'selected' : '' }}>
                {{ $eleve->prenom }} {{ $eleve->nom }} — {{ $eleve->classe->nom }}
              </option>
            @endforeach
          </select>
          @error('eleve_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <!-- INFO ÉLÈVE -->
        <div id="eleveInfo" style="display:none; background:var(--green-light);
             border-radius:8px; padding:14px 16px; margin-bottom:18px;">
          <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:6px;">
            <span style="color:var(--text-secondary);">Frais de scolarité</span>
            <span id="infoFrais" style="font-weight:600; font-family:'DM Mono',monospace;"></span>
          </div>
          <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:6px;">
            <span style="color:var(--text-secondary);">Déjà payé</span>
            <span id="infoPaye" style="font-weight:600; color:var(--green-dark); font-family:'DM Mono',monospace;"></span>
          </div>
          <div style="display:flex; justify-content:space-between; font-size:13px;">
            <span style="color:var(--text-secondary);">Reste à payer</span>
            <span id="infoReste" style="font-weight:600; color:var(--red); font-family:'DM Mono',monospace;"></span>
          </div>
        </div>

        <div class="form-grid-2">
          <div class="form-group">
            <label class="form-label">Montant versé (FCFA) <span style="color:var(--red);">*</span></label>
            <input type="number" name="montant" id="montantInput" class="form-control"
                   placeholder="Ex: 25000" value="{{ old('montant') }}" min="1" required>
            @error('montant') <div class="form-error">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label class="form-label">Date du paiement <span style="color:var(--red);">*</span></label>
            <input type="date" name="date_paiement" class="form-control"
                   value="{{ old('date_paiement', date('Y-m-d')) }}" required>
            @error('date_paiement') <div class="form-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Observation (optionnel)</label>
          <textarea name="observation" class="form-control" rows="3"
                    placeholder="Remarques sur ce paiement...">{{ old('observation') }}</textarea>
          @error('observation') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;
                    padding-top:16px; border-top:1px solid var(--border);">
          <a href="{{ route('paiements.index') }}" class="btn-secondary">Annuler</a>
          <button type="submit" class="btn-primary">
            <i class="ti ti-check"></i> Enregistrer le paiement
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  const select = document.getElementById('eleveSelect');
  const info   = document.getElementById('eleveInfo');

  function updateInfo() {
    const opt = select.options[select.selectedIndex];
    if (!opt.value) { info.style.display = 'none'; return; }
    document.getElementById('infoFrais').textContent =
      parseInt(opt.dataset.frais).toLocaleString('fr-FR') + ' FCFA';
    document.getElementById('infoPaye').textContent =
      parseInt(opt.dataset.paye).toLocaleString('fr-FR') + ' FCFA';
    document.getElementById('infoReste').textContent =
      parseInt(opt.dataset.reste).toLocaleString('fr-FR') + ' FCFA';
    document.getElementById('montantInput').max = opt.dataset.reste;
    info.style.display = 'block';
  }

  select.addEventListener('change', updateInfo);
  if (select.value) updateInfo();
</script>

@endsection