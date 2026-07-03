package com.ecole.suiviscolaire.data.model

import com.google.gson.annotations.SerializedName

data class Classe(
    @SerializedName("id") val id: Int,
    @SerializedName("nom") val nom: String
)
