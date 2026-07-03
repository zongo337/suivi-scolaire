package com.ecole.suiviscolaire.ui.absences

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
import com.ecole.suiviscolaire.data.model.Absence
import com.ecole.suiviscolaire.data.repository.ParentRepository
import com.ecole.suiviscolaire.ui.main.MainActivity
import com.ecole.suiviscolaire.util.ApiResult
import kotlinx.coroutines.launch

class AbsencesFragment : Fragment() {

    private lateinit var repository: ParentRepository
    private lateinit var absenceAdapter: AbsenceAdapter

    private lateinit var recyclerView: RecyclerView
    private lateinit var swipeRefresh: SwipeRefreshLayout
    private lateinit var progressBar: ProgressBar
    private lateinit var textViewVide: TextView
    private lateinit var textViewTotal: TextView

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        return inflater.inflate(R.layout.fragment_absences, container, false)
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        repository = ParentRepository(requireContext())

        recyclerView = view.findViewById(R.id.recyclerViewAbsences)
        swipeRefresh = view.findViewById(R.id.swipeRefreshAbsences)
        progressBar = view.findViewById(R.id.progressBarAbsences)
        textViewVide = view.findViewById(R.id.textViewVideAbsences)
        textViewTotal = view.findViewById(R.id.textViewTotalAbsences)

        absenceAdapter = AbsenceAdapter()
        recyclerView.layoutManager = LinearLayoutManager(requireContext())
        recyclerView.adapter = absenceAdapter

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
            val result = repository.getAbsences(eleveId)
            afficherChargement(false)

            when (result) {
                is ApiResult.Success -> afficherDonnees(result.data)
                is ApiResult.Error -> afficherVide(result.message)
            }
        }
    }

    private fun afficherDonnees(absences: List<Absence>) {
        textViewTotal.text = "${absences.size} absence(s) enregistrée(s)"

        if (absences.isEmpty()) {
            afficherVide("Aucune absence enregistrée. 🎉")
        } else {
            textViewVide.visibility = View.GONE
        }

        absenceAdapter.submitList(absences)
    }

    private fun afficherChargement(enCours: Boolean) {
        progressBar.visibility = if (enCours && !swipeRefresh.isRefreshing) View.VISIBLE else View.GONE
        swipeRefresh.isRefreshing = false
    }

    private fun afficherVide(message: String) {
        textViewVide.text = message
        textViewVide.visibility = View.VISIBLE
        absenceAdapter.submitList(emptyList())
        textViewTotal.text = ""
    }

    companion object {
        fun nouvelleInstance(): AbsencesFragment = AbsencesFragment()
    }
}
