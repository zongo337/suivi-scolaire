# Suivi Scolaire — School Connect

Application mobile Android permettant aux parents d'élèves de suivre la scolarité de leurs enfants (CP1 à CM2) : notes, paiements, absences, annonces — en temps réel, depuis leur téléphone.

Projet réalisé dans le cadre du cours **Développement Mobile (L3 Informatique SEA)**, Université Joseph KI-ZERBO (UJKZ).

---

## 1. Présentation du projet

L'objectif est de concevoir une application mobile Android pour les parents, consommant une API REST développée en Laravel. L'API Laravel gérait déjà l'espace **administrateur** et **enseignant** (web) ; ce projet y ajoute :

- un système de comptes **parents** (authentification par token Bearer, via Laravel Sanctum) ;
- une **API REST** complète (tableau de bord, notes, paiements, absences, annonces) ;
- l'**application mobile Android** (Kotlin) qui consomme cette API ;
- les pages d'administration pour créer les comptes parents et gérer absences/annonces.

---

## 2. Membres du groupe

| Nom | Rôle |
|---|---|
| ZONGO MOUMOUNI | |
| SAWADOGO | MUSSOUNA |

---

## 3. Architecture du projet

```
.
├── backend-laravel/      Backend Laravel 11 (API REST + espace admin/enseignant web)
└── mobile-android/       Application mobile Android (Kotlin) — espace parent
```

```
┌─────────────────────┐        HTTP / REST (JSON)         ┌──────────────────────┐
│   Application        │ ──────────────────────────────▶  │   API Laravel         │
│   Android (Kotlin)   │ ◀──────────────────────────────  │   (Sanctum + SQLite)  │
│   Espace PARENT      │                                   │                       │
└─────────────────────┘                                   └──────────┬───────────┘
                                                                      │
                                                           ┌──────────▼───────────┐
                                                           │  Espace web Admin /   │
                                                           │  Enseignant (Blade)   │
                                                           └──────────────────────┘
```

---

## 4. Fonctionnalités

### Application mobile — Espace Parent

- **Authentification** : connexion par email/mot de passe, déconnexion, modification du mot de passe.
- **Tableau de bord** : photo de l'élève, nom, classe, moyenne générale, rang dans la classe, dernières notes obtenues.
- **Notes** : liste des matières, notes par matière, moyennes trimestrielles.
- **Paiements** : historique des versements, total payé, reste à payer, reçu de paiement (HTML imprimable).
- **Absences** : liste des absences avec motif et statut justifiée/non justifiée.
- **Annonces** : annonces de l'école et notifications importantes (réunions, examens, échéances) — 5ème onglet de navigation.
- **Sélecteur d'enfant** : si un parent a plusieurs enfants, un sélecteur horizontal permet de basculer entre eux.
- **Profil** : consultation des informations du compte, modification du mot de passe, déconnexion.

### Espace Admin / Enseignant (web)

