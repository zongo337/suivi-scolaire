package com.ecole.suiviscolaire.data.model

import com.google.gson.annotations.SerializedName

/*
 * Lorsqu'un contrôleur Laravel retourne directement une collection de
 * Resource (ex: `return EleveResource::collection($eleves);`), la
 * réponse JSON est automatiquement enveloppée dans une clé "data".
 * C'est le cas pour /api/eleves, /api/eleves/{id}/absences et
 * /api/annonces. Les autres endpoints renvoient leurs données dans
 * une structure personnalisée (pas de wrapper "data").
 */
data class DataWrapper<T>(
    @SerializedName("data") val data: List<T>
)
