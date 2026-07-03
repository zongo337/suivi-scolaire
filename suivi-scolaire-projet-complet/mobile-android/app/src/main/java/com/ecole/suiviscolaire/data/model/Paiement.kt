package com.ecole.suiviscolaire.data.model

import com.google.gson.annotations.SerializedName

data class Paiement(
    @SerializedName("id") val id: Int,
    @SerializedName("montant") val montant: Double,
    @SerializedName("date_paiement") val datePaiement: String?,
    @SerializedName("reference") val reference: String?,
    @SerializedName("observation") val observation: String?
)

data class PaiementsResponse(
    @SerializedName("paiements") val paiements: List<Paiement>,
    @SerializedName("total_paye") val totalPaye: Double,
    @SerializedName("frais_scolarite") val fraisScolarite: Double,
    @SerializedName("reste_a_payer") val resteAPayer: Double
)
