<?php

namespace App\Livewire\Pos;

use App\Models\Vente;
use App\Models\VenteDetail;
use App\Models\Produit;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Objectif;

class DashboardCaissier extends Component
{
    public string $periode = 'today'; // today, week, month
    public ?string $messageSucces = null;

    // Propriétés pour la modale de détails de vente
    public $venteSelectionnee = null;
    public $showModal = false;

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
        $this->venteSelectionnee = Vente::with('details.produit')->find($venteId);
        $this->showModal = true;
    }

    /**
     * Fermer la modale
     */
    public function fermerModal()
    {
        $this->showModal = false;
        $this->venteSelectionnee = null;
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
        $driver = DB::getDriverName();

        // Expression heure selon le SGBD
        $hourExpression = match ($driver) {
            'sqlite' => "strftime('%H', date_vente)", // SQLite
            'pgsql' => "to_char(date_vente, 'HH24')",  // PostgreSQL
            default => "HOUR(date_vente)",              // MySQL
        };

        $ventes = Vente::where('user_id', auth()->id())
            ->where('est_annulee', false)
            ->whereDate('date_vente', today())
            ->select(
                DB::raw("$hourExpression as heure"),
                DB::raw('COUNT(*) as nombre'),
                DB::raw('SUM(total) as montant')
            )
            ->groupBy('heure')
            ->orderBy('heure')
            ->get();

        // Remplir les heures manquantes
        $heures = collect(range(0, 23))->map(function($h) use ($ventes) {
            $key = str_pad($h, 2, '0', STR_PAD_LEFT); // '00', '01', '02', ...
            $vente = $ventes->firstWhere('heure', $key);

            return [
                'heure' => $key . 'h',
                'nombre' => $vente ? $vente->nombre : 0,
                'montant' => $vente ? $vente->montant : 0,
            ];
        });

        return $heures;
    }


    /**
     * Objectif du jour (peut être configuré)
     */
   public function getObjectifQuotidienProperty()
{
    // On prend l'objectif journalier dont la date de début est aujourd'hui
    // (c'est la convention la plus utilisée)
    return Objectif::where('type', 'journalier')
        ->whereDate('date_debut', Carbon::today())
        ->first();
}

    public function getCaGlobalProperty()
    {
        // Calcule le total de TOUTES les ventes du magasin aujourd'hui
        return Vente::whereDate('date_vente', Carbon::today())->sum('total');
    }

    /**
     * Pourcentage de l'objectif basé sur le CA du magasin (logique actuelle)
     */
    public function getPourcentageObjectifProperty()  // ← maintenant $this->pourcentageObjectif existe !
{
    if (!$this->objectifQuotidien || $this->objectifQuotidien->objectif <= 0) {
        return 0;
    }
    return min(100, ($this->caGlobal / $this->objectifQuotidien->objectif) * 100);
}
    
    /**
     * Pourcentage de l'objectif basé sur le CA personnel du caissier (Suggestion)
     */
    public function getPourcentageObjectifPersonnelProperty()
    {
        if (!$this->objectifQuotidien || $this->objectifQuotidien->objectif <= 0) {
            return 0;
        }
        $caPersonnel = $this->statistiquesJour()['chiffre_affaires'] ?? 0;
        return min(100, ($caPersonnel / $this->objectifQuotidien->objectif) * 100);
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
        
        $this->messageSucces = 'Tableau de bord mis à jour';
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