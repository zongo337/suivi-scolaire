@extends('layouts.app')

@section('title', 'Classes')
@section('page-title', 'Classes')

@section('topbar-actions')
  <a href="{{ route('classes.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Nouvelle classe
  </a>
@endsection

@section('content')

<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="ti ti-school"></i> Liste des classes</span>
  </div>
  <table>
    <thead>
      <tr>
        <th>Classe</th>
        <th>Effectif max</th>
        <th>Élèves inscrits</th>
        <th>Frais de scolarité</th>
        <th>Total collecté</th>
        <th>Reste à collecter</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($classes as $classe)
      <tr>
        <td>
          <div style="display:flex; align-items:center; gap:10px;">
            <div class="avatar-circle" style="background:var(--green-light); color:var(--green-dark);">
              {{ $classe->nom }}
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);">{{ $classe->effectif_max }} élèves</td>
        <td>
          <span class="badge {{ $classe->eleves_count >= $classe->effectif_max ? 'badge-danger' : 'badge-success' }}">
            {{ $classe->eleves_count }} / {{ $classe->effectif_max }}
          </span>
        </td>
        <td style="font-family:'DM Mono',monospace; font-size:13px;">
          {{ number_format($classe->frais_scolarite, 0, ',', ' ') }} FCFA
        </td>
        <td style="font-family:'DM Mono',monospace; font-size:13px; color:var(--green-dark); font-weight:600;">
          {{ number_format($classe->total_paiements, 0, ',', ' ') }} FCFA
        </td>
        <td style="font-family:'DM Mono',monospace; font-size:13px;
                   color:{{ $classe->total_attendu - $classe->total_paiements > 0 ? 'var(--red)' : 'var(--green-dark)' }}; font-weight:600;">
          {{ number_format($classe->total_attendu - $classe->total_paiements, 0, ',', ' ') }} FCFA
        </td>
        <td>
          <div style="display:flex; gap:6px;">
            <a href="{{ route('classes.show', $classe) }}" class="btn-outline">
              <i class="ti ti-eye"></i>
            </a>
            <a href="{{ route('classes.edit', $classe) }}" class="btn-outline">
              <i class="ti ti-pencil"></i>
            </a>
            <form method="POST" action="{{ route('classes.destroy', $classe) }}"
                  onsubmit="return confirm('Supprimer cette classe ? Tous les élèves seront supprimés.')">
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
          <i class="ti ti-school" style="font-size:32px; display:block; margin-bottom:8px;"></i>
          Aucune classe créée
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection