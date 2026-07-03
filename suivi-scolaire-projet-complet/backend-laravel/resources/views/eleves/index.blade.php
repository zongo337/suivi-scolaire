@extends('layouts.app')

@section('title', 'Élèves')
@section('page-title', 'Élèves')

@section('topbar-actions')
  <a href="{{ route('eleves.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Nouvel élève
  </a>
@endsection

@section('content')

<!-- CARTES CLASSES -->
<div style="display:grid; grid-template-columns:repeat(6,1fr); gap:12px; margin-bottom:20px;">
  @foreach($classes as $c)
  <a href="{{ route('eleves.index', ['classe_id' => $c->id]) }}" style="text-decoration:none;">
    <div style="background:#fff; border-radius:10px; padding:16px;
                border:2px solid {{ isset($classe) && $classe->id === $c->id ? '#1D9E75' : '#dde8e4' }};
                cursor:pointer; text-align:center;">
      <div style="font-size:22px; font-weight:600;
                  color:{{ isset($classe) && $classe->id === $c->id ? '#0F6E56' : '#1a2e26' }};">
        {{ $c->eleves_count }}
      </div>
      <div style="font-size:13px; font-weight:600; color:#0F6E56; margin-top:4px;">
        {{ $c->nom }}
      </div>
      <div style="font-size:11px; color:#8fa89f; margin-top:2px;">élèves</div>
    </div>
  </a>
  @endforeach
</div>

<!-- FILTRE -->
<div class="card" style="margin-bottom:20px;">
  <div style="padding:16px 20px;">
    <form method="GET" action="{{ route('eleves.index') }}"
          style="display:flex; gap:12px; align-items:flex-end;">
      <div style="flex:2;">
        <label class="form-label">Rechercher</label>
        <input type="text" name="search" class="form-control"
               placeholder="Nom ou prénom de l'élève..."
               value="{{ request('search') }}">
      </div>
      <div style="flex:1;">
        <label class="form-label">Filtrer par classe</label>
        <select name="classe_id" class="form-select">
          <option value="">Toutes les classes</option>
          @foreach($classes as $c)
            <option value="{{ $c->id }}"
              {{ request('classe_id') == $c->id ? 'selected' : '' }}>
              {{ $c->nom }} ({{ $c->eleves_count }} élèves)
            </option>
          @endforeach
        </select>
      </div>
      <div>
        <button type="submit" class="btn-primary">
          <i class="ti ti-search"></i> Filtrer
        </button>
        <a href="{{ route('eleves.index') }}" class="btn-secondary" style="margin-left:8px;">
          <i class="ti ti-x"></i> Reset
        </a>
      </div>
    </form>
  </div>
</div>

<!-- TABLEAU -->
<div class="card">
  <div class="card-header">
    <span class="card-title">
      <i class="ti ti-users"></i>
      @if(isset($classe))
        Élèves de la classe {{ $classe->nom }}
      @else
        Sélectionnez une classe
      @endif
      @if(isset($eleves) && method_exists($eleves, 'total'))
        <span style="font-size:13px; font-weight:400; color:var(--text-muted);">
          ({{ $eleves->total() }} élèves)
        </span>
      @endif
    </span>
  </div>

  @if(isset($classe))
  <table>
    <thead>
      <tr>
        <th>Élève</th>
        <th>Date de naissance</th>
        <th>Parent / Tuteur</th>
        <th>Téléphone</th>
        <th>Situation</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($eleves as $eleve)
      <tr>
        <td>
          <div class="avatar-cell">
            @if($eleve->photo)
              <img src="{{ asset('storage/' . $eleve->photo) }}"
                   style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
            @else
              <div class="avatar-circle"
                   style="background:{{ $eleve->sexe === 'M' ? '#E6F1FB' : '#EEEDFE' }};
                          color:{{ $eleve->sexe === 'M' ? '#185FA5' : '#534AB7' }};">
                {{ strtoupper(substr($eleve->prenom, 0, 1) . substr($eleve->nom, 0, 1)) }}
              </div>
            @endif
            <div>
              <div class="avatar-name">{{ $eleve->prenom }} {{ $eleve->nom }}</div>
              <div class="avatar-sub">{{ $eleve->sexe === 'M' ? 'Garçon' : 'Fille' }}</div>
            </div>
          </div>
        </td>
        <td style="color:var(--text-secondary);">
          {{ \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') }}
        </td>
        <td>{{ $eleve->nom_parent }}</td>
        <td style="font-family:'DM Mono',monospace; font-size:13px;">
          {{ $eleve->telephone_parent }}
        </td>
        <td>
          @if($eleve->reste_a_payer <= 0)
            <span class="badge badge-success">
              <i class="ti ti-check" style="font-size:11px;"></i> À jour
            </span>
          @elseif($eleve->total_paye > 0)
            <span class="badge badge-warning">
              <i class="ti ti-clock" style="font-size:11px;"></i> Partiel
            </span>
          @else
            <span class="badge badge-danger">
              <i class="ti ti-x" style="font-size:11px;"></i> Impayé
            </span>
          @endif
        </td>
        <td>
  <div style="display:flex; gap:6px;">
    <a href="{{ route('eleves.show', $eleve) }}" class="btn-outline">
      <i class="ti ti-eye"></i>
    </a>
    <a href="{{ route('eleves.edit', $eleve) }}" class="btn-outline">
      <i class="ti ti-pencil"></i>
    </a>
    {{-- Relevé de notes imprimable --}}
    <a href="{{ route('eleves.releve', $eleve) }}" class="btn-outline" target="_blank">
      <i class="ti ti-file-text"></i>
    </a>
    <form method="POST" action="{{ route('eleves.destroy', $eleve) }}"
          onsubmit="return confirm('Supprimer cet élève ?')">
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
        <td colspan="6" style="text-align:center; color:var(--text-muted); padding:40px;">
          Aucun élève dans cette classe
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>

  @if($eleves->hasPages())
  <div style="padding:16px 20px; border-top:1px solid var(--border);
              display:flex; justify-content:flex-end;">
    {{ $eleves->withQueryString()->links() }}
  </div>
  @endif

  @else
  <div style="text-align:center; padding:60px; color:var(--text-muted);">
    <i class="ti ti-school" style="font-size:48px; display:block; margin-bottom:12px;"></i>
    <div style="font-size:15px; font-weight:500; margin-bottom:6px;">
      Cliquez sur une classe pour voir ses élèves
    </div>
    <div style="font-size:13px;">Sélectionnez CP1, CP2, CE1, CE2, CM1 ou CM2</div>
  </div>
  @endif

</div>

@endsection