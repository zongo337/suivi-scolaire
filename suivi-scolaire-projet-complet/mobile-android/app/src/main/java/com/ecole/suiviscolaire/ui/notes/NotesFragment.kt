package com.ecole.suiviscolaire.ui.notes

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
import com.ecole.suiviscolaire.data.model.NotesResponse
import com.ecole.suiviscolaire.data.repository.ParentRepository
import com.ecole.suiviscolaire.ui.main.MainActivity
import com.ecole.suiviscolaire.util.ApiResult
import kotlinx.coroutines.launch

class NotesFragment : Fragment() {

    private lateinit var repository: ParentRepository
    private lateinit var matiereAdapter: MatiereAdapter

    private lateinit var recyclerViewMatieres: RecyclerView
    private lateinit var swipeRefresh: SwipeRefreshLayout
    private lateinit var progressBar: ProgressBar
    private lateinit var textViewVide: TextView
    private lateinit var textViewMoyenneT1: TextView
    private lateinit var textViewMoyenneT2: TextView
    private lateinit var textViewMoyenneT3: TextView

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        return inflater.inflate(R.layout.fragment_notes, container, false)
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        repository = ParentRepository(requireContext())

        recyclerViewMatieres = view.findViewById(R.id.recyclerViewMatieres)
        swipeRefresh = view.findViewById(R.id.swipeRefreshNotes)
        progressBar = view.findViewById(R.id.progressBarNotes)
        textViewVide = view.findViewById(R.id.textViewVideNotes)
        textViewMoyenneT1 = view.findViewById(R.id.textViewMoyenneT1)
        textViewMoyenneT2 = view.findViewById(R.id.textViewMoyenneT2)
        textViewMoyenneT3 = view.findViewById(R.id.textViewMoyenneT3)

        matiereAdapter = MatiereAdapter()
        recyclerViewMatieres.layoutManager = LinearLayoutManager(requireContext())
        recyclerViewMatieres.adapter = matiereAdapter

        swipeRefresh.setOnRefreshListener { chargerDonnees() }

        chargerDonnees()
    }

    private fun chargerDonnees() {
        val eleveId = (requireActivity() as MainActivity).getEleveSelectionneId()
        if (eleveId == null) {
            afficherVide("Aucun enfant n'est associé à votre compte.")
            return
        }

        afficherChargement(true)

        lifecycleScope.launch {
            val result = repository.getNotes(eleveId)
            afficherChargement(false)

            when (result) {
                is ApiResult.Success -> afficherDonnees(result.data)
                is ApiResult.Error -> afficherVide(result.message)
            }
        }
    }

    private fun afficherDonnees(data: NotesResponse) {
        if (data.matieres.isEmpty()) {
            afficherVide("Aucune note enregistrée pour le moment.")
            return
        }

        textViewVide.visibility = View.GONE
        matiereAdapter.submitList(data.matieres)

        textViewMoyenneT1.text = formatMoyenne(data.moyennesTrimestrielles["1"])
        textViewMoyenneT2.text = formatMoyenne(data.moyennesTrimestrielles["2"])
        textViewMoyenneT3.text = formatMoyenne(data.moyennesTrimestrielles["3"])
    }

    private fun formatMoyenne(valeur: Double?): String =
        if (valeur == null) "—" else String.format("%.2f", valeur)

    private fun afficherChargement(enCours: Boolean) {
        progressBar.visibility = if (enCours && !swipeRefresh.isRefreshing) View.VISIBLE else View.GONE
        swipeRefresh.isRefreshing = false
    }

    private fun afficherVide(message: String) {
        textViewVide.text = message
        textViewVide.visibility = View.VISIBLE
        matiereAdapter.submitList(emptyList())
    }

    companion object {
        fun nouvelleInstance(): NotesFragment = NotesFragment()
    }
}
