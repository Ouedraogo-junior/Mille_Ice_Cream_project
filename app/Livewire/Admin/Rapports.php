<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Vente;
use App\Models\VenteItem;
use App\Models\User;
use App\Models\Categorie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class Rapports extends Component
{
    public $dateDebut;
    public $dateFin;
    
    // Statistiques principales
    public $chiffreAffaires = 0;
    public $nombreVentes = 0;
    public $panierMoyen = 0;
    public $produitsVendus = 0;
    public $evolutionCA = 0;
    
    // Données pour les graphiques
    public $topProduits;
    public $ventesParCategorie;
    public $performanceCaissiers;
    public $evolutionVentes;

    public function mount()
    {
        // Par défaut : ce mois
        $this->dateDebut = now()->startOfMonth()->format('Y-m-d');
        $this->dateFin = now()->format('Y-m-d');
        
        $this->chargerDonnees();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['dateDebut', 'dateFin'])) {
            $this->chargerDonnees();
        }
    }

    public function setToday()
    {
        $this->dateDebut = now()->format('Y-m-d');
        $this->dateFin = now()->format('Y-m-d');
        $this->chargerDonnees();
    }

    public function setWeek()
    {
        $this->dateDebut = now()->startOfWeek()->format('Y-m-d');
        $this->dateFin = now()->endOfWeek()->format('Y-m-d');
        $this->chargerDonnees();
    }

    public function setMonth()
    {
        $this->dateDebut = now()->startOfMonth()->format('Y-m-d');
        $this->dateFin = now()->format('Y-m-d');
        $this->chargerDonnees();
    }

    public function chargerDonnees()
    {
        // Vérifier si la table ventes existe
        if (!Schema::hasTable('ventes')) {
            $this->resetStats();
            return;
        }

        // Statistiques principales
        $ventes = Vente::whereBetween('created_at', [
            $this->dateDebut . ' 00:00:00',
            $this->dateFin . ' 23:59:59'
        ])->get();

        $this->nombreVentes = $ventes->count();
        $this->chiffreAffaires = $ventes->sum('montant');
        $this->panierMoyen = $this->nombreVentes > 0 
            ? $this->chiffreAffaires / $this->nombreVentes 
            : 0;

        // Nombre de produits vendus
        $this->produitsVendus = VenteItem::whereIn('vente_id', $ventes->pluck('id'))
            ->sum('quantite');

        // Évolution du CA (comparaison avec période précédente)
        $joursDiff = Carbon::parse($this->dateDebut)
            ->diffInDays(Carbon::parse($this->dateFin)) + 1;
        
        $datePrecedenteDebut = Carbon::parse($this->dateDebut)
            ->subDays($joursDiff)->format('Y-m-d');
        $datePrecedenteFin = Carbon::parse($this->dateFin)
            ->subDays($joursDiff)->format('Y-m-d');
        
        $caPrecedent = Vente::whereBetween('created_at', [
            $datePrecedenteDebut . ' 00:00:00',
            $datePrecedenteFin . ' 23:59:59'
        ])->sum('montant');

        $this->evolutionCA = $caPrecedent > 0 
            ? (($this->chiffreAffaires - $caPrecedent) / $caPrecedent * 100) 
            : 0;

        // Top 10 produits
        $this->topProduits = VenteItem::select(
                'variants.nom',
                DB::raw('SUM(vente_items.quantite) as total_vendus'),
                DB::raw('SUM(vente_items.prix_unitaire * vente_items.quantite) as total_ca')
            )
            ->join('ventes', 'vente_items.vente_id', '=', 'ventes.id')
            ->join('variants', 'vente_items.variant_id', '=', 'variants.id')
            ->whereBetween('ventes.created_at', [
                $this->dateDebut . ' 00:00:00',
                $this->dateFin . ' 23:59:59'
            ])
            ->groupBy('variants.id', 'variants.nom')
            ->orderByDesc('total_ca')
            ->limit(10)
            ->get();

        // Ventes par catégorie
        $this->ventesParCategorie = VenteItem::select(
                'categorie.nom',
                'categorie.couleur',
                DB::raw('SUM(vente_items.prix_unitaire * vente_items.quantite) as total_ca')
            )
            ->join('ventes', 'vente_items.vente_id', '=', 'ventes.id')
            ->join('variants', 'vente_items.variant_id', '=', 'variants.id')
            ->join('produit', 'variants.produit_id', '=', 'produit.id')
            ->join('categorie', 'produit.categorie_id', '=', 'categorie.id')
            ->whereBetween('ventes.created_at', [
                $this->dateDebut . ' 00:00:00',
                $this->dateFin . ' 23:59:59'
            ])
            ->groupBy('categorie.id', 'categorie.nom', 'categorie.couleur')
            ->orderByDesc('total_ca')
            ->get();

        // Performance des caissiers
        $this->performanceCaissiers = Vente::select(
                'users.name',
                DB::raw('COUNT(ventes.id) as nombre_ventes'),
                DB::raw('SUM(ventes.montant) as total_ca')
            )
            ->join('users', 'ventes.user_id', '=', 'users.id')
            ->whereBetween('ventes.created_at', [
                $this->dateDebut . ' 00:00:00',
                $this->dateFin . ' 23:59:59'
            ])
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_ca')
            ->get();

        // Évolution des ventes par jour
        $this->evolutionVentes = Vente::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as nombre'),
                DB::raw('SUM(montant) as total')
            )
            ->whereBetween('created_at', [
                $this->dateDebut . ' 00:00:00',
                $this->dateFin . ' 23:59:59'
            ])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
    }

    private function resetStats()
    {
        $this->chiffreAffaires = 0;
        $this->nombreVentes = 0;
        $this->panierMoyen = 0;
        $this->produitsVendus = 0;
        $this->evolutionCA = 0;
        $this->topProduits = collect();
        $this->ventesParCategorie = collect();
        $this->performanceCaissiers = collect();
        $this->evolutionVentes = collect();
    }

    public function exporterPDF()
    {
        // À implémenter avec dompdf ou autre
        $this->dispatch('toast', 'Export PDF en cours de développement');
    }

    public function exporterExcel()
    {
        // À implémenter avec Laravel Excel
        $this->dispatch('toast', 'Export Excel en cours de développement');
    }

    public function render()
    {
        return view('livewire.admin.rapports')->layout('layouts.admin');
    }
}