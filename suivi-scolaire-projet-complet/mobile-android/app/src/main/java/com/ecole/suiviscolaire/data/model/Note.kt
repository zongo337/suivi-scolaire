package com.ecole.suiviscolaire.data.model

import com.google.gson.annotations.SerializedName

data class Note(
    @SerializedName("id") val id: Int,
    @SerializedName("note") val note: Double,
    @SerializedName("note_sur") val noteSur: Int?,
    @SerializedName("trimestre") val trimestre: String,
    @SerializedName("annee_scolaire") val anneeScolaire: String,
    @SerializedName("matiere") val matiere: Matiere?
)

/**
 * Une entrée du tableau "matières" renvoyé par /api/eleves/{id}/notes :
 * regroupe toutes les notes de l'élève pour une matière donnée.
 */
data class MatiereNotes(
    @SerializedName("matiere") val matiere: Matiere,
    @SerializedName("notes") val notes: List<Note>,
    @SerializedName("moyenne_matiere") val moyenneMatiere: Double
)

data class NotesResponse(
    @SerializedName("matieres") val matieres: List<MatiereNotes>,
    @SerializedName("moyennes_trimestrielles") val moyennesTrimestrielles: Map<String, Double>
)
