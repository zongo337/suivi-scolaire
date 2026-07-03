@extends('layouts.app')

@section('title', 'Absences')
@section('page-title', 'Suivi des Absences')

@section('topbar-actions')
  <a href="{{ route('absences.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Déclarer une absence
  </a>
@endsection

@section('content')

<div class="card">
  <div class="card-header">
    <span class="card-title">
      <i class="ti ti-calendar-x"></i> Absences
      <span style="font-size:13px; font-weight:400; color:var(--text-muted);">
        ({{ $absences->count() }} absences)
      </span>
    </span>
    <form method="GET" style="display:flex; gap:8px;">
      <select name="classe_id" class="form-select" onchange="this.form.submit()" style="width:180px;">
        <option value="">Toutes les classes</option>
        @foreach($classes as $classe)
          <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
            {{ $classe->nom }}
          </option>
        @endforeach
      </select>
    </form>
  </div>
  <table>
    <thead>
      <tr>
        <th>Élève</th>
        <th>Classe</th>
        <th>Date</th>
        <th>Motif</th>
        <th>Statut</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($absences as $absence)
      <tr>
        <td class="avatar-name">{{ $absence->eleve->prenom }} {{ $absence->eleve->nom }}</td>
        <td><span class="badge badge-info">{{ $absence->eleve->classe->nom ?? '—' }}</span></td>
        <td style="color:var(--text-secondary);">{{ $absence->date_absence->format('d/m/Y') }}</td>
        <td style="color:var(--text-secondary);">{{ $absence->motif ?? '—' }}</td>
        <td>
          @if($absence->justifiee)
            <span class="badge badge-success"><i class="ti ti-check"></i> Justifiée</span>
          @else
            <span class="badge badge-warning"><i class="ti ti-alert-triangle"></i> Non justifiée</span>
          @endif
        </td>
        <td>
          <form method="POST" action="{{ route('absences.destroy', $absence) }}"
                onsubmit="return confirm('Supprimer cette absence ?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger"><i class="ti ti-trash"></i></button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" style="text-align:center; color:var(--text-muted); padding:40px;">
          <i class="ti ti-calendar-x" style="font-size:32px; display:block; margin-bottom:8px;"></i>
          Aucune absence enregistrée
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
