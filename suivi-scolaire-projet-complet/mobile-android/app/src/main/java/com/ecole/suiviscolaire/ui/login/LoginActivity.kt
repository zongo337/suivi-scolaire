package com.ecole.suiviscolaire.ui.login

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.Button
import android.widget.EditText
import android.widget.ProgressBar
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import com.ecole.suiviscolaire.R
import com.ecole.suiviscolaire.data.local.SessionManager
import com.ecole.suiviscolaire.data.repository.ParentRepository
import com.ecole.suiviscolaire.ui.main.MainActivity
import com.ecole.suiviscolaire.util.ApiResult
import kotlinx.coroutines.launch

class LoginActivity : AppCompatActivity() {

    private lateinit var repository: ParentRepository
    private lateinit var sessionManager: SessionManager

    private lateinit var emailInput: EditText
    private lateinit var passwordInput: EditText
    private lateinit var loginButton: Button
    private lateinit var progressBar: ProgressBar
    private lateinit var errorText: TextView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        repository = ParentRepository(applicationContext)
        sessionManager = SessionManager(applicationContext)

        emailInput = findViewById(R.id.editTextEmail)
        passwordInput = findViewById(R.id.editTextPassword)
        loginButton = findViewById(R.id.buttonLogin)
        progressBar = findViewById(R.id.progressBarLogin)
        errorText = findViewById(R.id.textViewError)

        // Si déjà connecté, on passe directement à l'écran principal
        if (sessionManager.isLoggedIn()) {
            goToMain()
            return
        }

        loginButton.setOnClickListener { tenterConnexion() }
    }

    private fun tenterConnexion() {
        val email = emailInput.text.toString().trim()
        val password = passwordInput.text.toString()

        errorText.visibility = View.GONE

        if (email.isEmpty() || password.isEmpty()) {
            afficherErreur("Veuillez renseigner votre email et votre mot de passe.")
            return
        }

        afficherChargement(true)

        lifecycleScope.launch {
            val result = repository.login(email, password)
            afficherChargement(false)

            when (result) {
                is ApiResult.Success -> {
                    sessionManager.saveLogin(result.data)
                    goToMain()
                }
                is ApiResult.Error -> afficherErreur(result.message)
            }
        }
    }

    private fun afficherChargement(enCours: Boolean) {
        progressBar.visibility = if (enCours) View.VISIBLE else View.GONE
        loginButton.isEnabled = !enCours
    }

    private fun afficherErreur(message: String) {
        errorText.text = message
        errorText.visibility = View.VISIBLE
    }

    private fun goToMain() {
        val intent = Intent(this, MainActivity::class.java)
        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK).addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK)
        startActivity(intent)
        finish()
    }
}
