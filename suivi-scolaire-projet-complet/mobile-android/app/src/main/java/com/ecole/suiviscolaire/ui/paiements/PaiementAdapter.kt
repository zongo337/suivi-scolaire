package com.ecole.suiviscolaire.ui.paiements

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.ecole.suiviscolaire.R
import com.ecole.suiviscolaire.data.model.Paiement
import java.text.NumberFormat
import java.util.Locale

class PaiementAdapter(
    private val onVoirRecu: (Paiement) -> Unit
) : ListAdapter<Paiement, PaiementAdapter.PaiementViewHolder>(DIFF_CALLBACK) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): PaiementViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_paiement, parent, false)
        return PaiementViewHolder(view)
    }

    override fun onBindViewHolder(holder: PaiementViewHolder, position: Int) {
        holder.bind(getItem(position), onVoirRecu)
    }

    class PaiementViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val textViewMontant: TextView = itemView.findViewById(R.id.textViewMontant)
        private val textViewDate: TextView = itemView.findViewById(R.id.textViewDatePaiement)
        private val textViewReference: TextView = itemView.findViewById(R.id.textViewReference)
        private val textViewObservation: TextView = itemView.findViewById(R.id.textViewObservation)
        private val buttonVoirRecu: Button = itemView.findViewById(R.id.buttonVoirRecu)

        fun bind(paiement: Paiement, onVoirRecu: (Paiement) -> Unit) {
            val formatteur = NumberFormat.getNumberInstance(Locale.FRANCE)
            textViewMontant.text = "${formatteur.format(paiement.montant)} FCFA"
            textViewDate.text = paiement.datePaiement ?: "—"
            textViewReference.text = paiement.reference ?: "—"

            if (!paiement.observation.isNullOrBlank()) {
                textViewObservation.text = paiement.observation
                textViewObservation.visibility = View.VISIBLE
            } else {
                textViewObservation.visibility = View.GONE
            }

            buttonVoirRecu.setOnClickListener { onVoirRecu(paiement) }
        }
    }

    companion object {
        private val DIFF_CALLBACK = object : DiffUtil.ItemCallback<Paiement>() {
            override fun areItemsTheSame(oldItem: Paiement, newItem: Paiement): Boolean = oldItem.id == newItem.id
            override fun areContentsTheSame(oldItem: Paiement, newItem: Paiement): Boolean = oldItem == newItem
        }
    }
}
