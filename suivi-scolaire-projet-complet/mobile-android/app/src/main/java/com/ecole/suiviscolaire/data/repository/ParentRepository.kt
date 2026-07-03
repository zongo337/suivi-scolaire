package com.ecole.suiviscolaire.data.repository

import android.content.Context
import com.ecole.suiviscolaire.data.local.SessionManager
import com.ecole.suiviscolaire.data.model.ApiErrorResponse
import com.ecole.suiviscolaire.data.model.DashboardResponse
import com.ecole.suiviscolaire.data.model.DataWrapper
import com.ecole.suiviscolaire.data.model.Absence
import com.ecole.suiviscolaire.data.model.Annonce
import com.ecole.suiviscolaire.data.model.Eleve
import com.ecole.suiviscolaire.data.model.LoginRequest
import com.ecole.suiviscolaire.data.model.LoginResponse
import com.ecole.suiviscolaire.data.model.MessageResponse
import com.ecole.suiviscolaire.data.model.NotesResponse
import com.ecole.suiviscolaire.data.model.PaiementsResponse
import com.ecole.suiviscolaire.data.model.UpdatePasswordRequest
import com.ecole.suiviscolaire.data.remote.RetrofitClient
import com.ecole.suiviscolaire.util.ApiResult
import com.google.gson.Gson
import retrofit2.Response
import java.io.IOException

/*
 * Point d'entrée unique vers l'API pour toute l'application.
 * Convertit les réponses Retrofit en ApiResult, en extrayant un
 * message d'erreur lisible depuis le corps JSON renvoyé par Laravel
 * en cas d'échec (422, 401, 403, etc.).
 */
class ParentRepository(context: Context) {

    private val api = RetrofitClient.getApiService(context)
    private val gson = Gson()

    suspend fun login(email: String, password: String): ApiResult<LoginResponse> =
        safeCall { api.login(LoginRequest(email, password)) }

    suspend fun logout(): ApiResult<MessageResponse> = safeCall { api.logout() }

    suspend fun updatePassword(current: String, new: String): ApiResult<MessageResponse> =
        safeCall { api.updatePassword(UpdatePasswordRequest(current, new, new)) }

    suspend fun getEleves(): ApiResult<List<Eleve>> =
        when (val result = safeCall { api.getEleves() }) {
            is ApiResult.Success -> ApiResult.Success(result.data.data)
            is ApiResult.Error -> result
        }

    suspend fun getDashboard(eleveId: Int): ApiResult<DashboardResponse> =
        safeCall { api.getDashboard(eleveId) }

    suspend fun getNotes(eleveId: Int): ApiResult<NotesResponse> =
        safeCall { api.getNotes(eleveId) }

    suspend fun getPaiements(eleveId: Int): ApiResult<PaiementsResponse> =
        safeCall { api.getPaiements(eleveId) }

    suspend fun getAbsences(eleveId: Int): ApiResult<List<Absence>> =
        when (val result = safeCall { api.getAbsences(eleveId) }) {
            is ApiResult.Success -> ApiResult.Success(result.data.data)
            is ApiResult.Error -> result
        }

    suspend fun getAnnonces(type: String? = null): ApiResult<List<Annonce>> =
        when (val result = safeCall { api.getAnnonces(type) }) {
            is ApiResult.Success -> ApiResult.Success(result.data.data)
            is ApiResult.Error -> result
        }

    /*
     * Exécute un appel Retrofit et convertit le résultat en ApiResult,
     * en gérant les erreurs réseau (IOException) et les erreurs HTTP
     * (corps d'erreur JSON Laravel).
     */
    private suspend fun <T> safeCall(call: suspend () -> Response<T>): ApiResult<T> {
        return try {
            val response = call()
            if (response.isSuccessful()) {
                val body = response.body()
                if (body != null) {
                    ApiResult.Success(body)
                } else {
                    ApiResult.Error("Réponse vide du serveur.")
                }
            } else {
                ApiResult.Error(extractErrorMessage(response), response.code())
            }
        } catch (e: IOException) {
            ApiResult.Error("Impossible de contacter le serveur. Vérifiez votre connexion internet.")
        } catch (e: Exception) {
            ApiResult.Error("Une erreur inattendue s'est produite : ${e.message}")
        }
    }

    private fun <T> extractErrorMessage(response: Response<T>): String {
        return try {
            val errorJson = response.errorBody()?.string()
            if (errorJson.isNullOrBlank()) return "Erreur ${response.code()}"

            val parsed = gson.fromJson(errorJson, ApiErrorResponse::class.java)
            val premierChampErreur = parsed?.errors?.values?.firstOrNull()?.firstOrNull()
            premierChampErreur ?: parsed?.message ?: "Erreur ${response.code()}"
        } catch (e: Exception) {
            "Erreur ${response.code()}"
        }
    }
}
