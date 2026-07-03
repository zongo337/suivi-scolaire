package com.ecole.suiviscolaire.ui.main

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.ImageButton
import android.widget.PopupMenu
import android.widget.TextView
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.ecole.suiviscolaire.R
import com.ecole.suiviscolaire.data.local.SessionManager
import com.ecole.suiviscolaire.data.model.Eleve
import com.ecole.suiviscolaire.data.repository.ParentRepository
import com.ecole.suiviscolaire.ui.absences.AbsencesFragment
import com.ecole.suiviscolaire.ui.annonces.AnnoncesFragment
import com.ecole.suiviscolaire.ui.dashboard.DashboardFragment
import com.ecole.suiviscolaire.ui.eleveselector.EleveSelectorAdapter
import com.ecole.suiviscolaire.ui.login.LoginActivity
import com.ecole.suiviscolaire.ui.notes.NotesFragment
import com.ecole.suiviscolaire.ui.paiements.PaiementsFragment
import com.ecole.suiviscolaire.ui.profile.ChangePasswordActivity
import com.ecole.suiviscolaire.util.ApiResult
import com.google.android.material.bottomnavigation.BottomNavigationView
import kotlinx.coroutines.launch

class MainActivity : AppCompatActivity() {

    private lateinit var sessionManager: SessionManager
    private lateinit var repository: ParentRepository
    private lateinit var eleveAdapter: EleveSelectorAdapter

    private lateinit var bottomNav: BottomNavigationView
    private lateinit var recyclerEleveSelector: RecyclerView
    private lateinit var eleveSelectorContainer: View
    private lateinit var toolbarTitle: TextView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        sessionManager = SessionManager(applicationContext)
        repository = ParentRepository(applicationContext)

        if (!sessionManager.isLoggedIn()) {
            goToLogin()
            return
        }

        bottomNav = findViewById(R.id.bottomNavigationView)
        recyclerEleveSelector = findViewById(R.id.recyclerViewEleveSelector)
        eleveSelectorContainer = findViewById(R.id.eleveSelectorContainer)
        toolbarTitle = findViewById(R.id.toolbarTitle)

        // Bouton menu ⋮
        val btnMenu = findViewById<ImageButton>(R.id.btnMenu)
        btnMenu.setOnClickListener { view ->
            val popup = PopupMenu(this, view)
            popup.menuInflater.inflate(R.menu.toolbar_menu, popup.menu)
            popup.setOnMenuItemClickListener { item ->
                when (item.itemId) {
                    R.id.action_changer_mdp -> {
                        startActivity(Intent(this, ChangePasswordActivity::class.java))
                        true
                    }
                    R.id.action_deconnexion -> { deconnexion(); true }
                    else -> false
                }
            }
            popup.show()
        }

        configurerSelecteurEleve()
        configurerNavigation()

        if (savedInstanceState == null) {
            afficherFragment(DashboardFragment.nouvelleInstance())
            toolbarTitle.text = "Tableau de bord"
        }
    }

    private fun configurerSelecteurEleve() {
        val eleves = sessionManager.getEleves()
        eleveSelectorContainer.visibility = if (eleves.size > 1) View.VISIBLE else View.GONE
        eleveAdapter = EleveSelectorAdapter { eleve -> selectionnerEleve(eleve) }
        recyclerEleveSelector.layoutManager =
            LinearLayoutManager(this, LinearLayoutManager.HORIZONTAL, false)
        recyclerEleveSelector.setAdapter(eleveAdapter)
        eleveAdapter.selectedEleveId = sessionManager.getEleveSelectionneId()
        eleveAdapter.submitList(eleves)
    }

    private fun selectionnerEleve(eleve: Eleve) {
        sessionManager.setEleveSelectionneId(eleve.id)
        eleveAdapter.selectedEleveId = eleve.id
        recreerFragmentCourant()
    }

    private fun configurerNavigation() {
        bottomNav.setOnItemSelectedListener { item ->
            when (item.itemId) {
                R.id.nav_dashboard -> {
                    afficherFragment(DashboardFragment.nouvelleInstance())
                    toolbarTitle.text = "Tableau de bord"
                    true
                }
                R.id.nav_notes -> {
                    afficherFragment(NotesFragment.nouvelleInstance())
                    toolbarTitle.text = "Notes"
                    true
                }
                R.id.nav_paiements -> {
                    afficherFragment(PaiementsFragment.nouvelleInstance())
                    toolbarTitle.text = "Paiements"
                    true
                }
                R.id.nav_absences -> {
                    afficherFragment(AbsencesFragment.nouvelleInstance())
                    toolbarTitle.text = "Absences"
                    true
                }
                R.id.nav_annonces -> {
                    afficherFragment(AnnoncesFragment.nouvelleInstance())
                    toolbarTitle.text = "Annonces"
                    true
                }
                else -> false
            }
        }
    }

    private fun afficherFragment(fragment: androidx.fragment.app.Fragment) {
        supportFragmentManager.beginTransaction()
            .replace(R.id.fragmentContainer, fragment)
            .commit()
    }

    private fun recreerFragmentCourant() {
        when (bottomNav.selectedItemId) {
            R.id.nav_dashboard -> afficherFragment(DashboardFragment.nouvelleInstance())
            R.id.nav_notes -> afficherFragment(NotesFragment.nouvelleInstance())
            R.id.nav_paiements -> afficherFragment(PaiementsFragment.nouvelleInstance())
            R.id.nav_absences -> afficherFragment(AbsencesFragment.nouvelleInstance())
            R.id.nav_annonces -> afficherFragment(AnnoncesFragment.nouvelleInstance())
        }
    }

    fun getEleveSelectionneId(): Int? = sessionManager.getEleveSelectionneId()

    fun deconnexion() {
        AlertDialog.Builder(this)
            .setTitle("Déconnexion")
            .setMessage("Voulez-vous vraiment vous déconnecter ?")
            .setPositiveButton("Déconnexion") { _, _ -> effectuerDeconnexion() }
            .setNegativeButton("Annuler", null)
            .show()
    }

    private fun effectuerDeconnexion() {
        lifecycleScope.launch {
            when (repository.logout()) {
                is ApiResult.Success -> {}
                is ApiResult.Error -> {}
            }
            sessionManager.clear()
            goToLogin()
        }
    }

    private fun goToLogin() {
        val intent = Intent(this, LoginActivity::class.java)
        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK).addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK)
        startActivity(intent)
        finish()
    }
}