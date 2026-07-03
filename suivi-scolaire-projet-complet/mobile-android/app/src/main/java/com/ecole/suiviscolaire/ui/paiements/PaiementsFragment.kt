package com.ecole.suiviscolaire.ui.paiements

import android.content.Intent
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
import com.ecole.suiviscolaire.data.model.PaiementsResponse
import com.ecole.suiviscolaire.data.repository.ParentRepository
import com.ecole.suiviscolaire.ui.main.MainActivity
import com.ecole.suiviscolaire.util.ApiResult
import com.ecole.suiviscolaire.util.Constants
import kotlinx.coroutines.launch
import java.text.NumberFormat
import java.util.Locale

class PaiementsFragment : Fragment() {

    private lateinit var repository: ParentRepository
    private lateinit var paiementAdapter: PaiementAdapter

    private lateinit var recyclerViewPaiements: RecyclerView
    private lateinit var swipeRefresh: SwipeRefreshLayout
    private lateinit var progressBar: ProgressBar
    private lateinit var textViewVide: TextView
    private lateinit var textViewTotalPaye: TextView
    private lateinit var textViewResteAPayer: TextView
    private lateinit var textViewFraisScolarite: TextView

    private var eleveIdCourant: Int? = null

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        return inflater.inflate(R.layout.fragment_paiements, container, false)
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        repository = ParentRepository(requireContext())

        recyclerViewPaiements = view.findViewById(R.id.recyclerViewPaiements)
        swipeRefresh = view.findViewById(R.id.swipeRefreshPaiements)
        progressBar = view.findViewById(R.id.progressBarPaiements)
        textViewVide = view.findViewById(R.id.textViewVidePaiements)
        textViewTotalPaye = view.findViewById(R.id.textViewTotalPaye)
        textViewResteAPayer = view.findViewById(R.id.textViewResteAPayer)
        textViewFraisScolarite = view.findViewById(R.id.textViewFraisScolarite)

        paiementAdapter = PaiementAdapter { paiement ->
            val eleveId = eleveIdCourant ?: return@PaiementAdapter
            val intent = Intent(requireContext(), RecuActivity::class.java)
            intent.putExtra(Constants.EXTRA_ELEVE_ID, eleveId)
            intent.putExtra(Constants.EXTRA_PAIEMENT_ID, paiement.id)
            startActivity(intent)
        }
        recyclerViewPaiements.layoutManager = LinearLayoutManager(requireContext())
        recyclerViewPaiements.adapter = paiementAdapter

        swipeRefresh.setOnRefreshListener { chargerDonnees() }

        chargerDonnees()
    }

    private fun chargerDonnees() {
        val eleveId = (requireActivity() as MainActivity).getEleveSelectionneId()
        eleveIdCourant = eleveId

        if (eleveId == null) {
            afficherVide("Aucun enfant n'est associé à votre compte.")
            return
        }

        afficherChargement(true)

        lifecycleScope.launch {
            val result = repository.getPaiements(eleveId)
            afficherChargement(false)

            when (result) {
                is ApiResult.Success -> afficherDonnees(result.data)
                is ApiResult.Error -> afficherVide(result.message)
            }
        }
    }

    private fun afficherDonnees(data: PaiementsResponse) {
        val formatteur = NumberFormat.getNumberInstance(Locale.FRANCE)

        textViewTotalPaye.text = "${formatteur.format(data.totalPaye)} FCFA"
        textViewFraisScolarite.text = "${formatteur.format(data.fraisScolarite)} FCFA"
        textViewResteAPayer.text = "${formatteur.format(data.resteAPayer)} FCFA"

        if (data.paiements.isEmpty()) {
            textViewVide.text = "Aucun versement enregistré pour le moment."
            textViewVide.visibility = View.VISIBLE
        } else {
            textViewVide.visibility = View.GONE
        }

        paiementAdapter.submitList(data.paiements)
    }

    private fun afficherChargement(enCours: Boolean) {
        progressBar.visibility = if (enCours && !swipeRefresh.isRefreshing) View.VISIBLE else View.GONE
        swipeRefresh.isRefreshing = false
    }

    private fun afficherVide(message: String) {
        textViewVide.text = message
        textViewVide.visibility = View.VISIBLE
        paiementAdapter.submitList(emptyList())
    }

    companion object {
        fun nouvelleInstance(): PaiementsFragment = PaiementsFragment()
    }
}
