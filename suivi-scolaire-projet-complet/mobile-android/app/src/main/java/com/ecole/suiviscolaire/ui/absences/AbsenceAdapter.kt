package com.ecole.suiviscolaire.ui.absences

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.ecole.suiviscolaire.R
import com.ecole.suiviscolaire.data.model.Absence

class AbsenceAdapter : ListAdapter<Absence, AbsenceAdapter.AbsenceViewHolder>(DIFF_CALLBACK) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AbsenceViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_absence, parent, false)
        return AbsenceViewHolder(view)
    }

    override fun onBindViewHolder(holder: AbsenceViewHolder, position: Int) {
        holder.bind(getItem(position))
    }

    class AbsenceViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val textViewDate: TextView = itemView.findViewById(R.id.textViewDateAbsence)
        private val textViewMotif: TextView = itemView.findViewById(R.id.textViewMotifAbsence)
        private val badgeStatut: TextView = itemView.findViewById(R.id.badgeStatutAbsence)

        fun bind(absence: Absence) {
            textViewDate.text = absence.dateAbsence
            textViewMotif.text = absence.motif ?: "Motif non renseigné"

            if (absence.justifiee) {
                badgeStatut.text = "Justifiée"
                badgeStatut.setBackgroundResource(R.drawable.bg_badge_success)
                badgeStatut.setTextColor(ContextCompat.getColor(itemView.context, R.color.green_dark))
            } else {
                badgeStatut.text = "Non justifiée"
                badgeStatut.setBackgroundResource(R.drawable.bg_badge_warning)
                badgeStatut.setTextColor(ContextCompat.getColor(itemView.context, R.color.orange))
            }
        }
    }

    companion object {
        private val DIFF_CALLBACK = object : DiffUtil.ItemCallback<Absence>() {
            override fun areItemsTheSame(oldItem: Absence, newItem: Absence): Boolean = oldItem.id == newItem.id
            override fun areContentsTheSame(oldItem: Absence, newItem: Absence): Boolean = oldItem == newItem
        }
    }
}
