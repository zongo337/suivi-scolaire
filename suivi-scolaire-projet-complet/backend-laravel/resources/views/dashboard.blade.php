@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('topbar-actions')
  <a href="{{ route('eleves.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Nouvel élève
  </a>
  <a href="{{ route('paiements.create') }}" class="btn-primary" style="background:#0F6E56;">
    <i class="ti ti-cash"></i> Nouveau paiement
  </a>
@endsection

@section('content')

<!-- STATS -->
<div class="stats-grid">
  <div class="stat-card green">
    <div class="stat-value">{{ $totalEleves }}</div>
    <div class="stat-label"><i class="ti ti-users"></i> Élèves inscrits</div>
  </div>
  <div class="stat-card blue">
    <div class="stat-value">{{ $totalClasses }}</div>
    <div class="stat-label"><i class="ti ti-school"></i> Classes actives</div>
  </div>
  <div class="stat-card orange">
    <div class="stat-value">{{ number_format($totalPaiements, 0, ',', ' ') }}</div>
    <div class="stat-label"><i class="ti ti-cash"></i> FCFA collectés</div>
  </div>
  <div class="stat-card red">
    <div class="stat-value">{{ $impayes->count() }}</div>
    <div class="stat-label"><i class="ti ti-alert-circle"></i> Élèves impayés</div>
  </div>
</div>

<!-- DEUX COLONNES -->
<div class="two-col">

  <!-- DERNIERS PAIEMENTS -->
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="ti ti-clock"></i> Derniers paiements</span>
      <a href="{{ route('paiements.index') }}" class="btn-outline">Tout voir</a>
    </div>
    <table>
      <thead>
        <tr>
          <th>Élève</th>
          <th>Date</th>
          <th>Montant</th>
          <th>Statut</th>
        </tr>
      </thead>
      <tbody>
        @forelse($derniersPaiements as $paiement)
        <tr>
          <td>
            <div class="avatar-cell">
              <div class="avatar-circle" style="background:#E1F5EE; color:#0F6E56;">
                {{ strtoupper(substr($paiement->eleve->prenom, 0, 1) . substr($paiement->eleve->nom, 0, 1)) }}
              </div>
              <div>
                <div class="avatar-name">{{ $paiement->eleve->prenom }} {{ $paiement->eleve->nom }}</div>
                <div class="avatar-sub">{{ $paiement->eleve->classe->nom }}</div>
              </div>
            </div>
          </td>
          <td style="color:var(--text-secondary);">{{ $paiement->date_paiement->format('d/m/Y') }}</td>
          <td style="font-family:'DM Mono',monospace; font-size:13px;">
            {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
          </td>
          <td><span class="badge badge-success"><i class="ti ti-check" style="font-size:11px;"></i> Payé</span></td>
        </tr>
        @empty
        <tr>
          <td colspan="4" style="text-align:center; color:var(--text-muted); padding:24px;">
            Aucun paiement enregistré
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- MEILLEURES MOYENNES -->
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="ti ti-trophy"></i> Meilleures moyennes</span>
      <a href="{{ route('notes.moyennes') }}" class="btn-outline">Détail</a>
    </div>
    <table>
      <thead>
        <tr>
          <th>Élève</th>
          <th>Classe</th>
          <th>Moyenne</th>
          <th>Rang</th>
        </tr>
      </thead>
      <tbody>
        @forelse($meilleuresMoyennes as $i => $eleve)
        <tr>
          <td>
            <div class="avatar-cell">
              <div class="avatar-circle" style="background:#E1F5EE; color:#0F6E56;">
                {{ strtoupper(substr($eleve->prenom, 0, 1) . substr($eleve->nom, 0, 1)) }}
              </div>
              <div class="avatar-name">{{ $eleve->prenom }} {{ $eleve->nom }}</div>
            </div>
          </td>
          <td><span class="badge badge-info">{{ $eleve->classe->nom }}</span></td>
          <td>
            <div style="display:flex; align-items:center; gap:8px;">
              <span style="font-weight:600; color:var(--green-dark); font-family:'DM Mono',monospace;">
                {{ number_format($eleve->moyenne, 2) }}
              </span>
              <div class="progress-bar">
                <div class="progress-fill" style="width:{{ ($eleve->moyenne / 10) * 100 }}%;"></div>
              </div>
            </div>
          </td>
          <td>
            @if($i === 0) 🥇
            @elseif($i === 1) 🥈
            @elseif($i === 2) 🥉
            @else {{ $i + 1 }}e
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" style="text-align:center; color:var(--text-muted); padding:24px;">
            Aucune note enregistrée
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- RECOUVREMENT + IMPAYES -->
<div class="two-col">

  <!-- RECOUVREMENT -->
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="ti ti-chart-pie"></i> Recouvrement financier</span>
    </div>
    <div style="padding:20px; display:flex; flex-direction:column; gap:14px;">
      <div>
        <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:6px;">
          <span style="color:var(--text-secondary);">Frais collectés</span>
          <span style="font-weight:600; color:var(--green-dark);">
            {{ number_format($totalPaiements, 0, ',', ' ') }} FCFA
          </span>
        </div>
        <div class="progress-bar" style="width:100%; height:8px;">
          <div class="progress-fill" style="width:{{ $totalAttendu > 0 ? ($totalPaiements / $totalAttendu) * 100 : 0 }}%;"></div>
        </div>
      </div>
      <div>
        <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:6px;">
          <span style="color:var(--text-secondary);">Total attendu</span>
          <span style="font-weight:600;">{{ number_format($totalAttendu, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="progress-bar" style="width:100%; height:8px;">
          <div class="progress-fill" style="width:100%; background:var(--border);"></div>
        </div>
      </div>
      <div style="background:var(--orange-light); border-radius:8px; padding:12px 16px;">
        <div style="font-size:12px; color:var(--orange);">Reste à collecter</div>
        <div style="font-size:20px; font-weight:600; color:var(--orange); font-family:'DM Mono',monospace;">
          {{ number_format($resteACollecter, 0, ',', ' ') }} FCFA
        </div>
      </div>
    </div>
  </div>

  <!-- IMPAYES -->
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="ti ti-alert-triangle"></i> Élèves impayés</span>
      <a href="{{ route('paiements.impayes') }}" class="btn-outline">Voir tous</a>
    </div>
    <table>
      <thead>
        <tr>
          <th>Élève</th>
          <th>Classe</th>
          <th>Reste à payer</th>
        </tr>
      </thead>
      <tbody>
        @forelse($impayes as $eleve)
        <tr>
          <td>
            <div class="avatar-cell">
              <div class="avatar-circle" style="background:#FCEBEB; color:#A32D2D;">
                {{ strtoupper(substr($eleve->prenom, 0, 1) . substr($eleve->nom, 0, 1)) }}
              </div>
              <div class="avatar-name">{{ $eleve->prenom }} {{ $eleve->nom }}</div>
            </div>
          </td>
          <td><span class="badge badge-gray">{{ $eleve->classe->nom }}</span></td>
          <td style="font-family:'DM Mono',monospace; color:var(--red); font-weight:600;">
            −{{ number_format($eleve->reste_a_payer, 0, ',', ' ') }} FCFA
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="3" style="text-align:center; color:var(--text-muted); padding:24px;">
            ✅ Tous les élèves sont à jour
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection