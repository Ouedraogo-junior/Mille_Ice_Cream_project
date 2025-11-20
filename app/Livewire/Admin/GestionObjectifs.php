<?php

namespace App\Livewire\Admin;

use App\Models\Objectif;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GestionObjectifs extends Component
{
    // --- Gestion de l'interface ---
    public $afficherFormulaire = false;
    public $modeEdition = false;
    public $objectifEnCours = null;
    public $afficherModalSuppression = false;
    public $objectifASupprimer = null;

    // --- Champs du formulaire (Simplifiés) ---
    public $titre = '';
    public $description = '';
    public $objectif = ''; // Montant en FCFA
    public $date_fin = '';
    
    // --- Filtres ---
    public $filtreStatut = 'tous';

    protected function rules()
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'objectif' => 'required|numeric|min:1000', // Minimum 1000 F
            'date_fin' => 'required|date', // On accepte les dates passées pour correction si besoin
        ];
    }

    protected $messages = [
        'titre.required' => 'Donnez un nom à cet objectif.',
        'objectif.required' => 'Le montant à atteindre est obligatoire.',
        'objectif.min' => 'L\'objectif doit être d\'au moins 1000 FCFA.',
        'date_fin.required' => 'La date est requise.',
    ];

    public function mount()
    {
        // Par défaut, on propose la date d'aujourd'hui pour un objectif journalier
        $this->date_fin = now()->format('Y-m-d');
    }

    // --- Ouverture / Fermeture ---

    public function ouvrirFormulaire()
    {
        $this->resetFormulaire();
        
        // Pré-remplissage intelligent pour gagner du temps
        $this->titre = 'Objectif CA du ' . now()->translatedFormat('d F');
        $this->date_fin = now()->format('Y-m-d');
        
        $this->modeEdition = false;
        $this->afficherFormulaire = true;
    }

    public function resetFormulaire()
    {
        $this->reset([
            'titre',
            'description',
            'objectif',
            'afficherFormulaire', 
            'modeEdition', 
            'objectifEnCours'
        ]);
        $this->date_fin = now()->format('Y-m-d');
        $this->resetErrorBag();
    }

    // --- CRUD (Create, Read, Update, Delete) ---

    public function ajouterObjectif()
    {
        $this->validate();

        if ($this->modeEdition) {
            $this->mettreAJourObjectif();
            return;
        }

        // Optionnel : Nettoyer les anciens objectifs de type 'journalier' pour la même date
        // pour éviter d'avoir deux objectifs concurrents le même jour.
        Objectif::where('type', 'journalier')
            ->whereDate('date_fin', $this->date_fin)
            ->delete();

        Objectif::create([
            'titre' => $this->titre,
            'description' => $this->description,
            'objectif' => $this->objectif, // Montant cible
            'actuel' => 0, // Commence à 0, sera calculé par le Dashboard via les ventes
            'unite' => 'FCFA', // Forcé
            'type' => 'journalier', // Forcé pour détection auto
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => $this->date_fin,
            'statut' => 'en_cours',
            'cree_par' => Auth::id(),
        ]);

        $this->resetFormulaire();
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => '🚀 Objectif de vente fixé !'
        ]);
    }
    
    // NOTE: Il manque la méthode `mettreAJourProgression` référencée dans la vue liste-objectifs.blade.php
    public function mettreAJourProgression($id, $nouvelleValeur)
    {
        $obj = Objectif::findOrFail($id);
        $nouvelleValeur = (float) $nouvelleValeur;

        // Mise à jour de l'actuel
        $obj->actuel = $nouvelleValeur;
        
        // Recalcul du statut
        if ($obj->actuel >= $obj->objectif && $obj->statut !== 'atteint') {
            $obj->statut = 'atteint';
        } elseif ($obj->actuel < $obj->objectif && $obj->statut !== 'annule') {
            $obj->statut = 'en_cours';
        }
        
        $obj->save();
        
        $this->dispatch('toast', [
            'type' => 'info',
            'message' => 'Progression mise à jour !'
        ]);
    }
    
    // NOTE: Il manque les méthodes annulerObjectif et reactiverObjectif
    public function annulerObjectif($id)
    {
        $this->changerStatut($id, 'annule');
    }

    public function reactiverObjectif($id)
    {
        $this->changerStatut($id, 'en_cours');
    }

    public function editerObjectif($id)
    {
        $obj = Objectif::findOrFail($id);
        
        // Permissions (optionnel si seul l'admin a accès)
        if (auth()->user()->role !== 'admin' && $obj->cree_par !== auth()->id()) {
            $this->dispatch('toast', ['type' => 'error', 'message' => 'Accès non autorisé']);
            return;
        }

        $this->objectifEnCours = $id;
        $this->titre = $obj->titre;
        $this->description = $obj->description;
        $this->objectif = $obj->objectif; // Montant
        $this->date_fin = $obj->date_fin->format('Y-m-d');
        
        $this->modeEdition = true;
        $this->afficherFormulaire = true;
    }

    public function mettreAJourObjectif()
    {
        $this->validate();

        $obj = Objectif::findOrFail($this->objectifEnCours);
        
        $obj->update([
            'titre' => $this->titre,
            'description' => $this->description,
            'objectif' => $this->objectif,
            'date_fin' => $this->date_fin,
        ]);

        // Recalcul du statut si on modifie le montant
        if ($obj->actuel >= $this->objectif && $obj->statut !== 'atteint') {
            $obj->update(['statut' => 'atteint']);
        } elseif ($obj->actuel < $this->objectif && $obj->statut === 'atteint') {
            $obj->update(['statut' => 'en_cours']);
        }

        $this->resetFormulaire();
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => '✅ Objectif mis à jour !'
        ]);
    }

    public function confirmerSuppression($id)
    {
        $this->objectifASupprimer = $id;
        $this->afficherModalSuppression = true;
    }

    public function supprimerObjectif()
    {
        if ($this->objectifASupprimer) {
            $objectif = Objectif::findOrFail($this->objectifASupprimer);

             // Vérifier les permissions (règle de sécurité)
            if (auth()->user()->role !== 'admin' && $objectif->cree_par !== auth()->id()) {
                $this->dispatch('toast', ['type' => 'error', 'message' => 'Accès non autorisé à la suppression']);
                return;
            }

            $objectif->delete();
            
            $this->afficherModalSuppression = false;
            $this->objectifASupprimer = null;

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => '🗑️ Objectif supprimé'
            ]);
        }
    }

    // --- Changement de statut rapide ---

    public function changerStatut($id, $nouveauStatut)
    {
        $obj = Objectif::findOrFail($id);
        
        // Règle de sécurité : seul l'admin peut annuler/réactiver
        if (auth()->user()->role !== 'admin' && ($nouveauStatut === 'annule' || $nouveauStatut === 'en_cours')) {
            $this->dispatch('toast', ['type' => 'error', 'message' => 'Seul l\'administrateur peut faire cette action']);
            return;
        }

        $obj->update(['statut' => $nouveauStatut]);
        
        $this->dispatch('toast', ['type' => 'info', 'message' => 'Statut mis à jour']);
    }

    // --- Rendu ---

    public function render()
    {
        $query = Objectif::with('createur');

        // Appliquer les filtres
        if ($this->filtreStatut !== 'tous') {
            $query->where('statut', $this->filtreStatut);
        }

        // On affiche les objectifs du plus récent au plus ancien (date_fin)
        $objectifs = $query->orderBy('date_fin', 'desc')->get();

        // --- CALCULE DES STATISTIQUES COMPLÈTES (basé sur la BD pour le header) ---
        $totalAtteints = Objectif::where('statut', 'atteint')->count();
        $totalEnCours = Objectif::where('statut', 'en_cours')->count();
        $totalActifs = $totalAtteints + $totalEnCours;
        
        // Récupération de la collection des objectifs ATTEINTS pour le Hall of Fame
        $objectifsAtteints = Objectif::where('statut', 'atteint')
                                    ->orderBy('updated_at', 'desc')
                                    ->limit(12) // Limiter à 12 pour le Hall of Fame
                                    ->get();

        $stats = [
            'total' => Objectif::count(),
            'en_cours' => $totalEnCours,
            'atteints' => $totalAtteints,
            // Rétablissement du calcul du taux de réussite pour la vue header
            'taux_reussite' => $totalActifs > 0
                ? round(($totalAtteints / $totalActifs) * 100)
                : 0,
        ];

        return view('livewire.admin.gestion-objectifs', [
            'objectifs' => $objectifs,
            'statistiques' => $stats,
            'atteints' => $objectifsAtteints, // <-- NOUVEAU : Passons la collection à la vue
        ])->layout('layouts.admin');
    }
}