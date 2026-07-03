@extends('layouts.app')
@section('title', 'Paiements')
@section('page-title', 'Paiements')

@section('topbar-actions')
  <a href="{{ route('paiements.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Nouveau paiement
  </a>
@endsection

@section('content')

<!-- FILTRE -->
<div class="card" style="margin-bottom:20px;">
  <div style="padding:16px 20px;">
    <form method="GET" action="{{ route('paiements.index') }}"
          style="display:flex; gap:12px; align-items:flex-end;">
      <div style="flex:2;">
        <label class="form-label">Rechercher un élève</label>
        <input type="text" name="search" class="form-control"
               placeholder="Nom ou prénom..." value="{{ request('search') }}">
      </div>
      <div style="flex:1;">
        <label class="form-label">Filtrer par classe</label>
        <select name="classe_id" class="form-select">
          <option value="">Toutes les classes</option>
          @foreach($classes as $classe)
            <option value="{{ $classe->id }}"
              {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
              {{ $classe->nom }}
            </option>
          @endforeach
        </select>
      </div>
      <div>
        <button type="submit" class="btn-primary">
          <i class="ti ti-search"></i> Filtrer
        </button>
        <a href="{{ route('paiements.index') }}" class="btn-secondary" style="margin-left:8px;">
          <i class="ti ti-x"></i> Reset
        </a>
      </div>
    </form>
  </div>
</div>

<!-- PAIEMENTS GROUPÉS PAR CLASSE -->
@forelse($paiementsParClasse as $nomClasse => $paiements)
<div class="card" style="margin-bottom:20px;">
  <div class="card-header">
    <span class="card-title">
      <i class="ti ti-school"></i> Classe {{ $nomClasse }}
      <span style="font-size:13px; font-weight:400; color:var(--text-muted);">
        ({{ $paiements->count() }} paiements)
      </span>
    </span>
    <span style="font-size:13px; font-weight:600; color:var(--green-dark);">
      Total : {{ number_format($paiements->sum('montant'), 0, ',', ' ') }} FCFA
    </span>
  </div>
  <table>
    <thead>
      <tr>
        <th>Élève</th>
        <th>Date</th>
        <th>Montant</th>
        <th>Référence</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($paiements as $paiement)
      <tr>
        <td>
          <div class="avatar-cell">
            <div class="avatar-circle" style="background:var(--green-light); color:var(--green-dark);">
              {{ strtoupper(substr($paiement->eleve->prenom, 0, 1) . substr($paiement->eleve->nom, 0, 1)) }}
            </div>
            <div>
              <div class="avatar-name">{{ $paiement->eleve->prenom }} {{ $paiement->eleve->nom }}</div>
              <div class="avatar-sub">{{ $paiement->eleve->nom_parent }}</div>
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);">
          {{ $paiement->date_paiement->format('d/m/Y') }}
        </td>
        <td style="font-family:'DM Mono',monospace; font-size:13px;
                   font-weight:600; color:var(--green-dark);">
          {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
        </td>
        <td style="font-family:'DM Mono',monospace; font-size:12px; color:var(--text-muted);">
          {{ $paiement->reference }}
        </td>
        <td>
          <div style="display:flex; gap:6px;">
            <a href="{{ route('paiements.recu', $paiement) }}" class="btn-outline" target="_blank">
              <i class="ti ti-file-text"></i> Reçu
            </a>
            <form method="POST" action="{{ route('paiements.destroy', $paiement) }}"
                  onsubmit="return confirm('Supprimer ce paiement ?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger">
                <i class="ti ti-trash"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@empty
<div class="card">
  <div style="text-align:center; padding:40px; color:var(--text-muted);">
    <i class="ti ti-receipt" style="font-size:32px; display:block; margin-bottom:8px;"></i>
    Aucun paiement enregistré
  </div>
</div>
@endforelse

@endsection