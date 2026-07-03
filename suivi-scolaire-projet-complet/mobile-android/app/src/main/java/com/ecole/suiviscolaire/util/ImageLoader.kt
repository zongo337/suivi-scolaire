package com.ecole.suiviscolaire.util

import android.graphics.Bitmap
import android.graphics.BitmapFactory
import android.widget.ImageView
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext
import java.net.URL

/*
 * Chargeur d'image minimaliste : télécharge la photo de l'élève en
 * arrière-plan et l'affiche dans l'ImageView fournie. Pour un projet
 * de plus grande envergure, on pourrait remplacer ceci par Glide ou
 * Coil, mais cela évite une dépendance supplémentaire pour un simple
 * avatar.
 *
 * Le CoroutineScope (généralement lifecycleScope) est fourni par
 * l'appelant afin que le téléchargement soit annulé automatiquement
 * si l'écran est détruit avant la fin du chargement.
 */
object ImageLoader {

    fun load(scope: CoroutineScope, imageView: ImageView, url: String?, placeholderResId: Int) {
        if (url.isNullOrBlank()) {
            imageView.setImageResource(placeholderResId)
            return
        }

        scope.launch(Dispatchers.Main) {
            val bitmap = try {
                withContext(Dispatchers.IO) { telechargerBitmap(url) }
            } catch (e: Exception) {
                null
            }

            if (bitmap != null) {
                imageView.setImageBitmap(bitmap)
            } else {
                imageView.setImageResource(placeholderResId)
            }
        }
    }

    private fun telechargerBitmap(url: String): Bitmap? {
        return URL(url).openStream().use { stream ->
            BitmapFactory.decodeStream(stream)
        }
    }
}

