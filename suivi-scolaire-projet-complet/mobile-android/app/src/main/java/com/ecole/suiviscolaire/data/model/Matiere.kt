package com.ecole.suiviscolaire.data.model

import com.google.gson.annotations.SerializedName

data class Matiere(
    @SerializedName("id") val id: Int,
    @SerializedName("nom") val nom: String,
    @SerializedName("coefficient") val coefficient: Int,
    @SerializedName("note_sur") val noteSur: Int? = null
)
