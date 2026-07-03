<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Relevé de notes — {{ $eleve->prenom }} {{ $eleve->nom }}</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Arial', sans-serif;
    background: #f4f6f5;
    padding: 30px 20px;
    color: #1a2e26;
  }

  .page {
    background: #fff;
    max-width: 780px;
    margin: 0 auto;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
  }

  /* EN-TÊTE */
  .header {
    background: #0a5c46;
    color: #fff;
    padding: 24px 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .header-left h1 { font-size: 18px; font-weight: 700; }
  .header-left p  { font-size: 12px; color: rgba(255,255,255,0.7); margin-top: 3px; }

  .header-right {
    text-align: right;
    font-size: 12px;
    color: rgba(255,255,255,0.8);
  }

  .releve-title {
    text-align: center;
    padding: 16px;
    font-size: 16px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    background: #E1F5EE;
    color: #0F6E56;
    border-bottom: 2px solid #1D9E75;
  }

  /* INFOS ÉLÈVE */
  .eleve-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    border-bottom: 1px solid #dde8e4;
  }

  .info-item {
    padding: 10px 24px;
    border-right: 1px solid #dde8e4;
    font-size: 13px;
  }

  .info-item:nth-child(even) { border-right: none; }
  .info-item label { color: #8fa89f; font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px; display: block; margin-bottom: 3px; }
  .info-item span  { font-weight: 600; }

  /* TABLEAU NOTES */
  .trimestre-section { padding: 0 24px 20px; margin-top: 20px; }
  .trimestre-title {
    font-size: 13px;
    font-weight: 700;
    color: #0F6E56;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 10px;
    padding-bottom: 6px;
    border-bottom: 2px solid #E1F5EE;
    display: flex;
    justify-content: space-between;
  }

  table { width: 100%; border-collapse: collapse; font-size: 13px; }

  thead th {
    background: #f9fafb;
    padding: 8px 12px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #8fa89f;
    border-bottom: 1px solid #dde8e4;
  }

  tbody td {
    padding: 8px 12px;
    border-bottom: 1px solid #f0f4f2;
    vertical-align: middle;
  }

  tbody tr:last-child td { border-bottom: none; }

  .note-val {
    font-weight: 700;
    font-family: 'Courier New', monospace;
  }

  .note-good { color: #0F6E56; }
  .note-bad  { color: #A32D2D; }

  /* MOYENNE TRIMESTRE */
  .moy-row td {
    background: #f4f6f5;
    font-weight: 700;
    border-top: 2px solid #dde8e4;
  }

  /* RÉCAPITULATIF ANNUEL */
  .recap {
    margin: 0 24px 24px;
    border: 2px solid #0a5c46;
    border-radius: 10px;
    overflow: hidden;
  }

  .recap-title {
    background: #0a5c46;
    color: #fff;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
  }

  .recap-body {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
  }

  .recap-item {
    padding: 14px 16px;
    text-align: center;
    border-right: 1px solid #dde8e4;
  }

  .recap-item:last-child { border-right: none; }
  .recap-item label { font-size: 11px; color: #8fa89f; display: block; margin-bottom: 4px; }
  .recap-item .val  { font-size: 20px; font-weight: 700; font-family: 'Courier New', monospace; }

  /* DÉCISION */
  .decision-admis    { color: #0F6E56; }
  .decision-redouble { color: #A32D2D; }

  /* SIGNATURES */
  .signatures {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 20px;
    padding: 20px 24px;
    border-top: 1px solid #dde8e4;
  }

  .sign-box { text-align: center; }
  .sign-line { border-bottom: 1px solid #1a2e26; margin: 40px auto 6px; width: 120px; }
  .sign-label { font-size: 11px; color: #8fa89f; }

  /* FOOTER */
  .footer {
    background: #f9fafb;
    padding: 10px 24px;
    font-size: 11px;
    color: #8fa89f;
    text-align: center;
    border-top: 1px solid #dde8e4;
  }

  /* BOUTON IMPRESSION */
  .btn-print {
    display: block;
    margin: 20px auto;
    padding: 10px 32px;
    background: #0a5c46;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
  }

  @media print {
    body { background: #fff; padding: 0; }
    .btn-print { display: none; }
    .page { box-shadow: none; border-radius: 0; max-width: 100%; }
  }
</style>
</head>
<body>

<button class="btn-print" onclick="window.print()">
  🖨️ Imprimer le relevé
</button>

<div class="page">

  <!-- EN-TÊTE -->
  <div class="header">
    <div class="header-left">
      <h1>🏫 {{ $ecole?->nom ?? 'ScolaritéPro' }}</h1>
      <p>{{ $ecole?->adresse ?? 'Burkina Faso' }}</p>
      @if($ecole?->telephone)
        <p>Tél : {{ $ecole->telephone }}</p>
      @endif
    </div>
    <div class="header-right">
      <div>Année scolaire : <strong>2025 – 2026</strong></div>
      <div style="margin-top:4px;">Classe : <strong>{{ $eleve->classe->nom }}</strong></div>
      <div style="margin-top:4px;">Imprimé le : {{ now()->format('d/m/Y') }}</div>
    </div>
  </div>

  <!-- TITRE -->
  <div class="releve-title">Relevé de Notes</div>

  <!-- INFOS ÉLÈVE -->
  <div class="eleve-info">
    <div class="info-item">
      <label>Nom & Prénom</label>
      <span>{{ $eleve->nom }} {{ $eleve->prenom }}</span>
    </div>
    <div class="info-item">
      <label>Classe</label>
      <span>{{ $eleve->classe->nom }}</span>
    </div>
    <div class="info-item">
      <label>Date de naissance</label>
      <span>{{ \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') }}</span>
    </div>
    <div class="info-item">
      <label>Sexe</label>
      <span>{{ $eleve->sexe === 'M' ? 'Masculin' : 'Féminin' }}</span>
    </div>
  </div>

  <!-- NOTES PAR TRIMESTRE -->
  @foreach($trimestres as $num => $data)
  <div class="trimestre-section">
    <div class="trimestre-title">
      <span>Trimestre {{ $num }}</span>
      @if($data['moyenne'] !== null)
        <span>Moyenne : {{ number_format($data['moyenne'], 2) }}/10</span>
      @else
        <span style="color:#8fa89f;">Pas de notes</span>
      @endif
    </div>

    @if($data['notes']->isNotEmpty())
    <table>
      <thead>
        <tr>
          <th>Matière</th>
          <th>Coefficient</th>
          <th>Note obtenue</th>
          <th>Note sur</th>
          <th>Note /10</th>
          <th>Appréciation</th>
        </tr>
      </thead>
      <tbody>
        @foreach($data['notes'] as $note)
        @php
          $note_sur = $note->matiere->note_sur ?? 20;
          $note_10  = min(($note->note / $note_sur) * 10, 10);
        @endphp
        <tr>
          <td>{{ $note->matiere->nom ?? '—' }}</td>
          <td style="text-align:center;">{{ $note->matiere->coefficient ?? 1 }}</td>
          <td style="text-align:center;">
            <span class="note-val {{ $note->note >= ($note_sur / 2) ? 'note-good' : 'note-bad' }}">
              {{ number_format($note->note, 2) }}
            </span>
          </td>
          <td style="text-align:center; color:#8fa89f;">/{{ $note_sur }}</td>
          <td style="text-align:center;">
            <span class="note-val {{ $note_10 >= 5 ? 'note-good' : 'note-bad' }}">
              {{ number_format($note_10, 2) }}/10
            </span>
          </td>
          <td>
            @if($note_10 >= 8) Très bien
            @elseif($note_10 >= 7) Bien
            @elseif($note_10 >= 6) Assez bien
            @elseif($note_10 >= 5) Passable
            @else Insuffisant
            @endif
          </td>
        </tr>
        @endforeach

        <!-- Ligne moyenne du trimestre -->
        @if($data['moyenne'] !== null)
        <tr class="moy-row">
          <td colspan="4" style="text-align:right; padding-right:12px;">
            Moyenne du trimestre {{ $num }} :
          </td>
          <td style="text-align:center;">
            <span class="note-val {{ $data['moyenne'] >= 5 ? 'note-good' : 'note-bad' }}">
              {{ number_format($data['moyenne'], 2) }}/10
            </span>
          </td>
          <td>
            @php $m = $data['moyenne']; @endphp
            @if($m >= 8) Très bien
            @elseif($m >= 7) Bien
            @elseif($m >= 6) Assez bien
            @elseif($m >= 5) Passable
            @else Insuffisant
            @endif
          </td>
        </tr>
        @endif
      </tbody>
    </table>
    @else
      <div style="text-align:center; color:#8fa89f; padding:16px; font-size:13px;">
        Aucune note pour ce trimestre
      </div>
    @endif
  </div>
  @endforeach

  <!-- RÉCAPITULATIF ANNUEL -->
  <div class="recap">
    <div class="recap-title">📊 Récapitulatif Annuel</div>
    <div class="recap-body">
      <div class="recap-item">
        <label>Moy. T1</label>
        <div class="val {{ ($trimestres['1']['moyenne'] ?? 0) >= 5 ? 'note-good' : 'note-bad' }}">
          {{ $trimestres['1']['moyenne'] !== null ? number_format($trimestres['1']['moyenne'], 2) : '—' }}
        </div>
      </div>
      <div class="recap-item">
        <label>Moy. T2</label>
        <div class="val {{ ($trimestres['2']['moyenne'] ?? 0) >= 5 ? 'note-good' : 'note-bad' }}">
          {{ $trimestres['2']['moyenne'] !== null ? number_format($trimestres['2']['moyenne'], 2) : '—' }}
        </div>
      </div>
      <div class="recap-item">
        <label>Moy. T3</label>
        <div class="val {{ ($trimestres['3']['moyenne'] ?? 0) >= 5 ? 'note-good' : 'note-bad' }}">
          {{ $trimestres['3']['moyenne'] !== null ? number_format($trimestres['3']['moyenne'], 2) : '—' }}
        </div>
      </div>
      <div class="recap-item">
        <label>Moy. Annuelle</label>
        <div class="val {{ $moyenne_annuelle >= 5 ? 'note-good' : 'note-bad' }}">
          {{ number_format($moyenne_annuelle, 2) }}/10
        </div>
      </div>
    </div>

    <!-- DÉCISION -->
    <div style="text-align:center; padding:12px; border-top:1px solid #dde8e4;
                font-size:15px; font-weight:700;">
      Décision du conseil de classe :
      <span class="{{ $decision === 'Admis(e)' ? 'decision-admis' : 'decision-redouble' }}"
            style="margin-left:8px; font-size:18px;">
        {{ $decision === 'Admis(e)' ? '✅' : '❌' }} {{ $decision }}
      </span>
    </div>
  </div>

  <!-- SIGNATURES -->
  <div class="signatures">
    <div class="sign-box">
      <div class="sign-line"></div>
      <div class="sign-label">Signature de l'enseignant</div>
    </div>
    <div class="sign-box">
      <div class="sign-line"></div>
      <div class="sign-label">Cachet de l'école</div>
    </div>
    <div class="sign-box">
      <div class="sign-line"></div>
      <div class="sign-label">Signature du parent</div>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="footer">
    Relevé généré le {{ now()->format('d/m/Y à H:i') }} —
    {{ $ecole?->nom ?? 'ScolaritéPro' }}
  </div>

</div>

<button class="btn-print" onclick="window.print()">
  🖨️ Imprimer le relevé
</button>

</body>
</html>