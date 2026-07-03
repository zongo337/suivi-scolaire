@extends('layouts.app')

@section('title', 'Classe ' . $classe->nom)
@section('page-title', 'Classe ' . $classe->nom)

@section('topbar-actions')
  <a href="{{ route('classes.edit', $classe) }}" class="btn-primary">
    <i class="ti ti-pencil"></i> Modifier
  </a>
  <a href="{{ route('classes.index') }}" class="btn-secondary">
    <i class="ti ti-arrow-left"></i> Retour
  </a>
@endsection

@section('content')

<!-- STATS CLASSE -->
<div class="stats-grid" style="grid-template-columns: repeat(4,1fr);">
  <div class="stat-card green">
    <div class="stat-value">{{ $classe->eleves->count() }}</div>
    <div class="stat-label"><i class="ti ti-users"></i> Élèves inscrits</div>
  </div>
  <div class="stat-card blue">
    <div class="stat-value">{{ $classe->effectif_max }}</div>
    <div class="stat-label"><i class="ti ti-armchair"></i> Places max</div>
  </div>
  <div class="stat-card orange">
    <div class="stat-value">{{ number_format($classe->total_paiements, 0, ',', ' ') }}</div>
    <div class="stat-label"><i class="ti ti-cash"></i> FCFA collectés</div>
  </div>
  <div class="stat-card red">
    <div class="stat-value">{{ number_format($classe->total_attendu - $classe->total_paiements, 0, ',', ' ') }}</div>
    <div class="stat-label"><i class="ti ti-alert-circle"></i> FCFA restants</div>
  </div>
</div>

<!-- CLASSEMENT -->
<div class="card">
  <div class="card-header">
    <span class="card-title">
      <i class="ti ti-trophy"></i> Classement — {{ $classe->nom }}
    </span>
    <a href="{{ route('eleves.create') }}" class="btn-outline">
      <i class="ti ti-plus"></i> Ajouter un élève
    </a>
  </div>
  <table>
    <thead>
      <tr>
        <th>Rang</th>
        <th>Élève</th>
        <th>Sexe</th>
        <th>Moyenne</th>
        <th>Situation paiement</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($eleves as $index => $eleve)
      <tr>
        <td>
          <span style="font-size:18px; font-weight:700;">
            @if($index === 0) 🥇
            @elseif($index === 1) 🥈
            @elseif($index === 2) 🥉
            @else
              <span style="font-weight:600; color:var(--text-secondary);">{{ $index + 1 }}</span>
            @endif
          </span>
        </td>
        <td>
          <div class="avatar-cell">
            @if($eleve->photo)
              <img src="{{ asset('storage/' . $eleve->photo) }}"
                   style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
            @else
              <div class="avatar-circle"
                   style="background:{{ $eleve->sexe === 'M' ? '#E6F1FB' : '#EEEDFE' }};
                          color:{{ $eleve->sexe === 'M' ? '#185FA5' : '#534AB7' }};">
                {{ strtoupper(substr($eleve->prenom, 0, 1) . substr($eleve->nom, 0, 1)) }}
              </div>
            @endif
            <div>
              <div class="avatar-name">{{ $eleve->prenom }} {{ $eleve->nom }}</div>
              <div class="avatar-sub">{{ $eleve->nom_parent }}</div>
            </div>
          </div>
        </td>
        <td>
          <span class="badge {{ $eleve->sexe === 'M' ? 'badge-info' : 'badge-gray' }}">
            {{ $eleve->sexe === 'M' ? 'Garçon' : 'Fille' }}
          </span>
        </td>
        <td>
          @if($eleve->moyenne_calculee > 0)
            <div style="display:flex; align-items:center; gap:8px;">
              <span style="font-weight:600; font-family:'DM Mono',monospace;
                           color:{{ $eleve->moyenne_calculee >= 10 ? 'var(--green-dark)' : 'var(--red)' }};">
                {{ number_format($eleve->moyenne_calculee, 2) }}/20
              </span>
              <div class="progress-bar">
                <div class="progress-fill"
                     style="width:{{ ($eleve->moyenne_calculee / 20) * 100 }}%;
                            background:{{ $eleve->moyenne_calculee >= 10 ? 'var(--green-mid)' : 'var(--red)' }};">
                </div>
              </div>
            </div>
          @else
            <span style="color:var(--text-muted); font-size:13px;">Pas de notes</span>
          @endif
        </td>
        <td>
          @if($eleve->reste_a_payer <= 0)
            <span class="badge badge-success"><i class="ti ti-check" style="font-size:11px;"></i> À jour</span>
          @elseif($eleve->total_paye > 0)
            <span class="badge badge-warning"><i class="ti ti-clock" style="font-size:11px;"></i> Partiel</span>
          @else
            <span class="badge badge-danger"><i class="ti ti-x" style="font-size:11px;"></i> Impayé</span>
          @endif
        </td>
        <td>
          <div style="display:flex; gap:6px;">
            <a href="{{ route('eleves.show', $eleve) }}" class="btn-outline">
              <i class="ti ti-eye"></i>
            </a>
            <a href="{{ route('eleves.edit', $eleve) }}" class="btn-outline">
              <i class="ti ti-pencil"></i>
            </a>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" style="text-align:center; color:var(--text-muted); padding:40px;">
          <i class="ti ti-users" style="font-size:32px; display:block; margin-bottom:8px;"></i>
          Aucun élève dans cette classe
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection