package com.ecole.suiviscolaire.ui.notes

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.ecole.suiviscolaire.R
import com.ecole.suiviscolaire.data.model.MatiereNotes

class MatiereAdapter : ListAdapter<MatiereNotes, MatiereAdapter.MatiereViewHolder>(DIFF_CALLBACK) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): MatiereViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_matiere_notes, parent, false)
        return MatiereViewHolder(view)
    }

    override fun onBindViewHolder(holder: MatiereViewHolder, position: Int) {
        holder.bind(getItem(position))
    }

    class MatiereViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val textViewNom: TextView = itemView.findViewById(R.id.textViewNomMatiere)
        private val textViewCoefficient: TextView = itemView.findViewById(R.id.textViewCoefficient)
        private val textViewMoyenne: TextView = itemView.findViewById(R.id.textViewMoyenneMatiere)
        private val recyclerViewNotes: RecyclerView = itemView.findViewById(R.id.recyclerViewNotesMatiere)
        private val notesAdapter = NoteParTrimestreAdapter()

        init {
            recyclerViewNotes.layoutManager = LinearLayoutManager(itemView.context)
            recyclerViewNotes.adapter = notesAdapter
            recyclerViewNotes.setHasFixedSize(true)
        }

        fun bind(matiereNotes: MatiereNotes) {
            textViewNom.text = matiereNotes.matiere.nom
            textViewCoefficient.text = "Coefficient ${matiereNotes.matiere.coefficient}"
            val sur = matiereNotes.matiere.noteSur ?: 10
            textViewMoyenne.text = "${formatNote(matiereNotes.moyenneMatiere)}/$sur"
            notesAdapter.submitList(matiereNotes.notes)
        }

        private fun formatNote(valeur: Double): String =
            if (valeur == valeur.toLong().toDouble()) valeur.toLong().toString() else valeur.toString()
    }

    companion object {
        private val DIFF_CALLBACK = object : DiffUtil.ItemCallback<MatiereNotes>() {
            override fun areItemsTheSame(oldItem: MatiereNotes, newItem: MatiereNotes): Boolean =
                oldItem.matiere.id == newItem.matiere.id

            override fun areContentsTheSame(oldItem: MatiereNotes, newItem: MatiereNotes): Boolean =
                oldItem == newItem
        }
    }
}
