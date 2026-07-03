package com.ecole.suiviscolaire.data.model

import com.google.gson.annotations.SerializedName

data class Eleve(
    @SerializedName("id") val id: Int,
    @SerializedName("nom") val nom: String,
    @SerializedName("prenom") val prenom: String,
    @SerializedName("date_naissance") val dateNaissance: String?,
    @SerializedName("sexe") val sexe: String?,
    @SerializedName("photo_url") val photoUrl: String?,
    @SerializedName("classe") val classe: Classe?
) {
    val nomComplet: String get() = "$prenom $nom"
}
