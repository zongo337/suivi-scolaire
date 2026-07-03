package com.ecole.suiviscolaire.ui.annonces

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ProgressBar
import android.widget.TextView
import androidx.fragment.app.Fragment
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout
import com.ecole.suiviscolaire.R
import com.ecole.suiviscolaire.data.model.Annonce
import com.ecole.suiviscolaire.data.repository.ParentRepository
import com.ecole.suiviscolaire.util.ApiResult
import kotlinx.coroutines.launch

class AnnoncesFragment : Fragment() {

    private lateinit var repository: ParentRepository
    private lateinit var annonceAdapter: AnnonceAdapter

    private lateinit var recyclerView: RecyclerView
    private lateinit var swipeRefresh: SwipeRefreshLayout
    private lateinit var progressBar: ProgressBar
    private lateinit var textViewVide: TextView
    private lateinit var chipTous: TextView
    private lateinit var chipAnnonces: TextView
    private lateinit var chipNotifications: TextView

    private var filtreActuel: String? = null

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        return inflater.inflate(R.layout.fragment_annonces, container, false)
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        repository = ParentRepository(requireContext())

        recyclerView = view.findViewById(R.id.recyclerViewAnnonces)
        swipeRefresh = view.findViewById(R.id.swipeRefreshAnnonces)
        progressBar = view.findViewById(R.id.progressBarAnnonces)
        textViewVide = view.findViewById(R.id.textViewVideAnnonces)
        chipTous = view.findViewById(R.id.chipFiltreTous)
        chipAnnonces = view.findViewById(R.id.chipFiltreAnnonces)
        chipNotifications = view.findViewById(R.id.chipFiltreNotifications)

        annonceAdapter = AnnonceAdapter()
        recyclerView.layoutManager = LinearLayoutManager(requireContext())
        recyclerView.adapter = annonceAdapter

        chipTous.setOnClickListener { appliquerFiltre(null) }
        chipAnnonces.setOnClickListener { appliquerFiltre("annonce") }
        chipNotifications.setOnClickListener { appliquerFiltre("notification") }

        swipeRefresh.setOnRefreshListener { chargerDonnees() }

        chargerDonnees()
    }

    private fun appliquerFiltre(type: String?) {
        filtreActuel = type
        mettreAJourApparenceFiltres()
        chargerDonnees()
    }

    private fun mettreAJourApparenceFiltres() {
        chipTous.alpha = if (filtreActuel == null) 1.0f else 0.5f
        chipAnnonces.alpha = if (filtreActuel == "annonce") 1.0f else 0.5f
        chipNotifications.alpha = if (filtreActuel == "notification") 1.0f else 0.5f
    }

    private fun chargerDonnees() {
        afficherChargement(true)

        lifecycleScope.launch {
            val result = repository.getAnnonces(filtreActuel)
            afficherChargement(false)

            when (result) {
                is ApiResult.Success -> afficherDonnees(result.data)
                is ApiResult.Error -> afficherVide(result.message)
            }
        }
    }

    private fun afficherDonnees(annonces: List<Annonce>) {
        if (annonces.isEmpty()) {
            afficherVide("Aucune annonce pour le moment.")
        } else {
            textViewVide.visibility = View.GONE
        }
        annonceAdapter.submitList(annonces)
    }

    private fun afficherChargement(enCours: Boolean) {
        progressBar.visibility = if (enCours && !swipeRefresh.isRefreshing) View.VISIBLE else View.GONE
        swipeRefresh.isRefreshing = false
    }

    private fun afficherVide(message: String) {
        textViewVide.text = message
        textViewVide.visibility = View.VISIBLE
        annonceAdapter.submitList(emptyList())
    }

    companion object {
        fun nouvelleInstance(): AnnoncesFragment = AnnoncesFragment()
    }
}
