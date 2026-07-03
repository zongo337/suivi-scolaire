@extends('layouts.app')

@section('title', 'Annonces')
@section('page-title', 'Annonces & Notifications')

@section('topbar-actions')
  <a href="{{ route('annonces.create') }}" class="btn-primary">
    <i class="ti ti-plus"></i> Nouvelle annonce
  </a>
@endsection

@section('content')

<div class="card">
  <div class="card-header">
    <span class="card-title">
      <i class="ti ti-speakerphone"></i> Annonces publiées
      <span style="font-size:13px; font-weight:400; color:var(--text-muted);">
        ({{ $annonces->count() }})
      </span>
    </span>
  </div>
  <table>
    <thead>
      <tr>
        <th>Titre</th>
        <th>Type</th>
        <th>Destinataires</th>
        <th>Date de publication</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($annonces as $annonce)
      <tr>
        <td>
          <div class="avatar-name">{{ $annonce->titre }}</div>
          <div class="avatar-sub" style="max-width:380px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            {{ $annonce->contenu }}
          </div>
        </td>
        <td>
          @if($annonce->type === 'notification')
            <span class="badge badge-warning"><i class="ti ti-bell"></i> Notification</span>
          @else
            <span class="badge badge-info"><i class="ti ti-speakerphone"></i> Annonce</span>
          @endif
        </td>
        <td>
          @if($annonce->classe)
            <span class="badge badge-gray">{{ $annonce->classe->nom }}</span>
          @else
            <span class="badge badge-success">Toute l'école</span>
          @endif
        </td>
        <td style="color:var(--text-secondary);">{{ $annonce->date_publication->format('d/m/Y H:i') }}</td>
        <td>
          <form method="POST" action="{{ route('annonces.destroy', $annonce) }}"
                onsubmit="return confirm('Supprimer cette annonce ?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger"><i class="ti ti-trash"></i></button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5" style="text-align:center; color:var(--text-muted); padding:40px;">
          <i class="ti ti-speakerphone" style="font-size:32px; display:block; margin-bottom:8px;"></i>
          Aucune annonce publiée
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
