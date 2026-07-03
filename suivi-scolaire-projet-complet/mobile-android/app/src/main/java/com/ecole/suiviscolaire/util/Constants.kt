package com.ecole.suiviscolaire.util

object Constants {
    /*
     * URL de base de l'API Laravel.
     *
     * - Émulateur Android Studio : l'hôte de la machine de développement
     *   est accessible via 10.0.2.2 (cas par défaut ci-dessous).
     * - Appareil physique sur le même réseau Wi-Fi : remplacez par
     *   l'adresse IP locale de la machine qui exécute "php artisan serve",
     *   ex: "http://10.17.97.108:8000/api/"
     * - Serveur de production : remplacez par l'URL HTTPS réelle,
     *   ex: "https://scolaritepro.mon-ecole.bf/api/"
     */
    const val BASE_URL = "http://10.231.54.182:8000/api/"

    const val PREFS_NAME = "suivi_scolaire_prefs"
    const val PREF_TOKEN = "token"
    const val PREF_PARENT_NOM = "parent_nom"
    const val PREF_PARENT_PRENOM = "parent_prenom"
    const val PREF_PARENT_EMAIL = "parent_email"
    const val PREF_ELEVE_SELECTIONNE_ID = "eleve_selectionne_id"

    const val EXTRA_PAIEMENT_ID = "extra_paiement_id"
    const val EXTRA_ELEVE_ID = "extra_eleve_id"
}