- Gestion des élèves, classes, matières, notes, paiements, écoles, enseignants.
- **Nouveau** : gestion des comptes parents (création + association à un ou plusieurs élèves).
- **Nouveau** : déclaration des absences (admin : toutes classes ; enseignant : sa classe uniquement).
- **Nouveau** : publication des annonces et notifications (ciblées sur une classe ou toute l'école).

---

## 5. Technologies utilisées

| Composant | Technologie |
|---|---|
| Backend | Laravel 11, PHP 8.3+ |
| Authentification API | Laravel Sanctum (tokens d'accès personnels) |
| Base de données | SQLite (développement) |
| Application mobile | Kotlin, Android SDK (minSdk 24, targetSdk 34) |
| Réseau mobile | Retrofit2 + OkHttp3 + Gson |
| Navigation | BottomNavigationView (5 onglets) |
| Listes | RecyclerView avec ListAdapter / DiffUtil |
| Asynchrone | Coroutines Kotlin + lifecycleScope |
| Images | Chargeur personnalisé (ImageLoader.kt) |

---

## 6. Installation

### 6.1 Backend Laravel

```bash
cd backend-laravel
composer install
```

Copiez le fichier d'environnement :

```bash
# Windows (cmd)
copy .env.example .env

# Windows (PowerShell)
Copy-Item .env.example .env

# Linux / macOS
cp .env.example .env
```

Ouvrez `.env` et configurez l'URL de l'application avec l'IP de votre machine :

```env
APP_URL=http://<IP_DE_VOTRE_MACHINE>:8000
DB_CONNECTION=sqlite
```

> Pour trouver votre IP locale : `ipconfig` (Windows) ou `ifconfig` (Linux/macOS)

Puis exécutez :

```bash
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve --host=0.0.0.0 --port=8000
```

>  Sur Windows, `php artisan storage:link` doit être exécuté en tant qu'**Administrateur** pour créer le lien symbolique correctement.

> Le fichier `database/database.sqlite` fourni contient déjà des données de démonstration complètes.

**Identifiants de démonstration :**

| Rôle | Email | Mot de passe |
|---|---|---|
| Admin (web) | `admin@ecole.bf` | `admin123` |
| Parent (mobile) | `parent.test@ecole.bf` | `123456` |

### 6.2 Application mobile Android

1. Ouvrir le dossier `mobile-android/` dans **Android Studio** (File → Open).
2. Laisser Android Studio synchroniser Gradle.
3. Configurer l'URL de l'API dans :
   `app/src/main/java/com/ecole/suiviscolaire/util/Constants.kt`

   ```kotlin
   // Téléphone physique sur le même réseau Wi-Fi :
   const val BASE_URL = "http://<IP_DE_VOTRE_MACHINE>:8000/api/"

   // Émulateur Android Studio :
   const val BASE_URL = "http://10.0.2.2:8000/api/"
   ```

4. Ajouter la même IP dans `app/src/main/res/xml/network_security_config.xml` :

   ```xml
   <domain includeSubdomains="false">VOTRE_IP</domain>
   ```

5. Lancer l'application sur un émulateur ou un appareil connecté (▶ Run).
6. Se connecter avec le compte parent de démonstration.

>  L'API tourne en HTTP en développement. Le fichier `network_security_config.xml` n'autorise le trafic non chiffré que vers les adresses de développement configurées.

---

## 7. Navigation mobile

L'application comporte **5 onglets** dans la barre de navigation inférieure :

| # | Onglet | Description |
|---|---|---|
| 1 | **Accueil** | Tableau de bord : photo, moyenne, rang, dernières notes |
| 2 | **Notes** | Notes par matière et trimestre |
| 3 | **Paiements** | Historique des paiements et reçus |
| 4 | **Absences** | Liste des absences de l'élève |
| 5 | **Annonces** | Annonces et notifications de l'école |

Le menu **⋮** (trois points) en haut à droite permet de **modifier le mot de passe** ou de se **déconnecter**.

---

## 8. Structure de l'API REST

| Méthode | Route | Auth | Description |
|---|---|---|---|
| POST | `/api/login` | ❌ | Connexion du parent, retourne un token |
| POST | `/api/logout` | ✅ | Déconnexion (révoque le token) |
| PUT | `/api/password` | ✅ | Modification du mot de passe |
| GET | `/api/eleves` | ✅ | Liste des enfants du parent connecté |
| GET | `/api/eleves/{id}/dashboard` | ✅ | Tableau de bord d'un élève |
| GET | `/api/eleves/{id}/notes` | ✅ | Notes par matière + moyennes |
| GET | `/api/eleves/{id}/paiements` | ✅ | Historique des paiements |
| GET | `/api/eleves/{id}/paiements/{id}/recu` | ✅ | Reçu de paiement (HTML) |
| GET | `/api/eleves/{id}/absences` | ✅ | Liste des absences |
| GET | `/api/annonces` | ✅ | Annonces et notifications |

Toutes les routes protégées (✅) nécessitent l'en-tête HTTP :
```
Authorization: Bearer {token}
```

---

## 9. Dépôt GitHub

Lien du dépôt : **https://github.com/zongo337/suivi-scolaire**


---

## 10. Remarques techniques

- Le lien symbolique `public/storage` doit être créé avec `php artisan storage:link` (en administrateur sur Windows) pour que les photos des élèves s'affichent correctement.
- La variable `APP_URL` dans `.env` doit correspondre à l'IP réelle de la machine serveur pour que les URLs des photos générées par `asset()` soient accessibles depuis le téléphone.
- Sur Windows, la politique de contrôle d'application peut bloquer `php_mbstring.dll`. Solution : débloquer la DLL via `Unblock-File` en PowerShell ou via les propriétés du fichier dans l'explorateur.
