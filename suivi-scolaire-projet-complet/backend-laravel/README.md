# Backend — Gestion Scolarité (Laravel)

API REST + espace web Admin/Enseignant pour l'application **Suivi Scolaire Parent-Enfant**.

Voir le [README principal](../README.md) pour la présentation générale du projet, les membres du groupe et les fonctionnalités.

## Installation

```bash
composer install
```

Copiez le fichier d'environnement : `copy .env.example .env` (Windows cmd) ou `cp .env.example .env` (Linux/macOS/PowerShell : `Copy-Item .env.example .env`).

```bash
php artisan key:generate
php artisan migrate
php artisan serve
```

La base `database/database.sqlite` fournie contient déjà des données de démonstration. Pour repartir d'une base vide :

```bash
php artisan migrate:fresh
```

## Modules ajoutés pour ce projet (espace mobile parent)

| Élément | Fichier(s) |
|---|---|
| Authentification parent (Sanctum) | `app/Http/Controllers/Api/AuthController.php` |
| Modèle compte parent | `app/Models/ParentUser.php` |
| Tableau de bord élève | `app/Http/Controllers/Api/DashboardController.php` |
| Notes par matière | `app/Http/Controllers/Api/NoteController.php` |
| Paiements + reçu | `app/Http/Controllers/Api/PaiementController.php` |
| Absences | `app/Http/Controllers/Api/AbsenceController.php`, `app/Models/Absence.php` |
| Annonces / notifications | `app/Http/Controllers/Api/AnnonceController.php`, `app/Models/Annonce.php` |
| Routes API | `routes/api.php` |
| Gestion des comptes parents (admin, web) | `app/Http/Controllers/ParentController.php` |
| Gestion des absences (admin/enseignant, web) | `app/Http/Controllers/AbsenceController.php`, `EnseignantAbsenceController.php` |
| Gestion des annonces (admin, web) | `app/Http/Controllers/AnnonceController.php` |

## Authentification API (Sanctum)

Le parent se connecte via `POST /api/login` (email + mot de passe) et reçoit un **token d'accès personnel**. Ce token doit être transmis dans l'en-tête `Authorization: Bearer {token}` pour toutes les requêtes suivantes. Un seul appareil est connecté à la fois par compte (les anciens tokens sont révoqués à chaque nouvelle connexion).

Les comptes parents (table `parents`) sont entièrement séparés des comptes `users` (admin/enseignant) : un parent ne peut jamais se connecter à l'espace web, et un admin/enseignant ne peut jamais obtenir de token API.

## Création d'un compte parent

Les comptes parents sont créés par l'administrateur depuis l'espace web : **Comptes parents → Nouveau compte parent**. L'administrateur associe un ou plusieurs élèves au compte, puis transmet les identifiants au parent.

## Tester l'API manuellement

```bash
# Connexion
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"email":"parent.test@ecole.bf","password":"password123"}'

# Utiliser le token reçu pour appeler un endpoint protégé
curl http://127.0.0.1:8000/api/eleves \
  -H "Accept: application/json" -H "Authorization: Bearer {TOKEN}"
```
