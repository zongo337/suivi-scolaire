package com.ecole.suiviscolaire.ui.dashboard

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.ecole.suiviscolaire.R
import com.ecole.suiviscolaire.data.model.Note

class DernieresNotesAdapter : ListAdapter<Note, DernieresNotesAdapter.NoteViewHolder>(DIFF_CALLBACK) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): NoteViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_note_chip, parent, false)
        return NoteViewHolder(view)
    }

    override fun onBindViewHolder(holder: NoteViewHolder, position: Int) {
        holder.bind(getItem(position))
    }

    class NoteViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val textViewMatiere: TextView = itemView.findViewById(R.id.textViewTrimestre)
        private val textViewNote: TextView = itemView.findViewById(R.id.textViewValeurNote)

        fun bind(note: Note) {
            textViewMatiere.text = note.matiere?.nom ?: "—"
            val sur = note.noteSur ?: 10
            textViewNote.text = "${formatNote(note.note)}/$sur"
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
