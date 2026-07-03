# Application mobile — Espace Parent (Android / Kotlin)

Application Android native (Kotlin) consommant l'API REST du backend Laravel (`../backend-laravel`). Voir le [README principal](../README.md) pour la présentation générale du projet.

## Ouvrir le projet

1. Démarrer le backend (`cd ../backend-laravel && php artisan serve`).
2. Ouvrir **ce dossier** (`mobile-android/`) dans Android Studio : *File → Open*.
3. Laisser Gradle se synchroniser.
4. Lancer sur un émulateur (▶ Run).

> Si Android Studio signale que le wrapper Gradle (`gradle-wrapper.jar`) est manquant, utilisez *File → Sync Project with Gradle Files* ou laissez Android Studio le régénérer automatiquement — c'est normal pour un projet transmis hors dépôt Git complet.

## Configuration de l'URL de l'API

Fichier : `app/src/main/java/com/ecole/suiviscolaire/util/Constants.kt`

```kotlin
const val BASE_URL = "http://10.0.2.2:8000/api/"
```

- **Émulateur Android Studio** : ne rien changer, `10.0.2.2` pointe vers la machine hôte.
- **Téléphone physique** (même réseau Wi-Fi) : remplacer par l'IP locale de votre PC (ex. `192.168.1.20`) et l'ajouter dans `app/src/main/res/xml/network_security_config.xml`.

## Architecture du code

```
data/
 ├── model/        Classes de données (DTO) miroir des réponses JSON de l'API
 ├── local/        SessionManager (token, enfant sélectionné — SharedPreferences)
 ├── remote/       ApiService (Retrofit), AuthInterceptor, RetrofitClient
 └── repository/   ParentRepository — point d'entrée unique vers l'API, gestion des erreurs
ui/
 ├── login/        Écran de connexion
 ├── main/         Écran principal (navigation par onglets + sélecteur d'enfant)
 ├── dashboard/    Tableau de bord élève
 ├── notes/        Notes par matière (RecyclerView imbriqués)
 ├── paiements/    Historique des paiements + écran du reçu (WebView + impression)
 ├── absences/     Liste des absences
 ├── annonces/     Annonces / notifications (avec filtre)
 ├── profile/      Profil parent + modification du mot de passe
 └── eleveselector/ Sélecteur horizontal d'enfant (si plusieurs enfants)
util/             Constantes, ApiResult, ImageLoader
```

## Utilisation de RecyclerView

Toutes les listes de l'application utilisent `RecyclerView` avec `ListAdapter` + `DiffUtil` (mises à jour efficaces, sans `notifyDataSetChanged` systématique) :

- **Notes** : un `RecyclerView` principal (une carte par matière) contenant, pour chaque carte, un **second `RecyclerView` imbriqué** listant les notes de cette matière par trimestre (`MatiereAdapter` → `NoteParTrimestreAdapter`).
- **Paiements** : historique des versements (`PaiementAdapter`), avec bouton vers le reçu.
- **Absences** : liste des absences (`AbsenceAdapter`).
- **Annonces** : liste des annonces/notifications (`AnnonceAdapter`).
- **Sélecteur d'enfant** : `RecyclerView` horizontal (`EleveSelectorAdapter`), visible uniquement si le parent a plusieurs enfants.
- **Tableau de bord** : `RecyclerView` des dernières notes (`DernieresNotesAdapter`).

## Reçu de paiement (PDF)

Le serveur renvoie le reçu sous forme de **page HTML** stylée (réutilise le design du reçu de l'espace admin). L'application l'affiche dans une `WebView` et propose un bouton **« Imprimer / PDF »** qui utilise le `PrintManager` natif d'Android (`WebView.createPrintDocumentAdapter`) — aucune génération de PDF côté serveur n'est nécessaire.

## Dépendances principales

- Retrofit2 + Gson + OkHttp (réseau)
- Coroutines Kotlin (asynchrone)
- AndroidX (RecyclerView, Fragment, SwipeRefreshLayout, Lifecycle)
- Material Components (BottomNavigationView, TextInputLayout)

## Comptes de test

Voir le [README principal](../README.md#installation) pour les identifiants de démonstration (compte parent prêt à l'emploi avec la base SQLite fournie).
