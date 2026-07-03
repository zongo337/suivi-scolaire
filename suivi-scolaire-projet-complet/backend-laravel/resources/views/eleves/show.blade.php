@extends('layouts.app')

@section('title', $eleve->prenom . ' ' . $eleve->nom)
@section('page-title', $eleve->prenom . ' ' . $eleve->nom)

@section('topbar-actions')
  <a href="{{ route('eleves.edit', $eleve) }}" class="btn-primary">
    <i class="ti ti-pencil"></i> Modifier
  </a>
  <a href="{{ route('eleves.index') }}" class="btn-secondary">
    <i class="ti ti-arrow-left"></i> Retour
  </a>

  <a href="{{ route('eleves.releve', $eleve) }}" class="btn-primary" target="_blank">
  <i class="ti ti-file-text"></i> Relevé de notes
</a>
@endsection

@section('content')

<div class="two-col" style="align-items:start;">

  <!-- COLONNE GAUCHE : PROFIL + PAIEMENTS -->
  <div>

    <!-- PROFIL -->
    <div class="card">
      <div style="padding:24px; text-align:center; border-bottom:1px solid var(--border);">
        @if($eleve->photo)
          <img src="{{ asset('storage/' . $eleve->photo) }}"
               style="width:80px;height:80px;border-radius:50%;object-fit:cover;
                      border:3px solid var(--green-light); margin-bottom:12px;">
        @else
          <div style="width:80px;height:80px;border-radius:50%;
                      background:{{ $eleve->sexe === 'M' ? '#E6F1FB' : '#EEEDFE' }};
                      color:{{ $eleve->sexe === 'M' ? '#185FA5' : '#534AB7' }};
                      display:flex;align-items:center;justify-content:center;
                      font-size:28px;font-weight:600;margin:0 auto 12px;">
            {{ strtoupper(substr($eleve->prenom, 0, 1) . substr($eleve->nom, 0, 1)) }}
          </div>
        @endif
        <div style="font-size:18px; font-weight:600;">{{ $eleve->prenom }} {{ $eleve->nom }}</div>
        <div style="margin-top:6px;">
          <span class="badge badge-info">{{ $eleve->classe->nom }}</span>
          <span class="badge {{ $eleve->sexe === 'M' ? 'badge-info' : 'badge-gray' }}" style="margin-left:4px;">
            {{ $eleve->sexe === 'M' ? 'Garçon' : 'Fille' }}
          </span>
        </div>
      </div>

      <div style="padding:20px; display:flex; flex-direction:column; gap:12px;">
        <div style="display:flex; justify-content:space-between; font-size:13.5px;">
          <span style="color:var(--text-secondary);">Date de naissance</span>
          <span style="font-weight:500;">{{ $eleve->date_naissance->format('d/m/Y') }}</span>
        </div>
        <div style="display:flex; justify-content:space-between; font-size:13.5px;">
          <span style="color:var(--text-secondary);">Parent / Tuteur</span>
          <span style="font-weight:500;">{{ $eleve->nom_parent }}</span>
        </div>
        <div style="display:flex; justify-content:space-between; font-size:13.5px;">
          <span style="color:var(--text-secondary);">Téléphone</span>
          <span style="font-weight:500; font-family:'DM Mono',monospace;">{{ $eleve->telephone_parent }}</span>
        </div>
        <div style="display:flex; justify-content:space-between; font-size:13.5px;">
          <span style="color:var(--text-secondary);">Frais de scolarité</span>
          <span style="font-weight:500;">{{ number_format($eleve->classe->frais_scolarite, 0, ',', ' ') }} FCFA</span>
        </div>
      </div>

      <!-- SITUATION FINANCIERE -->
      <div style="padding:16px 20px; border-top:1px solid var(--border);">
        <div style="font-size:12px; font-weight:600; color:var(--text-muted);
                    text-transform:uppercase; letter-spacing:0.8px; margin-bottom:12px;">
          Situation financière
        </div>
        <div style="display:flex; gap:10px;">
          <div style="flex:1; background:var(--green-light); border-radius:8px; padding:12px; text-align:center;">
            <div style="font-size:16px; font-weight:600; color:var(--green-dark); font-family:'DM Mono',monospace;">
              {{ number_format($eleve->total_paye, 0, ',', ' ') }}
            </div>
            <div style="font-size:11px; color:var(--green-dark); margin-top:2px;">FCFA payés</div>
          </div>
          <div style="flex:1; background:{{ $eleve->reste_a_payer > 0 ? 'var(--red-light)' : 'var(--green-light)' }};
                      border-radius:8px; padding:12px; text-align:center;">
            <div style="font-size:16px; font-weight:600;
                        color:{{ $eleve->reste_a_payer > 0 ? 'var(--red)' : 'var(--green-dark)' }};
                        font-family:'DM Mono',monospace;">
              {{ number_format($eleve->reste_a_payer, 0, ',', ' ') }}
            </div>
            <div style="font-size:11px; color:{{ $eleve->reste_a_payer > 0 ? 'var(--red)' : 'var(--green-dark)' }}; margin-top:2px;">
              FCFA restants
            </div>
          </div>
        </div>

        @if($eleve->reste_a_payer > 0)
          <a href="{{ route('paiements.create') }}?eleve_id={{ $eleve->id }}"
             class="btn-primary" style="width:100%; justify-content:center; margin-top:12px;">
            <i class="ti ti-cash"></i> Enregistrer un paiement
          </a>
        @endif
      </div>
    </div>

    <!-- HISTORIQUE PAIEMENTS -->
    <div class="card">
      <div class="card-header">
        <span class="card-title"><i class="ti ti-receipt"></i> Historique des paiements</span>
        <a href="{{ route('paiements.create') }}" class="btn-outline">
          <i class="ti ti-plus"></i> Ajouter
        </a>
      </div>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Montant</th>
            <th>Référence</th>
            <th>Reçu</th>
          </tr>
        </thead>
        <tbody>
          @forelse($eleve->paiements->sortByDesc('date_paiement') as $paiement)
          <tr>
            <td style="color:var(--text-secondary);">{{ $paiement->date_paiement->format('d/m/Y') }}</td>
            <td style="font-weight:600; color:var(--green-dark); font-family:'DM Mono',monospace;">
              {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
            </td>
            <td style="font-family:'DM Mono',monospace; font-size:12px; color:var(--text-muted);">
              {{ $paiement->reference }}
            </td>
            <td>
              <a href="{{ route('paiements.recu', $paiement) }}" class="btn-outline" target="_blank">
                <i class="ti ti-file-text"></i> PDF
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" style="text-align:center; color:var(--text-muted); padding:20px;">
              Aucun paiement enregistré
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>

  <!-- COLONNE DROITE : NOTES -->
  <div>
    <div class="card">
      <div class="card-header">
        <span class="card-title"><i class="ti ti-pencil"></i> Notes & Moyennes</span>
        <div style="display:flex; align-items:center; gap:8px;">
          <span style="font-size:13px; color:var(--text-secondary);">Moyenne générale :</span>
          <span style="font-size:18px; font-weight:600; color:var(--green-dark); font-family:'DM Mono',monospace;">
            {{ number_format($eleve->moyenne, 2) }}/10
          </span>
        </div>
      </div>

      @php
        $trimestres = $eleve->notes->groupBy('trimestre');
      @endphp

      @if($trimestres->isEmpty())
        <div style="text-align:center; color:var(--text-muted); padding:40px;">
          <i class="ti ti-pencil" style="font-size:32px; display:block; margin-bottom:8px;"></i>
          Aucune note enregistrée
        </div>
      @else
        @foreach($trimestres->sortKeys() as $trimestre => $notes)
        <div style="padding:16px 20px; border-bottom:1px solid var(--border);">
          <div style="font-size:13px; font-weight:600; color:var(--text-secondary);
                      margin-bottom:12px; display:flex; align-items:center; gap:8px;">
            <span class="badge badge-info">Trimestre {{ $trimestre }}</span>
            @php
              $moy = $notes->sum(fn($n) => $n->note * ($n->matiere->coefficient ?? 1))
                   / max($notes->sum(fn($n) => $n->matiere->coefficient ?? 1), 1);
            @endphp
            <span style="font-size:12px; color:var(--text-muted);">
              Moyenne : <strong style="color:var(--green-dark);">{{ number_format($moy, 2) }}/20</strong>
            </span>
          </div>
          <table>
            <thead>
              <tr>
                <th style="padding:8px 0;">Matière</th>
                <th style="padding:8px 0;">Coeff.</th>
                <th style="padding:8px 0;">Note</th>
              </tr>
            </thead>
            <tbody>
              @foreach($notes as $note)
              <tr>
                <td style="padding:8px 0; border:none;">{{ $note->matiere->nom }}</td>
                <td style="padding:8px 0; border:none; color:var(--text-secondary);">
                  × {{ $note->matiere->coefficient }}
                </td>
                <td style="padding:8px 0; border:none;">
                  <span style="font-weight:600; font-family:'DM Mono',monospace;
                               color:{{ $note->note >= 10 ? 'var(--green-dark)' : 'var(--red)' }};">
                    {{ number_format($note->note, 2) }}/20
                  </span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @endforeach
      @endif
    </div>
  </div>

</div>

@endsection