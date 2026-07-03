<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Classe
        $classeId = DB::table('classes')->insertGetId([
            'nom'             => 'CM2 A',
            'effectif_max'    => 40,
            'frais_scolarite' => 25000,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Matières
        $matieres = [
            ['nom' => 'Mathématiques', 'note_sur' => 10],
            ['nom' => 'Français',      'note_sur' => 10],
            ['nom' => 'Sciences',      'note_sur' => 10],
            ['nom' => 'Histoire-Géo',  'note_sur' => 20],
            ['nom' => 'Anglais',       'note_sur' => 20],
        ];
        $matiereIds = [];
        foreach ($matieres as $m) {
            $matiereIds[] = DB::table('matieres')->insertGetId([
                'nom'         => $m['nom'],
                'coefficient' => 2,
                'note_sur'    => $m['note_sur'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        // 5 Élèves
        $eleves = [
            ['nom' => 'SAWADOGO', 'prenom' => 'Moussa',  'sexe' => 'M', 'nom_parent' => 'SAWADOGO Alassane', 'telephone_parent' => '70000001'],
            ['nom' => 'OUEDRAOGO','prenom' => 'Fatima',  'sexe' => 'F', 'nom_parent' => 'OUEDRAOGO Salif',   'telephone_parent' => '70000002'],
            ['nom' => 'TRAORE',   'prenom' => 'Ibrahim', 'sexe' => 'M', 'nom_parent' => 'TRAORE Boubacar',   'telephone_parent' => '70000003'],
            ['nom' => 'KABORE',   'prenom' => 'Aminata', 'sexe' => 'F', 'nom_parent' => 'KABORE Seydou',     'telephone_parent' => '70000004'],
            ['nom' => 'ZONGO',    'prenom' => 'Rasmane', 'sexe' => 'M', 'nom_parent' => 'ZONGO Moumouni',    'telephone_parent' => '70000005'],
        ];

        $eleveIds = [];
foreach ($eleves as $i => $e) {
    $eleveIds[] = DB::table('eleves')->insertGetId(array_merge($e, [
        'date_naissance' => '2015-03-10',
        'classe_id'      => $classeId,
        'created_at'     => now(),
        'updated_at'     => now(),
    ]));
}

        // Parent de test lié aux 5 élèves
        $parentId = DB::table('parents')->insertGetId([
            'nom'        => 'Test',
            'prenom'     => 'Parent',
            'email'      => 'parent.test@ecole.bf',
            'telephone'  => '70000000',
            'password'   => Hash::make('parent123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($eleveIds as $eleveId) {
            DB::table('eleve_parent')->insert([
                'eleve_id'   => $eleveId,
                'parent_id'  => $parentId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Notes (3 trimestres) — respecte note_sur de chaque matière
        // matieres[0,1,2] sur 10 ; matieres[3,4] sur 20
        $notesParEleve = [
            // Math, Fr, Sc, Hist, Ang
            [8, 7, 6, 16, 14],
            [9, 9, 8, 14, 16],
            [5, 6, 4, 12, 10],
            [8, 8, 7, 17, 18],
            [7, 6, 7, 11, 15],
        ];

        foreach ($eleveIds as $ei => $eleveId) {
            foreach ([1, 2, 3] as $trimestre) {
                foreach ($matiereIds as $mi => $matiereId) {
                    $noteMax  = $matieres[$mi]['note_sur'];
                    $noteBase = $notesParEleve[$ei][$mi];
                    $note     = min($noteBase + ($trimestre - 1), $noteMax);
                    DB::table('notes')->insert([
                        'eleve_id'       => $eleveId,
                        'matiere_id'     => $matiereId,
                        'note'           => $note,
                        'trimestre'      => (string)$trimestre,
                        'annee_scolaire' => '2025-2026',
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }
            }
        }

        // Paiements (Moussa et Fatima : payé ; Ibrahim : partiel ; Aminata : rien ; Rasmane : payé)
        $paiements = [
            ['eleve_id' => $eleveIds[0], 'montant' => 25000, 'date_paiement' => '2025-10-01', 'reference' => 'PAY001', 'observation' => 'Payé complet'],
            ['eleve_id' => $eleveIds[0], 'montant' => 25000, 'date_paiement' => '2026-01-10', 'reference' => 'PAY002', 'observation' => 'Payé complet'],
            ['eleve_id' => $eleveIds[1], 'montant' => 25000, 'date_paiement' => '2025-10-05', 'reference' => 'PAY003', 'observation' => 'Payé complet'],
            ['eleve_id' => $eleveIds[2], 'montant' => 12500, 'date_paiement' => '2025-11-01', 'reference' => 'PAY004', 'observation' => 'Paiement partiel'],
            ['eleve_id' => $eleveIds[4], 'montant' => 25000, 'date_paiement' => '2025-10-15', 'reference' => 'PAY005', 'observation' => 'Payé complet'],
        ];

        foreach ($paiements as $p) {
            DB::table('paiements')->insert(array_merge($p, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Absences (justifiées et non justifiées)
        $absences = [
            ['eleve_id' => $eleveIds[0], 'date_absence' => '2025-10-10', 'motif' => 'Maladie',           'justifiee' => true],
            ['eleve_id' => $eleveIds[0], 'date_absence' => '2025-11-05', 'motif' => null,                 'justifiee' => false],
            ['eleve_id' => $eleveIds[1], 'date_absence' => '2025-12-03', 'motif' => 'Cérémonie famille',  'justifiee' => true],
            ['eleve_id' => $eleveIds[2], 'date_absence' => '2026-01-15', 'motif' => null,                 'justifiee' => false],
            ['eleve_id' => $eleveIds[2], 'date_absence' => '2026-02-20', 'motif' => null,                 'justifiee' => false],
            ['eleve_id' => $eleveIds[3], 'date_absence' => '2025-10-22', 'motif' => 'Consultation',       'justifiee' => true],
            ['eleve_id' => $eleveIds[4], 'date_absence' => '2026-03-10', 'motif' => null,                 'justifiee' => false],
        ];

        foreach ($absences as $a) {
            DB::table('absences')->insert(array_merge($a, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Annonces
        $annonces = [
            ['titre' => 'Réunion parents d\'élèves',    'contenu' => 'Une réunion est prévue le 15 juillet 2026 à 9h dans la salle polyvalente. Votre présence est obligatoire.',              'type' => 'annonce',       'classe_id' => null],
            ['titre' => 'Examens de fin d\'année',      'contenu' => 'Les examens de fin d\'année se dérouleront du 20 au 30 juin 2026. Les élèves doivent se munir de leur carte scolaire.', 'type' => 'notification',  'classe_id' => null],
            ['titre' => 'Paiement frais scolaires',     'contenu' => 'Rappel : le dernier délai pour le paiement des frais du 3ème trimestre est fixé au 30 juin 2026.',                      'type' => 'notification',  'classe_id' => $classeId],
            ['titre' => 'Sortie pédagogique CM2',       'contenu' => 'Une sortie pédagogique est organisée pour les élèves de CM2 le 10 juillet 2026. Frais de participation : 2000 FCFA.',   'type' => 'annonce',       'classe_id' => $classeId],
            ['titre' => 'Résultats du 2ème trimestre',  'contenu' => 'Les bulletins du 2ème trimestre sont disponibles. Vous pouvez les consulter directement dans l\'application.',           'type' => 'notification',  'classe_id' => null],
        ];

        foreach ($annonces as $a) {
            DB::table('annonces')->insert(array_merge($a, [
                'date_publication' => now(),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]));
        }
    }
}