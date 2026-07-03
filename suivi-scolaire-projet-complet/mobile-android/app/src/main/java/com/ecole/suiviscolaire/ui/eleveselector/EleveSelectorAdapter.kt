package com.ecole.suiviscolaire.ui.eleveselector

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.ecole.suiviscolaire.R
import com.ecole.suiviscolaire.data.model.Eleve

class EleveSelectorAdapter(
    private val onEleveClick: (Eleve) -> Unit
) : ListAdapter<Eleve, EleveSelectorAdapter.EleveViewHolder>(DIFF_CALLBACK) {

    var selectedEleveId: Int? = null
        set(value) {
            field = value
            notifyDataSetChanged()
        }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): EleveViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_eleve_selector, parent, false)
        return EleveViewHolder(view)
    }

    override fun onBindViewHolder(holder: EleveViewHolder, position: Int) {
        holder.bind(getItem(position), getItem(position).id == selectedEleveId)
    }

    inner class EleveViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val textViewInitiales: TextView = itemView.findViewById(R.id.textViewInitiales)
        private val textViewPrenom: TextView = itemView.findViewById(R.id.textViewPrenomEleve)

        fun bind(eleve: Eleve, selectionne: Boolean) {
            val initiales = buildString {
                if (eleve.prenom.isNotEmpty()) append(eleve.prenom[0])
                if (eleve.nom.isNotEmpty()) append(eleve.nom[0])
            }.uppercase()

            textViewInitiales.text = initiales
            textViewPrenom.text = eleve.prenom

            itemView.setOnClickListener { onEleveClick(eleve) }

            // Mise en évidence visuelle de l'élève actuellement sélectionné
            itemView.alpha = if (selectionne) 1.0f else 0.55f
        }
    }

    companion object {
        private val DIFF_CALLBACK = object : DiffUtil.ItemCallback<Eleve>() {
            override fun areItemsTheSame(oldItem: Eleve, newItem: Eleve): Boolean =
                oldItem.id == newItem.id

            override fun areContentsTheSame(oldItem: Eleve, newItem: Eleve): Boolean =
                oldItem == newItem
        }
    }
}
