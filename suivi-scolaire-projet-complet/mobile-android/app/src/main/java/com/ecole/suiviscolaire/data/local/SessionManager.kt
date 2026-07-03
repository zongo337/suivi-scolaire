package com.ecole.suiviscolaire.data.local

import android.content.Context
import com.ecole.suiviscolaire.data.model.Eleve
import com.ecole.suiviscolaire.data.model.LoginResponse
import com.ecole.suiviscolaire.util.Constants
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken

/*
 * Stocke localement la session du parent connecté : token Sanctum,
 * informations du parent, liste des enfants et enfant actuellement
 * sélectionné dans l'application.
 */
class SessionManager(context: Context) {

    private val prefs = context.getSharedPreferences(Constants.PREFS_NAME, Context.MODE_PRIVATE)
    private val gson = Gson()

    fun saveLogin(response: LoginResponse) {
        prefs.edit()
            .putString(Constants.PREF_TOKEN, response.token)
            .putString(Constants.PREF_PARENT_NOM, response.parent.nom)
            .putString(Constants.PREF_PARENT_PRENOM, response.parent.prenom)
            .putString(Constants.PREF_PARENT_EMAIL, response.parent.email)
            .putString(KEY_ELEVES, gson.toJson(response.eleves))
            .apply()

        // Sélectionne automatiquement le premier enfant par défaut
        if (response.eleves.isNotEmpty()) {
            setEleveSelectionneId(response.eleves.first().id)
        }
    }

    fun getToken(): String? = prefs.getString(Constants.PREF_TOKEN, null)

    fun isLoggedIn(): Boolean = !getToken().isNullOrEmpty()

    fun getParentNomComplet(): String {
        val prenom = prefs.getString(Constants.PREF_PARENT_PRENOM, "") ?: ""
        val nom = prefs.getString(Constants.PREF_PARENT_NOM, "") ?: ""
        return "$prenom $nom".trim()
    }

    fun getParentEmail(): String = prefs.getString(Constants.PREF_PARENT_EMAIL, "") ?: ""

    fun saveEleves(eleves: List<Eleve>) {
        prefs.edit().putString(KEY_ELEVES, gson.toJson(eleves)).apply()
    }

    fun getEleves(): List<Eleve> {
        val json = prefs.getString(KEY_ELEVES, null) ?: return emptyList()
        val type = object : TypeToken<List<Eleve>>() {}.type
        return try {
            gson.fromJson(json, type) ?: emptyList()
        } catch (e: Exception) {
            emptyList()
        }
    }

    fun setEleveSelectionneId(id: Int) {
        prefs.edit().putString(Constants.PREF_ELEVE_SELECTIONNE_ID, id.toString()).apply()
    }

    fun getEleveSelectionneId(): Int? =
        prefs.getString(Constants.PREF_ELEVE_SELECTIONNE_ID, null)?.toIntOrNull()

    fun getEleveSelectionne(): Eleve? {
        val id = getEleveSelectionneId() ?: return getEleves().firstOrNull()
        return getEleves().find { it.id == id } ?: getEleves().firstOrNull()
    }

    fun clear() {
        prefs.edit().clear().apply()
    }

    companion object {
        private const val KEY_ELEVES = "eleves_json"
    }
}
