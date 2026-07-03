package com.ecole.suiviscolaire.ui.paiements

import android.content.Context
import android.os.Bundle
import android.print.PrintAttributes
import android.print.PrintManager
import android.view.View
import android.webkit.WebView
import android.webkit.WebViewClient
import android.widget.Button
import android.widget.ProgressBar
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import com.ecole.suiviscolaire.R
import com.ecole.suiviscolaire.data.local.SessionManager
import com.ecole.suiviscolaire.util.Constants

/*
 * Affiche le reçu de paiement (page HTML générée par le serveur) dans
 * une WebView, et permet de l'imprimer ou de l'enregistrer en PDF via
 * le gestionnaire d'impression natif d'Android — aucune génération de
 * PDF côté serveur n'est nécessaire.
 */
class RecuActivity : AppCompatActivity() {

    private lateinit var webView: WebView
    private lateinit var buttonImprimer: Button
    private lateinit var progressBar: ProgressBar
    private lateinit var toolbarTitle: TextView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_recu)

        webView = findViewById(R.id.webViewRecu)
        buttonImprimer = findViewById(R.id.buttonImprimerRecu)
        progressBar = findViewById(R.id.progressBarRecu)
        toolbarTitle = findViewById(R.id.toolbarRecu)
        toolbarTitle.text = "Reçu de paiement"

        val eleveId = intent.getIntExtra(Constants.EXTRA_ELEVE_ID, -1)
        val paiementId = intent.getIntExtra(Constants.EXTRA_PAIEMENT_ID, -1)

        webView.getSettings().javaScriptEnabled = false
        webView.webViewClient = object : WebViewClient() {
            override fun onPageFinished(view: WebView?, url: String?) {
                progressBar.visibility = View.GONE
            }
        }

        val sessionManager = SessionManager(applicationContext)
        val token = sessionManager.getToken().orEmpty()
        val url = "${Constants.BASE_URL}eleves/$eleveId/paiements/$paiementId/recu"

        webView.loadUrl(url, mapOf("Authorization" to "Bearer $token"))

        buttonImprimer.setOnClickListener { imprimerRecu() }
    }

    private fun imprimerRecu() {
        val printManager = getSystemService(Context.PRINT_SERVICE) as PrintManager
        val adapter = webView.createPrintDocumentAdapter("recu_paiement")
        printManager.print(
            "Reçu de paiement",
            adapter,
            PrintAttributes.Builder().build()
        )
    }
}
