package com.ecole.suiviscolaire.ui.notes

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.ecole.suiviscolaire.R
import com.ecole.suiviscolaire.data.model.Note

/**
 * Adapter affiché à l'intérieur de chaque carte "matière" : une ligne
 * par trimestre où l'élève a une note dans cette matière.
 */
class NoteParTrimestreAdapter : ListAdapter<Note, NoteParTrimestreAdapter.NoteViewHolder>(DIFF_CALLBACK) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): NoteViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_note_chip, parent, false)
        return NoteViewHolder(view)
    }

    override fun onBindViewHolder(holder: NoteViewHolder, position: Int) {
        holder.bind(getItem(position))
    }

    class NoteViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val textViewLabel: TextView = itemView.findViewById(R.id.textViewTrimestre)
        private val textViewValeur: TextView = itemView.findViewById(R.id.textViewValeurNote)

        fun bind(note: Note) {
            textViewLabel.text = "Trimestre ${note.trimestre}"
            val sur = note.noteSur ?: 10
            textViewValeur.text = "${formatNote(note.note)}/$sur"
        }

        private fun formatNote(valeur: Double): String =
            if (valeur == valeur.toLong().toDouble()) valeur.toLong().toString() else valeur.toString()
    }

    companion object {
        private val DIFF_CALLBACK = object : DiffUtil.ItemCallback<Note>() {
            override fun areItemsTheSame(oldItem: Note, newItem: Note): Boolean = oldItem.id == newItem.id
            override fun areContentsTheSame(oldItem: Note, newItem: Note): Boolean = oldItem == newItem
        }
    }
}
