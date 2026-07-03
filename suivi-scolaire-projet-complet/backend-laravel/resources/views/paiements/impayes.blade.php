@extends('layouts.app')

@section('title', 'Élèves impayés')
@section('page-title', 'Élèves en retard de paiement')

@section('topbar-actions')
  <a href="{{ route('paiements.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Enregistrer un paiement
  </a>
@endsection

@section('content')

<!-- FILTRE -->
<div class="card" style="margin-bottom:20px;">
  <div style="padding:16px 20px;">
    <form method="GET" action="{{ route('paiements.impayes') }}"
          style="display:flex; gap:12px; align-items:flex-end;">
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
        <a href="{{ route('paiements.impayes') }}" class="btn-secondary" style="margin-left:8px;">
          <i class="ti ti-x"></i> Reset
        </a>
      </div>
    </form>
  </div>
</div>

<!-- IMPAYÉS GROUPÉS PAR CLASSE -->
@php
  $elevesParClasse = $eleves->groupBy(fn($e) => $e->classe->nom);
@endphp

@forelse($elevesParClasse as $nomClasse => $elevesClasse)
<div class="card" style="margin-bottom:20px;">
  <div class="card-header">
    <span class="card-title">
      <i class="ti ti-alert-triangle"></i> Classe {{ $nomClasse }}
      <span style="font-size:13px; font-weight:400; color:var(--text-muted);">
        ({{ $elevesClasse->count() }} élèves impayés)
      </span>
    </span>
    <span style="font-size:13px; font-weight:600; color:var(--red);">
      Total : −{{ number_format($elevesClasse->sum('reste_a_payer'), 0, ',', ' ') }} FCFA
    </span>
  </div>
  <table>
    <thead>
      <tr>
        <th>Élève</th>
        <th>Parent / Tuteur</th>
        <th>Téléphone</th>
        <th>Frais totaux</th>
        <th>Déjà payé</th>
        <th>Reste à payer</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($elevesClasse as $eleve)
      <tr>
        <td>
          <div class="avatar-cell">
            <div class="avatar-circle" style="background:var(--red-light); color:var(--red);">
              {{ strtoupper(substr($eleve->prenom, 0, 1) . substr($eleve->nom, 0, 1)) }}
            </div>
            <div>
              <div class="avatar-name">{{ $eleve->prenom }} {{ $eleve->nom }}</div>
              <div class="avatar-sub">{{ $eleve->sexe === 'M' ? 'Garçon' : 'Fille' }}</div>
            </div>
          </div>
        </td>
        <td>{{ $eleve->nom_parent }}</td>
        <td style="font-family:'DM Mono',monospace; font-size:13px;">
          {{ $eleve->telephone_parent }}
        </td>
        <td style="font-family:'DM Mono',monospace; font-size:13px;">
          {{ number_format($eleve->classe->frais_scolarite, 0, ',', ' ') }} FCFA
        </td>
        <td style="font-family:'DM Mono',monospace; font-size:13px; color:var(--green-dark); font-weight:600;">
          {{ number_format($eleve->total_paye, 0, ',', ' ') }} FCFA
        </td>
        <td style="font-family:'DM Mono',monospace; font-size:13px; color:var(--red); font-weight:600;">
          −{{ number_format($eleve->reste_a_payer, 0, ',', ' ') }} FCFA
        </td>
        <td>
          <div style="display:flex; gap:6px;">
            <a href="{{ route('eleves.show', $eleve) }}" class="btn-outline">
              <i class="ti ti-eye"></i>
            </a>
            <a href="{{ route('paiements.create') }}?eleve_id={{ $eleve->id }}"
               class="btn-primary" style="padding:6px 10px; font-size:12px;">
              <i class="ti ti-cash"></i> Payer
            </a>
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
    <i class="ti ti-check" style="font-size:32px; display:block; margin-bottom:8px; color:var(--green-mid);"></i>
    ✅ Tous les élèves sont à jour
  </div>
</div>
@endforelse

@endsection