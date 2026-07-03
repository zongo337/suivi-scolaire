@extends('layouts.app')

@section('title', 'Enseignants')
@section('page-title', 'Gestion des Enseignants')

@section('topbar-actions')
  <a href="{{ route('enseignants.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Nouvel enseignant
  </a>
@endsection

@section('content')

<div class="card">
  <div class="card-header">
    <span class="card-title">
      <i class="ti ti-chalkboard"></i> Liste des enseignants
      <span style="font-size:13px; font-weight:400; color:var(--text-muted);">
        ({{ $enseignants->count() }} enseignants)
      </span>
    </span>
  </div>
  <table>
    <thead>
      <tr>
        <th>Enseignant</th>
        <th>Email</th>
        <th>Classe assignée</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($enseignants as $enseignant)
      <tr>
        <td>
          <div class="avatar-cell">
            <div class="avatar-circle" style="background:var(--green-light); color:var(--green-dark);">
              {{ strtoupper(substr($enseignant->name, 0, 2)) }}
            </div>
            <div>
              <div class="avatar-name">{{ $enseignant->name }}</div>
              <div class="avatar-sub">Enseignant</div>
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);">{{ $enseignant->email }}</td>
        <td>
          @if($enseignant->classe)
            <span class="badge badge-info">{{ $enseignant->classe->nom }}</span>
          @else
            <span class="badge badge-danger">Non assigné</span>
          @endif
        </td>
        <td>
          <div style="display:flex; gap:6px;">
            <a href="{{ route('enseignants.edit', $enseignant) }}" class="btn-outline">
              <i class="ti ti-pencil"></i> Modifier
            </a>
            <form method="POST" action="{{ route('enseignants.destroy', $enseignant) }}"
                  onsubmit="return confirm('Supprimer cet enseignant ?')">
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
        <td colspan="4" style="text-align:center; color:var(--text-muted); padding:40px;">
          <i class="ti ti-chalkboard" style="font-size:32px; display:block; margin-bottom:8px;"></i>
          Aucun enseignant créé
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection