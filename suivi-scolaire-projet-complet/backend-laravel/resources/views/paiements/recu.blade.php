<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Reçu {{ $paiement->reference }}</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Arial', sans-serif; background: #f4f6f5; padding: 40px 20px; }
.recu {
  background: #fff; width: 100%; max-width: 600px; border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden;
  margin: 0 auto;
}
  .recu-header {
    background: #0a5c46; color: #fff; padding: 28px 32px;
    display: flex; justify-content: space-between; align-items: center;
  }
  .recu-header h1 { font-size: 22px; font-weight: 700; }
  .recu-header p  { font-size: 12px; color: rgba(255,255,255,0.7); margin-top: 4px; }
  .recu-ref {
    background: #1D9E75; padding: 6px 14px;
    border-radius: 6px; font-size: 13px; font-weight: 600;
    font-family: 'Courier New', monospace;
  }
  .recu-body { padding: 28px 32px; }
  .recu-title {
    font-size: 16px; font-weight: 700; color: #0a5c46;
    text-align: center; text-transform: uppercase;
    letter-spacing: 1px; margin-bottom: 24px;
    padding-bottom: 16px; border-bottom: 2px solid #E1F5EE;
  }
  .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
  .info-item label { font-size: 11px; color: #8fa89f; text-transform: uppercase;
                     letter-spacing: 0.8px; display: block; margin-bottom: 4px; }
  .info-item span  { font-size: 14px; font-weight: 600; color: #1a2e26; }
  .montant-box {
    background: #E1F5EE; border-radius: 10px; padding: 20px;
    text-align: center; margin-bottom: 24px;
  }
  .montant-box .label { font-size: 12px; color: #5a7068; margin-bottom: 6px; }
  .montant-box .montant {
    font-size: 32px; font-weight: 700; color: #0a5c46;
    font-family: 'Courier New', monospace;
  }
  .situation {
    border: 1px solid #dde8e4; border-radius: 8px; overflow: hidden; margin-bottom: 24px;
  }
  .situation-row {
    display: flex; justify-content: space-between; padding: 10px 16px;
    font-size: 13px; border-bottom: 1px solid #f0f4f2;
  }
  .situation-row:last-child { border-bottom: none; }
  .situation-row span:first-child { color: #5a7068; }
  .situation-row span:last-child  { font-weight: 600; font-family: 'Courier New', monospace; }
  .recu-footer {
    border-top: 2px dashed #dde8e4; padding: 20px 32px;
    display: flex; justify-content: space-between; align-items: center;
    background: #f9fafb;
  }
  .signature-box { text-align: center; }
  .signature-box .line {
    width: 140px; border-bottom: 1px solid #1a2e26;
    margin: 30px auto 6px;
  }
  .signature-box p { font-size: 11px; color: #8fa89f; }
  .btn-print {
    display: block; margin: 20px auto; padding: 10px 28px;
    background: #0a5c46; color: #fff; border: none;
    border-radius: 8px; font-size: 14px; font-weight: 600;
    cursor: pointer; font-family: inherit;
  }
  @media print {
    body { background: #fff; padding: 0; }
    .btn-print { display: none; }
    .recu { box-shadow: none; border-radius: 0; width: 100%; }
  }
</style>
</head>
<body>
<div>
  <div class="recu">
    <!-- HEADER -->
    <div class="recu-header">
      <div>
        @php $ecole = App\Models\Ecole::active(); @endphp
<h1>🏫 {{ $ecole?->nom ?? 'ScolaritéPro' }}</h1>
<p>{{ $ecole?->adresse ?? '' }}</p>
<p>Tél : {{ $ecole?->telephone ?? '' }}</p>
      </div>
      <div class="recu-ref">{{ $paiement->reference }}</div>
    </div>

    <!-- BODY -->
    <div class="recu-body">
      <div class="recu-title">Reçu de paiement de frais de scolarité</div>

      <!-- INFOS ÉLÈVE -->
      <div class="info-grid">
        <div class="info-item">
          <label>Élève</label>
          <span>{{ $paiement->eleve->prenom }} {{ $paiement->eleve->nom }}</span>
        </div>
        <div class="info-item">
          <label>Classe</label>
          <span>{{ $paiement->eleve->classe->nom }}</span>
        </div>
        <div class="info-item">
          <label>Parent / Tuteur</label>
          <span>{{ $paiement->eleve->nom_parent }}</span>
        </div>
        <div class="info-item">
          <label>Téléphone</label>
          <span>{{ $paiement->eleve->telephone_parent }}</span>
        </div>
        <div class="info-item">
          <label>Date du paiement</label>
          <span>{{ $paiement->date_paiement->format('d/m/Y') }}</span>
        </div>
        <div class="info-item">
          <label>Année scolaire</label>
          <span>2025 – 2026</span>
        </div>
      </div>

      <!-- MONTANT -->
      <div class="montant-box">
        <div class="label">Montant versé</div>
        <div class="montant">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</div>
      </div>

      <!-- SITUATION -->
      <div class="situation">
        <div class="situation-row">
          <span>Frais de scolarité totaux</span>
          <span>{{ number_format($paiement->eleve->classe->frais_scolarite, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="situation-row">
          <span>Total payé (cumulé)</span>
          <span style="color:#0F6E56;">{{ number_format($paiement->eleve->total_paye, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="situation-row">
          <span>Reste à payer</span>
          <span style="color:{{ $paiement->eleve->reste_a_payer > 0 ? '#A32D2D' : '#0F6E56' }};">
            {{ number_format($paiement->eleve->reste_a_payer, 0, ',', ' ') }} FCFA
          </span>
        </div>
      </div>

      @if($paiement->observation)
      <div style="background:#f9fafb; border-radius:8px; padding:12px 16px;
                  font-size:13px; color:#5a7068; margin-bottom:16px;">
        <strong>Observation :</strong> {{ $paiement->observation }}
      </div>
      @endif
    </div>

    <!-- FOOTER SIGNATURE -->
    <div class="recu-footer">
      <div class="signature-box">
        <div class="line"></div>
        <p>Signature du parent</p>
      </div>
      <div style="text-align:center; font-size:11px; color:#8fa89f;">
        <div>Reçu généré le {{ now()->format('d/m/Y à H:i') }}</div>
        <div style="margin-top:4px;">Réf : {{ $paiement->reference }}</div>
      </div>
      <div class="signature-box">
        <div class="line"></div>
        <p>Cachet de l'école</p>
      </div>
    </div>
  </div>

  <button class="btn-print" onclick="window.print()">🖨️ Imprimer le reçu</button>
</div>
</body>
</html>