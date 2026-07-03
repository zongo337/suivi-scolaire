package com.ecole.suiviscolaire.data.remote

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
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.POST
import retrofit2.http.PUT
import retrofit2.http.Path
import retrofit2.http.Query

interface ApiService {

    @POST("login")
    suspend fun login(@Body request: LoginRequest): Response<LoginResponse>

    @POST("logout")
    suspend fun logout(): Response<MessageResponse>

    @PUT("password")
    suspend fun updatePassword(@Body request: UpdatePasswordRequest): Response<MessageResponse>

    @GET("eleves")
    suspend fun getEleves(): Response<DataWrapper<Eleve>>

    @GET("eleves/{eleve}/dashboard")
    suspend fun getDashboard(@Path("eleve") eleveId: Int): Response<DashboardResponse>

    @GET("eleves/{eleve}/notes")
    suspend fun getNotes(
        @Path("eleve") eleveId: Int,
        @Query("annee_scolaire") anneeScolaire: String? = null
    ): Response<NotesResponse>

    @GET("eleves/{eleve}/paiements")
    suspend fun getPaiements(@Path("eleve") eleveId: Int): Response<PaiementsResponse>

    @GET("eleves/{eleve}/absences")
    suspend fun getAbsences(@Path("eleve") eleveId: Int): Response<DataWrapper<Absence>>

    @GET("annonces")
    suspend fun getAnnonces(@Query("type") type: String? = null): Response<DataWrapper<Annonce>>
}
