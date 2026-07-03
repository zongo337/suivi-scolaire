package com.ecole.suiviscolaire.ui.dashboard

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.ProgressBar
import android.widget.TextView
import androidx.fragment.app.Fragment
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout
import com.ecole.suiviscolaire.R
import com.ecole.suiviscolaire.data.repository.ParentRepository
import com.ecole.suiviscolaire.ui.main.MainActivity
import com.ecole.suiviscolaire.util.ApiResult
import com.ecole.suiviscolaire.util.ImageLoader
import kotlinx.coroutines.launch

class DashboardFragment : Fragment() {

    private lateinit var repository: ParentRepository
    private lateinit var notesAdapter: DernieresNotesAdapter

    private lateinit var imageViewPhoto: ImageView
    private lateinit var textViewNomEleve: TextView
    private lateinit var textViewClasse: TextView
    private lateinit var textViewMoyenneGenerale: TextView
    private lateinit var textViewRang: TextView
    private lateinit var textViewEffectif: TextView
    private lateinit var recyclerViewDernieresNotes: RecyclerView
    private lateinit var swipeRefresh: SwipeRefreshLayout
    private lateinit var progressBar: ProgressBar
    private lateinit var textViewVide: TextView

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        return inflater.inflate(R.layout.fragment_dashboard, container, false)
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        repository = ParentRepository(requireContext())

        imageViewPhoto = view.findViewById(R.id.imageViewPhoto)
        textViewNomEleve = view.findViewById(R.id.textViewNomEleve)
        textViewClasse = view.findViewById(R.id.textViewClasse)
        textViewMoyenneGenerale = view.findViewById(R.id.textViewMoyenneGenerale)
        textViewRang = view.findViewById(R.id.textViewRang)
        textViewEffectif = view.findViewById(R.id.textViewEffectif)
        recyclerViewDernieresNotes = view.findViewById(R.id.recyclerViewDernieresNotes)
        swipeRefresh = view.findViewById(R.id.swipeRefreshDashboard)
        progressBar = view.findViewById(R.id.progressBarDashboard)
        textViewVide = view.findViewById(R.id.textViewVide)

        notesAdapter = DernieresNotesAdapter()
        recyclerViewDernieresNotes.layoutManager = LinearLayoutManager(requireContext())
        recyclerViewDernieresNotes.adapter = notesAdapter
        recyclerViewDernieresNotes.setHasFixedSize(true)

        swipeRefresh.setOnRefreshListener { chargerDonnees() }

        chargerDonnees()
    }

    private fun chargerDonnees() {
        val eleveId = (requireActivity() as MainActivity).getEleveSelectionneId()
        if (eleveId == null) {
            afficherVide("Aucun enfant n'est associé à votre compte. Contactez l'établissement.")
            return
        }

        afficherChargement(true)

        lifecycleScope.launch {
            val result = repository.getDashboard(eleveId)
            afficherChargement(false)

            when (result) {
                is ApiResult.Success -> afficherDonnees(result.data)
                is ApiResult.Error -> afficherVide(result.message)
            }
        }
    }

    private fun afficherDonnees(data: com.ecole.suiviscolaire.data.model.DashboardResponse) {
        textViewVide.visibility = View.GONE

        val eleve = data.eleve
        textViewNomEleve.text = eleve.nomComplet
        textViewClasse.text = eleve.classe ?: "—"
        textViewMoyenneGenerale.text = String.format("%.2f/10", eleve.moyenneGenerale)

        if (eleve.rang != null && eleve.effectifClasse != null) {
            textViewRang.text = "${eleve.rang}ᵉ"
            textViewEffectif.text = "sur ${eleve.effectifClasse} élèves"
        } else {
            textViewRang.text = "—"
            textViewEffectif.text = ""
        }

        ImageLoader.load(lifecycleScope, imageViewPhoto, eleve.photoUrl, R.drawable.ic_eleve_placeholder)

        notesAdapter.submitList(data.dernieresNotes)
    }

    private fun afficherChargement(enCours: Boolean) {
        if (enCours && !swipeRefresh.isRefreshing) {
            progressBar.visibility = View.VISIBLE
        } else {
            progressBar.visibility = View.GONE
        }
        swipeRefresh.isRefreshing = false
    }

    private fun afficherVide(message: String) {
        textViewVide.text = message
        textViewVide.visibility = View.VISIBLE
    }

    companion object {
        fun nouvelleInstance(): DashboardFragment = DashboardFragment()
    }
}
