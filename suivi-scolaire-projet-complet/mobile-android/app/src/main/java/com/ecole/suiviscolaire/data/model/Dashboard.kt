package com.ecole.suiviscolaire.data.model

import com.google.gson.annotations.SerializedName

data class EleveDashboardInfo(
    @SerializedName("id") val id: Int,
    @SerializedName("nom") val nom: String,
    @SerializedName("prenom") val prenom: String,
    @SerializedName("photo_url") val photoUrl: String?,
    @SerializedName("classe") val classe: String?,
    @SerializedName("moyenne_generale") val moyenneGenerale: Double,
    @SerializedName("rang") val rang: Int?,
    @SerializedName("effectif_classe") val effectifClasse: Int?
) {
    val nomComplet: String get() = "$prenom $nom"
}

data class DashboardResponse(
    @SerializedName("eleve") val eleve: EleveDashboardInfo,
    @SerializedName("dernieres_notes") val dernieresNotes: List<Note>
)
