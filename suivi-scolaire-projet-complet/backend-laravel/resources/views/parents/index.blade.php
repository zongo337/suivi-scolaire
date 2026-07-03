@extends('layouts.app')

@section('title', 'Parents')
@section('page-title', 'Comptes Parents')

@section('topbar-actions')
  <a href="{{ route('parents.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Nouveau compte parent
  </a>
@endsection

@section('content')

<div class="card">
  <div class="card-header">
    <span class="card-title">
      <i class="ti ti-users"></i> Comptes parents
      <span style="font-size:13px; font-weight:400; color:var(--text-muted);">
        ({{ $parents->count() }} comptes)
      </span>
    </span>
  </div>
  <table>
    <thead>
      <tr>
        <th>Parent</th>
        <th>Email</th>
        <th>Téléphone</th>
        <th>Enfant(s) suivi(s)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($parents as $parent)
      <tr>
        <td>
          <div class="avatar-cell">
            <div class="avatar-circle" style="background:var(--blue-light); color:var(--blue);">
              {{ strtoupper(substr($parent->prenom, 0, 1).substr($parent->nom, 0, 1)) }}
            </div>
            <div>
              <div class="avatar-name">{{ $parent->prenom }} {{ $parent->nom }}</div>
              <div class="avatar-sub">Parent</div>
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);">{{ $parent->email }}</td>
        <td style="color:var(--text-secondary);">{{ $parent->telephone ?? '—' }}</td>
        <td>
          @forelse($parent->eleves as $eleve)
            <span class="badge badge-info" style="margin-bottom:2px;">
              {{ $eleve->prenom }} {{ $eleve->nom }} ({{ $eleve->classe->nom ?? '—' }})
            </span>
          @empty
            <span class="badge badge-danger">Aucun enfant associé</span>
          @endforelse
        </td>
        <td>
          <div style="display:flex; gap:6px;">
            <a href="{{ route('parents.edit', $parent) }}" class="btn-outline">
              <i class="ti ti-pencil"></i> Modifier
            </a>
            <form method="POST" action="{{ route('parents.destroy', $parent) }}"
                  onsubmit="return confirm('Supprimer ce compte parent ? L\'accès à l\'application mobile sera immédiatement révoqué.')">
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
        <td colspan="5" style="text-align:center; color:var(--text-muted); padding:40px;">
          <i class="ti ti-users" style="font-size:32px; display:block; margin-bottom:8px;"></i>
          Aucun compte parent créé
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
