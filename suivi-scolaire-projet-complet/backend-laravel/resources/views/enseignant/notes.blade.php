@extends('layouts.app')

@section('title', 'Saisie des notes')
@section('page-title')
  Mes notes — {{ $classe?->nom ?? 'Aucune classe' }}
@endsection

@section('topbar-actions')
  {{-- L'enseignant ne peut pas inscrire des élèves ni gérer les paiements --}}
  <span style="font-size:13px; color:var(--text-secondary); padding:8px 12px;
               background:var(--green-light); border-radius:8px;">
    <i class="ti ti-school"></i>
    Classe : <strong>{{ $classe?->nom ?? 'Non assignée' }}</strong>
  </span>
@endsection

@section('content')

@if(!$classe)
  {{-- Pas de classe assignée --}}
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

  <!-- FILTRES -->
  <div class="card" style="margin-bottom:20px;">
    <div style="padding:16px 20px;">
      <form method="GET" action="{{ route('enseignant.notes') }}"
            style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
        <div style="flex:1; min-width:130px;">
          <label class="form-label">Trimestre</label>
          <select name="trimestre" class="form-select">
            <option value="">Tous les trimestres</option>
            <option value="1" {{ request('trimestre') == '1' ? 'selected' : '' }}>Trimestre 1</option>
            <option value="2" {{ request('trimestre') == '2' ? 'selected' : '' }}>Trimestre 2</option>
            <option value="3" {{ request('trimestre') == '3' ? 'selected' : '' }}>Trimestre 3</option>
          </select>
        </div>
        <div style="flex:1; min-width:130px;">
          <label class="form-label">Année scolaire</label>
          <input type="text" name="annee_scolaire" class="form-control"
                 placeholder="Ex: 2025-2026"
                 value="{{ request('annee_scolaire', '2025-2026') }}">
        </div>
        <div>
          <button type="submit" class="btn-primary">
            <i class="ti ti-search"></i> Afficher
          </button>
        </div>
      </form>
    </div>
  </div>

  @if($eleves->isEmpty())
    <div class="card">
      <div style="text-align:center; padding:40px; color:var(--text-muted);">
        <i class="ti ti-users" style="font-size:32px; display:block; margin-bottom:8px;"></i>
        Aucun élève dans cette classe
      </div>
    </div>

  @else

    <!-- TABLEAU DE SAISIE DES NOTES -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">
          <i class="ti ti-pencil"></i>
          Notes — {{ $classe->nom }}
          @if(request('trimestre'))
            | Trimestre {{ request('trimestre') }}
          @endif
          <span class="badge badge-info" style="margin-left:8px;">
            Sur {{ $classe->note_sur ?? 20 }}
          </span>
        </span>
      </div>

      <form method="POST" action="{{ route('enseignant.notes.bulk') }}">
        @csrf
        <input type="hidden" name="trimestre" value="{{ request('trimestre', '1') }}">
        <input type="hidden" name="annee_scolaire" value="{{ request('annee_scolaire', '2025-2026') }}">

        <div style="overflow-x:auto;">
          <table>
            <thead>
              <tr>
                <th style="min-width:180px;">Élève</th>
                @foreach($matieres as $matiere)
                  <th style="text-align:center; min-width:120px;">
                    {{ $matiere->nom }}
                    <div style="font-weight:400; font-size:10px; color:var(--text-muted);">
                      Coeff. {{ $matiere->coefficient }} | /{{ $matiere->note_sur ?? 20 }}
                    </div>
                  </th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @foreach($eleves as $eleveIndex => $eleve)
              <tr>
                <td>
                  <div class="avatar-cell">
                    <div class="avatar-circle"
                         style="background:{{ $eleve->sexe === 'M' ? '#E6F1FB' : '#EEEDFE' }};
                                color:{{ $eleve->sexe === 'M' ? '#185FA5' : '#534AB7' }};">
                      {{ strtoupper(substr($eleve->prenom, 0, 1) . substr($eleve->nom, 0, 1)) }}
                    </div>
                    <div>
                      <div class="avatar-name">{{ $eleve->prenom }} {{ $eleve->nom }}</div>
                    </div>
                  </div>

                  {{-- Champ caché eleve_id --}}
                  <input type="hidden"
                         name="notes[{{ $eleveIndex }}_0][eleve_id]"
                         value="{{ $eleve->id }}">
                </td>

                @foreach($matieres as $matiereIndex => $matiere)
                  @php
                    $note = $eleve->notes
                              ->where('matiere_id', $matiere->id)
                              ->where('trimestre', request('trimestre', '1'))
                              ->first();
                  @endphp
                  <td style="text-align:center;">
                    {{-- Champs cachés eleve_id et matiere_id --}}
                    <input type="hidden"
                           name="notes[{{ $eleveIndex }}_{{ $matiereIndex }}][eleve_id]"
                           value="{{ $eleve->id }}">
                    <input type="hidden"
                           name="notes[{{ $eleveIndex }}_{{ $matiereIndex }}][matiere_id]"
                           value="{{ $matiere->id }}">
                    {{-- Champ de saisie de la note --}}
                    <input type="number"
                           name="notes[{{ $eleveIndex }}_{{ $matiereIndex }}][note]"
                           class="form-control"
                           style="width:80px; text-align:center; margin:0 auto;"
                           min="0"
                           max="{{ $matiere->note_sur ?? 20 }}"
                           step="0.25"
                           value="{{ $note ? $note->note : '' }}"
                           placeholder="—">
                  </td>
                @endforeach
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div style="padding:16px 20px; border-top:1px solid var(--border);
                    display:flex; justify-content:flex-end;">
          <button type="submit" class="btn-primary">
            <i class="ti ti-check"></i> Enregistrer les notes
          </button>
        </div>
      </form>
    </div>

    <!-- LISTE DES NOTES AVEC BOUTON SUPPRIMER -->
    <div class="card" style="margin-top:20px;">
<div class="card-header">
  <span class="card-title">
    <i class="ti ti-list"></i> Notes enregistrées
  </span>
</div>
      <table>
        <thead>
          <tr>
            <th>Élève</th>
            <th>Matière</th>
            <th>Trimestre</th>
            <th>Note</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        
         @php
  $notesAffichees = $eleves->flatMap->notes;
  if(request('trimestre')) {
      $notesAffichees = $notesAffichees->where('trimestre', request('trimestre'));
  }
@endphp
@forelse($notesAffichees as $note)
          <tr>
            <td>
              <div class="avatar-cell">
                <div class="avatar-circle" style="background:var(--green-light); color:var(--green-dark);">
                  {{ strtoupper(substr($note->eleve->prenom ?? '?', 0, 1) . substr($note->eleve->nom ?? '?', 0, 1)) }}
                </div>
                <div class="avatar-name">
                  {{ $note->eleve->prenom ?? '' }} {{ $note->eleve->nom ?? '' }}
                </div>
              </div>
            </td>
            <td>{{ $note->matiere->nom ?? '—' }}</td>
            <td><span class="badge badge-info">T{{ $note->trimestre }}</span></td>
            <td style="font-family:'DM Mono',monospace; font-weight:600; color:var(--green-dark);">
              {{ $note->note }}/{{ $note->matiere->note_sur ?? 20 }}
            </td>
            <td>
  <div style="display:flex; gap:6px;">
    {{-- Relevé de notes de l'élève --}}
    <a href="{{ route('enseignant.releve', $note->eleve) }}"
       class="btn-outline" target="_blank">
      <i class="ti ti-file-text"></i>
    </a>
    {{-- Supprimer une note --}}
    <form method="POST"
          action="{{ route('enseignant.notes.destroy', $note) }}"
          onsubmit="return confirm('Supprimer cette note ?')">
      @csrf @method('DELETE')
      <button type="submit" class="btn-danger">
        <i class="ti ti-trash"></i>
      </button>
    </form>
  </div>
</td>
          </tr>
          @empty
          <tr>
            <td colspan="5" style="text-align:center; color:var(--text-muted); padding:24px;">
              Aucune note enregistrée pour ce trimestre
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  @endif
@endif

@endsection