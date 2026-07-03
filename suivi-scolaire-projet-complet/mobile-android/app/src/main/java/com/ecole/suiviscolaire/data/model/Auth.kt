package com.ecole.suiviscolaire.data.model

import com.google.gson.annotations.SerializedName

data class LoginRequest(
    @SerializedName("email") val email: String,
    @SerializedName("password") val password: String
)

data class ParentInfo(
    @SerializedName("id") val id: Int,
    @SerializedName("nom") val nom: String,
    @SerializedName("prenom") val prenom: String,
    @SerializedName("email") val email: String
)

data class LoginResponse(
    @SerializedName("token") val token: String,
    @SerializedName("parent") val parent: ParentInfo,
    @SerializedName("eleves") val eleves: List<Eleve>
)

data class UpdatePasswordRequest(
    @SerializedName("current_password") val currentPassword: String,
    @SerializedName("new_password") val newPassword: String,
    @SerializedName("new_password_confirmation") val newPasswordConfirmation: String
)

data class MessageResponse(
    @SerializedName("message") val message: String
)

/**
 * Structure générique des erreurs de validation retournées par Laravel
 * (HTTP 422) : { "message": "...", "errors": { "champ": ["message"] } }
 */
data class ApiErrorResponse(
    @SerializedName("message") val message: String?,
    @SerializedName("errors") val errors: Map<String, List<String>>?
)
