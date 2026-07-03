<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Classe;
use App\Models\Matiere;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Compte administrateur
        User::create([
            'name'     => 'Administrateur',
            'email'    => 'admin@ecole.bf',
            'password' => Hash::make('password'),
        ]);

        // Classes CP1 à CM2 avec frais différents
        $classes = [
            ['nom' => 'CP1', 'effectif_max' => 40, 'frais_scolarite' => 25000],
            ['nom' => 'CP2', 'effectif_max' => 40, 'frais_scolarite' => 25000],
            ['nom' => 'CE1', 'effectif_max' => 40, 'frais_scolarite' => 30000],
            ['nom' => 'CE2', 'effectif_max' => 40, 'frais_scolarite' => 30000],
            ['nom' => 'CM1', 'effectif_max' => 40, 'frais_scolarite' => 35000],
            ['nom' => 'CM2', 'effectif_max' => 40, 'frais_scolarite' => 35000],
        ];

        foreach ($classes as $classe) {
            Classe::create($classe);
        }

        // Matières
        $matieres = [
            ['nom' => 'Mathématiques',    'coefficient' => 3],
            ['nom' => 'Français',          'coefficient' => 3],
            ['nom' => 'Sciences',          'coefficient' => 2],
            ['nom' => 'Histoire-Géo',      'coefficient' => 2],
            ['nom' => 'Éducation Civique', 'coefficient' => 1],
            ['nom' => 'Anglais',           'coefficient' => 1],
        ];

        foreach ($matieres as $matiere) {
            Matiere::create($matiere);
        }
    }
}