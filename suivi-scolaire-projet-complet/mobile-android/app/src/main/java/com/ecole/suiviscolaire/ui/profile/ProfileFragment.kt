package com.ecole.suiviscolaire.ui.profile

import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import androidx.fragment.app.Fragment
import com.ecole.suiviscolaire.R
import com.ecole.suiviscolaire.data.local.SessionManager
import com.ecole.suiviscolaire.ui.main.MainActivity
import com.ecole.suiviscolaire.ui.profile.ChangePasswordActivity

class ProfileFragment : Fragment() {

    private lateinit var sessionManager: SessionManager

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        return inflater.inflate(R.layout.fragment_profile, container, false)
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        sessionManager = SessionManager(requireContext())

        val textViewNom: TextView = view.findViewById(R.id.textViewNomParent)
        val textViewEmail: TextView = view.findViewById(R.id.textViewEmailParent)
        val buttonChangerMotDePasse: Button = view.findViewById(R.id.buttonChangerMotDePasse)
        val buttonDeconnexion: Button = view.findViewById(R.id.buttonDeconnexion)

        textViewNom.text = sessionManager.getParentNomComplet()
        textViewEmail.text = sessionManager.getParentEmail()

        buttonChangerMotDePasse.setOnClickListener {
            startActivity(Intent(requireContext(), ChangePasswordActivity::class.java))
        }

        buttonDeconnexion.setOnClickListener {
            (requireActivity() as MainActivity).deconnexion()
        }
    }

    companion object {
        fun nouvelleInstance(): ProfileFragment = ProfileFragment()
    }
}
