package com.ecole.suiviscolaire.data.model

import com.google.gson.annotations.SerializedName

data class Annonce(
    @SerializedName("id") val id: Int,
    @SerializedName("titre") val titre: String,
    @SerializedName("contenu") val contenu: String,
    @SerializedName("type") val type: String, // "annonce" ou "notification"
    @SerializedName("classe") val classe: String?,
    @SerializedName("date_publication") val datePublication: String?
) {
    val estNotification: Boolean get() = type == "notification"
}
