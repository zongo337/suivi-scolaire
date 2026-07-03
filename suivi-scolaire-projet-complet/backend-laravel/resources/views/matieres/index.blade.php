@extends('layouts.app')

@section('title', 'Matières')
@section('page-title', 'Matières par classe')

@section('topbar-actions')
  <a href="{{ route('matieres.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Nouvelle matière
  </a>
@endsection

@section('content')

{{-- MATIÈRES PAR CLASSE --}}
@foreach($classes as $classe)
<div class="card" style="margin-bottom:20px;">
  <div class="card-header">
    <span class="card-title">
      <i class="ti ti-book"></i> Classe {{ $classe->nom }}
      <span style="font-size:13px; font-weight:400; color:var(--text-muted);">
        ({{ $classe->matieres->count() }} matières)
      </span>
    </span>
  </div>

  @if($classe->matieres->count() > 0)
  <table>
    <thead>
      <tr>
        <th>Matière</th>
        <th>Coefficient</th>
        <th>Poids</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($classe->matieres as $matiere)
      <tr>
        <td>
          <div class="avatar-cell">
            <div class="avatar-circle" style="background:var(--green-light); color:var(--green-dark);">
              <i class="ti ti-book" style="font-size:14px;"></i>
            </div>
            <span style="font-weight:500;">{{ $matiere->nom }}</span>
          </div>
        </td>
        <td>
          <span class="badge badge-info">× {{ $matiere->coefficient }}</span>
        </td>
        <td>
          <div style="display:flex; align-items:center; gap:8px;">
            <div class="progress-bar" style="width:120px;">
              <div class="progress-fill"
                   style="width:{{ ($matiere->coefficient / 3) * 100 }}%;">
              </div>
            </div>
            <span style="font-size:12px; color:var(--text-muted);">
              {{ $matiere->coefficient }}/3
            </span>
          </div>
        </td>
        <td>
          <div style="display:flex; gap:6px;">
            <a href="{{ route('matieres.edit', $matiere) }}" class="btn-outline">
              <i class="ti ti-pencil"></i> Modifier
            </a>
            <form method="POST" action="{{ route('matieres.destroy', $matiere) }}"
                  onsubmit="return confirm('Supprimer cette matière ?')">
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
  @else
  <div style="text-align:center; padding:24px; color:var(--text-muted); font-size:13px;">
    Aucune matière associée à cette classe
    <a href="{{ route('matieres.create') }}" style="color:var(--green-dark); margin-left:8px;">
      + Ajouter une matière
    </a>
  </div>
  @endif
</div>
@endforeach

@endsection