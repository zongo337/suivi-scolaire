@extends('layouts.app')

@section('title', 'Absences')
@section('page-title', 'Absences de ma classe')

@section('content')

@if(!$classe)
  <div class="alert alert-danger">
    <i class="ti ti-x"></i> Aucune classe ne vous est assignée. Contactez l'administrateur.
  </div>
@else

<div class="two-col">
  <div class="card" style="margin-bottom:0;">
    <div class="card-header">
      <span class="card-title"><i class="ti ti-plus"></i> Déclarer une absence — {{ $classe->nom }}</span>
    </div>
    <div style="padding:20px;">
      <form method="POST" action="{{ route('enseignant.absences.store') }}">
        @csrf
        <div class="form-group">
          <label class="form-label">Élève <span style="color:var(--red);">*</span></label>
          <select name="eleve_id" class="form-select" required>
            <option value="">Choisir un élève...</option>
            @foreach($eleves as $eleve)
              <option value="{{ $eleve->id }}">{{ $eleve->prenom }} {{ $eleve->nom }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Date <span style="color:var(--red);">*</span></label>
          <input type="date" name="date_absence" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">Motif</label>
          <input type="text" name="motif" class="form-control" placeholder="Ex: Maladie">
        </div>
        <div class="form-group">
          <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
            <input type="checkbox" name="justifiee" value="1">
            <span class="form-label" style="margin-bottom:0;">Absence justifiée</span>
          </label>
        </div>
        <button type="submit" class="btn-primary" style="width:100%; justify-content:center;">
          <i class="ti ti-check"></i> Enregistrer
        </button>
      </form>
    </div>
  </div>

  <div class="card" style="margin-bottom:0;">
    <div class="card-header">
      <span class="card-title">
        <i class="ti ti-calendar-x"></i> Historique
        <span style="font-size:13px; font-weight:400; color:var(--text-muted);">({{ $absences->count() }})</span>
      </span>
    </div>
    <table>
      <thead>
        <tr><th>Élève</th><th>Date</th><th>Statut</th><th></th></tr>
      </thead>
      <tbody>
        @forelse($absences as $absence)
        <tr>
          <td>{{ $absence->eleve->prenom }} {{ $absence->eleve->nom }}</td>
          <td style="color:var(--text-secondary);">{{ $absence->date_absence->format('d/m/Y') }}</td>
          <td>
            @if($absence->justifiee)
              <span class="badge badge-success">Justifiée</span>
            @else
              <span class="badge badge-warning">Non justifiée</span>
            @endif
          </td>
          <td>
            <form method="POST" action="{{ route('enseignant.absences.destroy', $absence) }}"
                  onsubmit="return confirm('Supprimer cette absence ?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger"><i class="ti ti-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center; color:var(--text-muted); padding:30px;">Aucune absence enregistrée</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endif

@endsection
