@extends('layouts.app')

@section('title', 'Moyennes & Classement')
@section('page-title', 'Moyennes & Classement')

@section('content')

<!-- FILTRES -->
<div class="card" style="margin-bottom:20px;">
  <div style="padding:16px 20px;">
    <form method="GET" action="{{ route('notes.moyennes') }}"
          style="display:flex; gap:12px; align-items:flex-end;">
      <div style="flex:1;">
        <label class="form-label">Classe</label>
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
      <div style="flex:1;">
        <label class="form-label">Trimestre</label>
        <select name="trimestre" class="form-select">
          <option value="">Tous les trimestres (Annuel)</option>
          <option value="1" {{ request('trimestre') == '1' ? 'selected' : '' }}>Trimestre 1</option>
          <option value="2" {{ request('trimestre') == '2' ? 'selected' : '' }}>Trimestre 2</option>
          <option value="3" {{ request('trimestre') == '3' ? 'selected' : '' }}>Trimestre 3</option>
        </select>
      </div>
      <div>
        <button type="submit" class="btn-primary">
          <i class="ti ti-trophy"></i> Afficher
        </button>
        <a href="{{ route('notes.moyennes') }}" class="btn-secondary" style="margin-left:8px;">
          <i class="ti ti-x"></i> Reset
        </a>
      </div>
    </form>
  </div>
</div>

@php $trimestreChoisi = request('trimestre'); @endphp

<!-- CLASSEMENT PAR CLASSE -->
@forelse($resultats as $nomClasse => $data)

<!-- STATS -->
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:12px;">
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

<!-- TABLEAU -->
<div class="card" style="margin-bottom:24px;">
  <div class="card-header">
    <span class="card-title">
      <i class="ti ti-trophy"></i> Classement — {{ $nomClasse }}
      @if($trimestreChoisi)
        | Trimestre {{ $trimestreChoisi }}
      @else
        | Annuel
      @endif
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
        @if(!$trimestreChoisi)
          {{-- Tous les trimestres : affiche T1 T2 T3 + Annuelle --}}
          <th style="text-align:center;">Moy. T1</th>
          <th style="text-align:center;">Moy. T2</th>
          <th style="text-align:center;">Moy. T3</th>
          <th style="text-align:center;">Moy. Annuelle</th>
          <th>Appréciation</th>
          <th>Décision</th>
        @else
          {{-- Un trimestre : affiche seulement ce trimestre --}}
          <th style="text-align:center;">Moyenne T{{ $trimestreChoisi }}</th>
          <th>Appréciation</th>
        @endif
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data['eleves'] as $eleve)
      <tr style="{{ !$trimestreChoisi && $eleve->decision === 'redouble' ? 'background:#fff8f8;' : '' }}">
        <td>
          @if($eleve->rang === 1) 🥇
          @elseif($eleve->rang === 2) 🥈
          @elseif($eleve->rang === 3) 🥉
          @else
            <span style="font-weight:600; color:var(--text-secondary);">{{ $eleve->rang }}e</span>
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

        @if(!$trimestreChoisi)
          {{-- TOUS LES TRIMESTRES --}}

          {{-- T1 --}}
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

          {{-- T2 --}}
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

          {{-- T3 --}}
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

          {{-- ANNUELLE --}}
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

          {{-- APPRECIATION --}}
          <td>
            @php $moy = $eleve->moyenne_annuelle; @endphp
            @if($moy >= 8) <span class="badge badge-success">Très bien</span>
            @elseif($moy >= 7) <span class="badge badge-success">Bien</span>
            @elseif($moy >= 6) <span class="badge badge-info">Assez bien</span>
            @elseif($moy >= 5) <span class="badge badge-warning">Passable</span>
            @else <span class="badge badge-danger">Insuffisant</span>
            @endif
          </td>

          {{-- DECISION --}}
          <td>
            @if($eleve->decision === 'admis')
              <span class="badge badge-success">✅ Admis</span>
            @else
              <span class="badge badge-danger">❌ Redouble</span>
            @endif
          </td>

        @else
          {{-- UN SEUL TRIMESTRE --}}
          @php
            $moy_t = $trimestreChoisi == '1' ? $eleve->moy_t1 :
                    ($trimestreChoisi == '2' ? $eleve->moy_t2 : $eleve->moy_t3);
          @endphp

          <td style="text-align:center;">
            @if($moy_t !== null)
              <div style="display:flex; align-items:center; justify-content:center; gap:8px;">
                <span style="font-size:16px; font-weight:700;
                             font-family:'DM Mono',monospace;
                             color:{{ $moy_t >= 5 ? 'var(--green-dark)' : 'var(--red)' }};">
                  {{ number_format($moy_t, 2) }}/10
                </span>
                <div class="progress-bar" style="width:60px;">
                  <div class="progress-fill"
                       style="width:{{ ($moy_t / 10) * 100 }}%;
                              background:{{ $moy_t >= 5 ? 'var(--green-mid)' : 'var(--red)' }};">
                  </div>
                </div>
              </div>
            @else
              <span style="color:var(--text-muted);">—</span>
            @endif
          </td>

          {{-- APPRECIATION --}}
          <td>
            @php $moy = $moy_t ?? 0; @endphp
            @if($moy >= 8) <span class="badge badge-success">Très bien</span>
            @elseif($moy >= 7) <span class="badge badge-success">Bien</span>
            @elseif($moy >= 6) <span class="badge badge-info">Assez bien</span>
            @elseif($moy >= 5) <span class="badge badge-warning">Passable</span>
            @else <span class="badge badge-danger">Insuffisant</span>
            @endif
          </td>

        @endif

        {{-- ACTIONS --}}
        <td>
          <a href="{{ route('eleves.show', $eleve) }}" class="btn-outline">
            <i class="ti ti-eye"></i> Détail
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@empty
<div class="card">
  <div style="text-align:center; padding:40px; color:var(--text-muted);">
    <i class="ti ti-trophy" style="font-size:32px; display:block; margin-bottom:8px;"></i>
    Aucune note enregistrée
  </div>
</div>
@endforelse

@endsection