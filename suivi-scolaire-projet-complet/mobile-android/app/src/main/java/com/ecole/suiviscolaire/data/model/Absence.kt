package com.ecole.suiviscolaire.data.model

import com.google.gson.annotations.SerializedName

data class Absence(
    @SerializedName("id") val id: Int,
    @SerializedName("date_absence") val dateAbsence: String,
    @SerializedName("motif") val motif: String?,
    @SerializedName("justifiee") val justifiee: Boolean
)
