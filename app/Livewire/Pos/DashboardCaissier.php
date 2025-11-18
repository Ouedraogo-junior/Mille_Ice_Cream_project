<?php

namespace App\Livewire\Pos;

use App\Models\Vente;
use App\Models\VenteDetail;
use App\Models\Produit;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardCaissier extends Component
{
    public string $periode = 'today'; // today, week, month
    public ?string $messageSucces = null;

    /**
     * Statistiques du jour pour le caissier connecté
     */
    #[Computed]
    public function statistiquesJour()
    {
        $debut = Carbon::today();
        $fin = Carbon::tomorrow();

        return $this->getStatistiques($debut, $fin);
    }

    /**
     * Afficher les détails d'une vente
     */
public function afficherDetails($venteId)
{
    $this->venteSelectionnee = $venteId;
    $this->showModal = true;
}

    /**
     * Statistiques de la semaine
     */
    #[Computed]
    public function statistiquesSemaine()
    {
        $debut = Carbon::now()->startOfWeek();
        $fin = Carbon::now()->endOfWeek();

        return $this->getStatistiques($debut, $fin);
    }

    /**
     * Statistiques du mois
     */
    #[Computed]
    public function statistiquesMois()
    {
        $debut = Carbon::now()->startOfMonth();
        $fin = Carbon::now()->endOfMonth();

        return $this->getStatistiques($debut, $fin);
    }

    /**
     * Méthode générique pour obtenir les statistiques
     */
    private function getStatistiques($debut, $fin)
    {
        $ventes = Vente::where('user_id', auth()->id())
            ->where('est_annulee', false)
            ->whereBetween('date_vente', [$debut, $fin]);

        $total = $ventes->sum('total');
        $nombre = $ventes->count();

        return [
            'nombre_ventes' => $nombre,
            'chiffre_affaires' => $total,
            'panier_moyen' => $nombre > 0 ? $total / $nombre : 0,
            'ventes_espece' => (clone $ventes)->where('mode_paiement', 'espece')->count(),
            'ventes_mobile' => (clone $ventes)->where('mode_paiement', 'mobile')->count(),
            'ventes_carte' => (clone $ventes)->where('mode_paiement', 'carte')->count(),
            'ca_espece' => (clone $ventes)->where('mode_paiement', 'espece')->sum('total'),
            'ca_mobile' => (clone $ventes)->where('mode_paiement', 'mobile')->sum('total'),
            'ca_carte' => (clone $ventes)->where('mode_paiement', 'carte')->sum('total'),
        ];
    }

    /**
     * Dernières ventes du caissier
     */
    #[Computed]
    public function dernieresVentes()
    {
        return Vente::where('user_id', auth()->id())
            ->where('est_annulee', false)
            ->with(['details.produit'])
            ->latest('date_vente')
            ->take(8)
            ->get();
    }

    /**
     * Top 5 produits les plus vendus aujourd'hui
     */
    #[Computed]
    public function topProduits()
    {
        return VenteDetail::whereHas('vente', function($q) {
                $q->where('user_id', auth()->id())
                  ->where('est_annulee', false)
                  ->whereDate('date_vente', today());
            })
            ->select('produit_id', DB::raw('SUM(quantite) as total_quantite'), DB::raw('SUM(sous_total) as total_ca'))
            ->with('produit')
            ->groupBy('produit_id')
            ->orderByDesc('total_quantite')
            ->take(5)
            ->get();
    }

    /**
     * Ventes par heure (aujourd'hui)
     */
    #[Computed]
    public function ventesParHeure()
    {
        $ventes = Vente::where('user_id', auth()->id())
            ->where('est_annulee', false)
            ->whereDate('date_vente', today())
            ->select(
                DB::raw('HOUR(date_vente) as heure'),
                DB::raw('COUNT(*) as nombre'),
                DB::raw('SUM(total) as montant')
            )
            ->groupBy('heure')
            ->orderBy('heure')
            ->get();

        // Remplir les heures manquantes avec 0
        $heures = collect(range(0, 23))->map(function($h) use ($ventes) {
            $vente = $ventes->firstWhere('heure', $h);
            return [
                'heure' => str_pad($h, 2, '0', STR_PAD_LEFT) . 'h',
                'nombre' => $vente ? $vente->nombre : 0,
                'montant' => $vente ? $vente->montant : 0,
            ];
        });

        return $heures;
    }

    /**
     * Objectif du jour (peut être configuré)
     */
    public function getObjectifJourProperty()
    {
        // Vous pouvez stocker cet objectif en config ou en BDD
        return config('pos.objectif_journalier', 50000);
    }

    /**
     * Progression vers l'objectif
     */
    public function getProgressionObjectifProperty()
    {
        $stats = $this->statistiquesJour;
        return $this->objectifJour > 0 
            ? min(100, ($stats['chiffre_affaires'] / $this->objectifJour) * 100)
            : 0;
    }

    /**
     * Changer de période
     */
    public function changerPeriode($periode)
    {
        $this->periode = $periode;
    }

    /**
     * Rafraîchir le dashboard
     */
    public function rafraichir()
    {
        // Force le recalcul de toutes les propriétés computed
        unset(
            $this->statistiquesJour,
            $this->statistiquesSemaine,
            $this->statistiquesMois,
            $this->dernieresVentes,
            $this->topProduits,
            $this->ventesParHeure
        );
        
        $this->messageSucces = 'Dashboard mis à jour';
    }

    /**
     * Effacer le message
     */
    public function effacerMessage()
    {
        $this->messageSucces = null;
    }

    /**
     * Render
     */
    public function render()
    {
        return view('livewire.pos.dashboard-caissier')
            ->layout('layouts.caissier');
    }
}