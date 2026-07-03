@extends('layouts.app')

@section('title', 'Moyennes')

@section('page-title')
  Moyennes — {{ $classe?->nom ?? 'Aucune classe' }}
@endsection

@section('content')

@if(!$classe)
  <div class="card">
    <div style="text-align:center; padding:60px; color:var(--text-muted);">
      <i class="ti ti-alert-circle" style="font-size:48px; display:block;
         margin-bottom:12px; color:var(--orange);"></i>
      <div style="font-size:16px; font-weight:500; margin-bottom:6px;">
        Aucune classe assignée
      </div>
      <div style="font-size:13px;">
        Contactez l'administrateur pour être assigné à une classe.
      </div>
    </div>
  </div>

@else

  <!-- STATS DE LA CLASSE -->
  <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:20px;">
    <div class="stat-card blue">
      <div class="stat-value">{{ $data['eleves']->count() }}</div>
      <div class="stat-label"><i class="ti ti-users"></i> Élèves</div>
    </div>
    <div class="stat-card orange">
      <div class="stat-value">{{ number_format($data['moyenne_classe'], 2) }}/10</div>
      <div class="stat-label"><i class="ti ti-chart-bar"></i> Moyenne de classe</div>
    </div>
    <div class="stat-card green">
      <div class="stat-value">{{ $data['admis'] }}</div>
      <div class="stat-label"><i class="ti ti-check"></i> Admis</div>
    </div>
    <div class="stat-card red">
      <div class="stat-value">{{ $data['redoublants'] }}</div>
      <div class="stat-label"><i class="ti ti-x"></i> Redoublants</div>
    </div>
  </div>

  <!-- TABLEAU CLASSEMENT -->
  <div class="card">
    <div class="card-header">
      <span class="card-title">
        <i class="ti ti-trophy"></i>
        Classement — {{ $classe->nom }} | Annuel
      </span>
      <span style="font-size:13px; font-weight:600; color:var(--green-dark);">
        Moyenne de classe : {{ number_format($data['moyenne_classe'], 2) }}/10
      </span>
    </div>
    <table>
      <thead>
        <tr>
          <th>Rang</th>
          <th>Élève</th>
          <th style="text-align:center;">Moy. T1</th>
          <th style="text-align:center;">Moy. T2</th>
          <th style="text-align:center;">Moy. T3</th>
          <th style="text-align:center;">Moy. Annuelle</th>
          <th>Appréciation</th>
          <th>Décision</th>
        </tr>
      </thead>
      <tbody>
        @forelse($data['eleves'] as $eleve)
        <tr style="{{ $eleve->decision === 'redouble' ? 'background:#fff8f8;' : '' }}">
          <td>
            @if($eleve->rang === 1) 🥇
            @elseif($eleve->rang === 2) 🥈
            @elseif($eleve->rang === 3) 🥉
            @else
              <span style="font-weight:600; color:var(--text-secondary);">
                {{ $eleve->rang }}e
              </span>
            @endif
          </td>
          <td>
            <div class="avatar-cell">
              <div class="avatar-circle"
                   style="background:{{ $eleve->sexe === 'M' ? '#E6F1FB' : '#EEEDFE' }};
                          color:{{ $eleve->sexe === 'M' ? '#185FA5' : '#534AB7' }};">
                {{ strtoupper(substr($eleve->prenom, 0, 1) . substr($eleve->nom, 0, 1)) }}
              </div>
              <div>
                <div class="avatar-name">{{ $eleve->prenom }} {{ $eleve->nom }}</div>
                <div class="avatar-sub">{{ $eleve->sexe === 'M' ? 'Garçon' : 'Fille' }}</div>
              </div>
            </div>
          </td>

          {{-- Trimestre 1 --}}
          <td style="text-align:center;">
            @if($eleve->moy_t1 !== null)
              <span style="font-weight:600; font-family:'DM Mono',monospace;
                           color:{{ $eleve->moy_t1 >= 5 ? 'var(--green-dark)' : 'var(--red)' }};">
                {{ number_format($eleve->moy_t1, 2) }}/10
              </span>
            @else
              <span style="color:var(--text-muted);">—</span>
            @endif
          </td>

          {{-- Trimestre 2 --}}
          <td style="text-align:center;">
            @if($eleve->moy_t2 !== null)
              <span style="font-weight:600; font-family:'DM Mono',monospace;
                           color:{{ $eleve->moy_t2 >= 5 ? 'var(--green-dark)' : 'var(--red)' }};">
                {{ number_format($eleve->moy_t2, 2) }}/10
              </span>
            @else
              <span style="color:var(--text-muted);">—</span>
            @endif
          </td>

          {{-- Trimestre 3 --}}
          <td style="text-align:center;">
            @if($eleve->moy_t3 !== null)
              <span style="font-weight:600; font-family:'DM Mono',monospace;
                           color:{{ $eleve->moy_t3 >= 5 ? 'var(--green-dark)' : 'var(--red)' }};">
                {{ number_format($eleve->moy_t3, 2) }}/10
              </span>
            @else
              <span style="color:var(--text-muted);">—</span>
            @endif
          </td>

          {{-- Moyenne annuelle --}}
          <td style="text-align:center;">
            <div style="display:flex; align-items:center; justify-content:center; gap:8px;">
              <span style="font-size:16px; font-weight:700;
                           font-family:'DM Mono',monospace;
                           color:{{ $eleve->moyenne_annuelle >= 5 ? 'var(--green-dark)' : 'var(--red)' }};">
                {{ number_format($eleve->moyenne_annuelle, 2) }}/10
              </span>
              <div class="progress-bar" style="width:60px;">
                <div class="progress-fill"
                     style="width:{{ ($eleve->moyenne_annuelle / 10) * 100 }}%;
                            background:{{ $eleve->moyenne_annuelle >= 5 ? 'var(--green-mid)' : 'var(--red)' }};">
                </div>
              </div>
            </div>
          </td>

          {{-- Appréciation --}}
          <td>
            @php $moy = $eleve->moyenne_annuelle; @endphp
            @if($moy >= 8) <span class="badge badge-success">Très bien</span>
            @elseif($moy >= 7) <span class="badge badge-success">Bien</span>
            @elseif($moy >= 6) <span class="badge badge-info">Assez bien</span>
            @elseif($moy >= 5) <span class="badge badge-warning">Passable</span>
            @else <span class="badge badge-danger">Insuffisant</span>
            @endif
          </td>

          {{-- Décision --}}
          <td>
            @if($eleve->decision === 'admis')
              <span class="badge badge-success">✅ Admis</span>
            @else
              <span class="badge badge-danger">❌ Redouble</span>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" style="text-align:center; color:var(--text-muted); padding:40px;">
            <i class="ti ti-pencil" style="font-size:32px; display:block; margin-bottom:8px;"></i>
            Aucune note enregistrée pour cette classe
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

@endif

@endsection