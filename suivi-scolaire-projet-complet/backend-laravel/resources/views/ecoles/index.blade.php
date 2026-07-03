@extends('layouts.app')

@section('title', 'Écoles')
@section('page-title', 'Gestion des Écoles')

@section('topbar-actions')
  <a href="{{ route('ecoles.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Nouvelle école
  </a>
@endsection

@section('content')

<div class="card">
  <div class="card-header">
    <span class="card-title">
      <i class="ti ti-building"></i> Liste des écoles
      <span style="font-size:13px; font-weight:400; color:var(--text-muted);">
        ({{ $ecoles->count() }} écoles)
      </span>
    </span>
  </div>
  <table>
    <thead>
      <tr>
        <th>École</th>
        <th>Adresse</th>
        <th>Directeur</th>
        <th>Téléphone</th>
        <th>Email</th>
        <th>Statut</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($ecoles as $ecole)
      <tr>
        <td>
          <div class="avatar-cell">
            <div class="avatar-circle" style="background:var(--green-light); color:var(--green-dark);">
              <i class="ti ti-building" style="font-size:14px;"></i>
            </div>
            <div>
              <div class="avatar-name">{{ $ecole->nom }}</div>
              @if($ecole->active)
                <div class="avatar-sub" style="color:var(--green-dark);">École active</div>
              @endif
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);">{{ $ecole->adresse ?? '—' }}</td>
        <td>{{ $ecole->directeur ?? '—' }}</td>
        <td style="font-family:'DM Mono',monospace; font-size:13px;">
          {{ $ecole->telephone ?? '—' }}
        </td>
        <td style="font-size:13px; color:var(--text-secondary);">
          {{ $ecole->email ?? '—' }}
        </td>
        <td>
          @if($ecole->active)
            <span class="badge badge-success">
              <i class="ti ti-check" style="font-size:11px;"></i> Active
            </span>
          @else
            <span class="badge badge-gray">Inactive</span>
          @endif
        </td>
        <td>
          <div style="display:flex; gap:6px;">
            @if(!$ecole->active)
              <form method="POST" action="{{ route('ecoles.activer', $ecole) }}">
                @csrf
                <button type="submit" class="btn-primary"
                        style="padding:6px 12px; font-size:12px;">
                  <i class="ti ti-check"></i> Activer
                </button>
              </form>
            @else
              <span style="font-size:12px; color:var(--green-dark); padding:6px 12px;
                           background:var(--green-light); border-radius:6px;">
                <i class="ti ti-star"></i> En cours
              </span>
            @endif
            <form method="POST" action="{{ route('ecoles.destroy', $ecole) }}"
                  onsubmit="return confirm('Supprimer cette école ?')">
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
        <td colspan="7" style="text-align:center; color:var(--text-muted); padding:40px;">
          <i class="ti ti-building" style="font-size:32px; display:block; margin-bottom:8px;"></i>
          Aucune école enregistrée — Ajoutez votre établissement
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection