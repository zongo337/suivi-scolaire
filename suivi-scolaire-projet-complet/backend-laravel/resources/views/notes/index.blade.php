@extends('layouts.app')

@section('title', 'Saisie des notes')
@section('page-title', 'Saisie des notes')

@section('content')

<!-- FILTRES -->
<div class="card" style="margin-bottom:20px;">
  <div style="padding:16px 20px;">
    <form method="GET" action="{{ route('notes.index') }}"
          style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
      <div style="flex:1; min-width:150px;">
        <label class="form-label">Classe</label>
        <select name="classe_id" class="form-select">
          <option value="">Choisir une classe...</option>
          @foreach($classes as $classe)
            <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
              {{ $classe->nom }}
            </option>
          @endforeach
        </select>
      </div>
      <div style="flex:1; min-width:130px;">
        <label class="form-label">Trimestre</label>
        <select name="trimestre" class="form-select">
          <option value="">Tous</option>
          <option value="1" {{ request('trimestre') == '1' ? 'selected' : '' }}>Trimestre 1</option>
          <option value="2" {{ request('trimestre') == '2' ? 'selected' : '' }}>Trimestre 2</option>
          <option value="3" {{ request('trimestre') == '3' ? 'selected' : '' }}>Trimestre 3</option>
        </select>
      </div>
      <div style="flex:1; min-width:130px;">
        <label class="form-label">Année scolaire</label>
        <input type="text" name="annee_scolaire" class="form-control"
               placeholder="Ex: 2025-2026" value="{{ request('annee_scolaire', '2025-2026') }}">
      </div>
      <div>
        <button type="submit" class="btn-primary">
          <i class="ti ti-search"></i> Afficher
        </button>
      </div>
    </form>
  </div>
</div>

@if($classe && $eleves->count() > 0)

<!-- TABLEAU SAISIE -->
<div class="card">
  <div class="card-header">
    <span class="card-title">
      <i class="ti ti-pencil"></i>
      Notes — {{ $classe->nom }} | Trimestre {{ request('trimestre', 'tous') }}
    </span>
    <a href="{{ route('notes.moyennes') }}?classe_id={{ $classe->id }}" class="btn-outline">
      <i class="ti ti-trophy"></i> Voir moyennes
    </a>
  </div>

  <form method="POST" action="{{ route('notes.storeBulk') }}">
    @csrf
    <input type="hidden" name="trimestre" value="{{ request('trimestre', '1') }}">
    <input type="hidden" name="annee_scolaire" value="{{ request('annee_scolaire', '2025-2026') }}">

    <div style="overflow-x:auto;">
      <table>
        <thead>
          <tr>
            <th style="min-width:180px;">Élève</th>
            
            {{-- Entête colonne --}}
@foreach($matieres as $matiere)
  <th style="text-align:center; min-width:110px;">
    {{ $matiere->nom }}
    <div style="font-weight:400; font-size:10px; color:var(--text-muted);">
      Coeff. {{ $matiere->coefficient }} | /{{ $matiere->note_sur }}
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
                <div class="avatar-circle" style="background:var(--green-light); color:var(--green-dark);">
                  {{ strtoupper(substr($eleve->prenom, 0, 1) . substr($eleve->nom, 0, 1)) }}
                </div>
                <div>
                  <div class="avatar-name">{{ $eleve->prenom }} {{ $eleve->nom }}</div>
                </div>
              </div>
              <input type="hidden" name="notes[{{ $loop->index }}][eleve_id]" value="{{ $eleve->id }}">
            </td>
            @foreach($matieres as $matiereIndex => $matiere)
    @php
        $note = $eleve->notes->where('matiere_id', $matiere->id)
                 ->where('trimestre', request('trimestre', '1'))->first();
    @endphp
    <td style="text-align:center;">
        <input type="hidden" 
               name="notes[{{ $eleveIndex }}_{{ $matiereIndex }}][eleve_id]"
               value="{{ $eleve->id }}">
        <input type="hidden" 
               name="notes[{{ $eleveIndex }}_{{ $matiereIndex }}][matiere_id]"
               value="{{ $matiere->id }}">
        
<input type="number"
       name="notes[{{ $eleveIndex }}_{{ $matiereIndex }}][note]"
       class="form-control"
       style="width:80px; text-align:center; margin:0 auto;"
       min="0" max="{{ $matiere->note_sur }}" step="0.25"
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
                display:flex; justify-content:flex-end; gap:12px;">
      <button type="submit" class="btn-primary">
        <i class="ti ti-check"></i> Enregistrer toutes les notes
      </button>
    </div>
  </form>
</div>

@elseif($classe && $eleves->count() === 0)
  <div class="card">
    <div style="text-align:center; padding:40px; color:var(--text-muted);">
      <i class="ti ti-users" style="font-size:32px; display:block; margin-bottom:8px;"></i>
      Aucun élève dans cette classe
    </div>
  </div>
@else
  <div class="card">
    <div style="text-align:center; padding:40px; color:var(--text-muted);">
      <i class="ti ti-pencil" style="font-size:32px; display:block; margin-bottom:8px;"></i>
      Sélectionnez une classe pour saisir les notes
    </div>
  </div>
@endif

@endsection