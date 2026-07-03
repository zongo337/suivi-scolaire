package com.ecole.suiviscolaire.ui.profile

import android.os.Bundle
import android.view.View
import android.widget.Button
import android.widget.EditText
import android.widget.ProgressBar
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import com.ecole.suiviscolaire.R
import com.ecole.suiviscolaire.data.repository.ParentRepository
import com.ecole.suiviscolaire.util.ApiResult
import kotlinx.coroutines.launch

class ChangePasswordActivity : AppCompatActivity() {

    private lateinit var repository: ParentRepository

    private lateinit var editCurrentPassword: EditText
    private lateinit var editNewPassword: EditText
    private lateinit var editConfirmPassword: EditText
    private lateinit var buttonValider: Button
    private lateinit var progressBar: ProgressBar
    private lateinit var textViewError: TextView
    private lateinit var textViewSuccess: TextView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_change_password)

        repository = ParentRepository(applicationContext)

        editCurrentPassword = findViewById(R.id.editTextCurrentPassword)
        editNewPassword = findViewById(R.id.editTextNewPassword)
        editConfirmPassword = findViewById(R.id.editTextConfirmPassword)
        buttonValider = findViewById(R.id.buttonValiderMotDePasse)
        progressBar = findViewById(R.id.progressBarChangePassword)
        textViewError = findViewById(R.id.textViewErrorChangePassword)
        textViewSuccess = findViewById(R.id.textViewSuccessChangePassword)

        buttonValider.setOnClickListener { soumettre() }
    }

    private fun soumettre() {
        val current = editCurrentPassword.text.toString()
        val new = editNewPassword.text.toString()
        val confirm = editConfirmPassword.text.toString()

        textViewError.visibility = View.GONE
        textViewSuccess.visibility = View.GONE

        if (current.isEmpty() || new.isEmpty() || confirm.isEmpty()) {
            afficherErreur("Veuillez remplir tous les champs.")
            return
        }
        if (new.length < 6) {
            afficherErreur("Le nouveau mot de passe doit contenir au moins 6 caractères.")
            return
        }
        if (new != confirm) {
            afficherErreur("La confirmation ne correspond pas au nouveau mot de passe.")
            return
        }

        afficherChargement(true)

        lifecycleScope.launch {
            val result = repository.updatePassword(current, new)
            afficherChargement(false)

            when (result) {
                is ApiResult.Success -> {
                    textViewSuccess.text = "Mot de passe modifié avec succès."
                    textViewSuccess.visibility = View.VISIBLE
                    editCurrentPassword.setText("")
                    editNewPassword.setText("")
                    editConfirmPassword.setText("")
                }
                is ApiResult.Error -> afficherErreur(result.message)
            }
        }
    }

    private fun afficherChargement(enCours: Boolean) {
        progressBar.visibility = if (enCours) View.VISIBLE else View.GONE
        buttonValider.isEnabled = !enCours
    }

    private fun afficherErreur(message: String) {
        textViewError.text = message
        textViewError.visibility = View.VISIBLE
    }
}