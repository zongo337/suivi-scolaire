package com.ecole.suiviscolaire.ui.annonces

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.ecole.suiviscolaire.R
import com.ecole.suiviscolaire.data.model.Annonce

class AnnonceAdapter : ListAdapter<Annonce, AnnonceAdapter.AnnonceViewHolder>(DIFF_CALLBACK) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AnnonceViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_annonce, parent, false)
        return AnnonceViewHolder(view)
    }

    override fun onBindViewHolder(holder: AnnonceViewHolder, position: Int) {
        holder.bind(getItem(position))
    }

    class AnnonceViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val textViewTitre: TextView = itemView.findViewById(R.id.textViewTitreAnnonce)
        private val textViewContenu: TextView = itemView.findViewById(R.id.textViewContenuAnnonce)
        private val textViewDate: TextView = itemView.findViewById(R.id.textViewDateAnnonce)
        private val badgeType: TextView = itemView.findViewById(R.id.badgeTypeAnnonce)
        private val badgeClasse: TextView = itemView.findViewById(R.id.badgeClasseAnnonce)

        fun bind(annonce: Annonce) {
            textViewTitre.text = annonce.titre
            textViewContenu.text = annonce.contenu
            textViewDate.text = annonce.datePublication ?: ""

            if (annonce.estNotification) {
                badgeType.text = "Notification"
                badgeType.setBackgroundResource(R.drawable.bg_badge_warning)
                badgeType.setTextColor(ContextCompat.getColor(itemView.context, R.color.orange))
            } else {
                badgeType.text = "Annonce"
                badgeType.setBackgroundResource(R.drawable.bg_badge_info)
                badgeType.setTextColor(ContextCompat.getColor(itemView.context, R.color.blue))
            }

            if (annonce.classe != null) {
                badgeClasse.text = annonce.classe
                badgeClasse.visibility = View.VISIBLE
            } else {
                badgeClasse.text = "Toute l'école"
                badgeClasse.visibility = View.VISIBLE
            }
        }
    }

    companion object {
        private val DIFF_CALLBACK = object : DiffUtil.ItemCallback<Annonce>() {
            override fun areItemsTheSame(oldItem: Annonce, newItem: Annonce): Boolean = oldItem.id == newItem.id
            override fun areContentsTheSame(oldItem: Annonce, newItem: Annonce): Boolean = oldItem == newItem
        }
    }
}
