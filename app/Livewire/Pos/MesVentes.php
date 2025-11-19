<?php

namespace App\Livewire\Pos;

use App\Models\Vente;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Carbon\Carbon;

use Illuminate\Support\Facades\Gate;

class MesVentes extends Component
{
    use WithPagination;

    // Filtres
    public string $dateDebut = '';
    public string $dateFin = '';
    public string $modePaiementFiltre = 'tous';
    public string $recherche = '';
    
    // UI States
    public ?int $venteSelectionnee = null;
    public bool $showDetails = false;
    // Permettre à l'admin de voir l'historique d'un caissier via query string
    public ?int $userId = null;
    protected $queryString = ['userId'];
    
    // Messages
    public ?string $messageSucces = null;
    public ?string $messageErreur = null;

    /**
     * Initialisation du composant
     */
    public function mount()
    {
        // Par défaut : aujourd'hui
        $this->dateDebut = today()->format('Y-m-d');
        $this->dateFin = today()->format('Y-m-d');
    }

    /**
     * Liste des ventes avec filtres
     */
    #[Computed]
    public function ventes()
    {
        $visibleUserId = auth()->id();
        if ($this->userId && auth()->user() && auth()->user()->isAdmin()) {
            $visibleUserId = $this->userId;
        }

        $query = Vente::query()
            ->where('user_id', $visibleUserId)
            ->where('est_annulee', false)
            ->with(['details.produit', 'details.variant', 'caissier']);

        // Filtre par dates
        if ($this->dateDebut) {
            $query->whereDate('date_vente', '>=', $this->dateDebut);
        }
        if ($this->dateFin) {
            $query->whereDate('date_vente', '<=', $this->dateFin);
        }

        // Filtre par mode de paiement
        if ($this->modePaiementFiltre !== 'tous') {
            $query->where('mode_paiement', $this->modePaiementFiltre);
        }

        // Recherche par numéro de ticket
        if ($this->recherche) {
            $query->where('numero_ticket', 'like', '%' . $this->recherche . '%');
        }

        return $query->latest('date_vente')
            ->paginate(20);
    }

    /**
     * Détails d'une vente spécifique
     */
    #[Computed]
    public function venteDetails()
    {
        if (!$this->venteSelectionnee) {
            return null;
        }

        $vente = Vente::with(['details.produit', 'details.variant', 'caissier'])
            ->find($this->venteSelectionnee);

        // Si l'admin consulte l'historique pour un autre caissier, autoriser.
        if ($vente && $this->userId && auth()->user() && auth()->user()->isAdmin()) {
            return $vente;
        }

        // Par défaut, n'autoriser que le propriétaire
        if ($vente && $vente->user_id === auth()->id()) {
            return $vente;
        }

        return null;
    }

    /**
     * Statistiques de la période sélectionnée
     */
    #[Computed]
    public function statistiquesPeriode()
    {
        $visibleUserId = auth()->id();
        if ($this->userId && auth()->user() && auth()->user()->isAdmin()) {
            $visibleUserId = $this->userId;
        }

        $query = Vente::query()
            ->where('user_id', $visibleUserId)
            ->where('est_annulee', false);

        if ($this->dateDebut) {
            $query->whereDate('date_vente', '>=', $this->dateDebut);
        }
        if ($this->dateFin) {
            $query->whereDate('date_vente', '<=', $this->dateFin);
        }

        return [
            'nombre_ventes' => $query->count(),
            'total_ca' => $query->sum('total'),
            'panier_moyen' => $query->count() > 0 ? $query->sum('total') / $query->count() : 0,
            'ventes_espece' => (clone $query)->where('mode_paiement', 'espece')->count(),
            'ventes_mobile' => (clone $query)->where('mode_paiement', 'mobile')->count(),
            'ventes_carte' => (clone $query)->where('mode_paiement', 'carte')->count(),
        ];
    }

    /**
     * Afficher les détails d'une vente
     */
    public function afficherDetails(int $venteId)
    {
        $this->venteSelectionnee = $venteId;
        $this->showDetails = true;
    }

    /**
     * Fermer le modal de détails
     */
    public function fermerDetails()
    {
        $this->showDetails = false;
        $this->venteSelectionnee = null;
    }

    /**
     * Réimprimer un ticket
     */
    public function reimprimerTicket(int $venteId)
    {
        $vente = Vente::find($venteId);
        if (!$vente) {
            $this->messageErreur = 'Vente introuvable';
            return;
        }

        // Autoriser la réimpression si propriétaire ou si admin
        if ($vente->user_id !== auth()->id() && !(auth()->user() && auth()->user()->isAdmin())) {
            $this->messageErreur = 'Accès non autorisé';
            return;
        }

        $this->dispatch('imprimer-ticket', venteId: $venteId);
        $this->messageSucces = 'Impression du ticket N° ' . $vente->numero_ticket;
    }

    /**
     * Définir la période "Aujourd'hui"
     */
    public function periodeAujourdhui()
    {
        $this->dateDebut = today()->format('Y-m-d');
        $this->dateFin = today()->format('Y-m-d');
        $this->resetPage();
    }

    /**
     * Définir la période "Cette semaine"
     */
    public function periodeSemaine()
    {
        $this->dateDebut = now()->startOfWeek()->format('Y-m-d');
        $this->dateFin = now()->endOfWeek()->format('Y-m-d');
        $this->resetPage();
    }

    /**
     * Définir la période "Ce mois"
     */
    public function periodeMois()
    {
        $this->dateDebut = now()->startOfMonth()->format('Y-m-d');
        $this->dateFin = now()->endOfMonth()->format('Y-m-d');
        $this->resetPage();
    }

    /**
     * Réinitialiser tous les filtres
     */
    public function resetFiltres()
    {
        $this->dateDebut = today()->format('Y-m-d');
        $this->dateFin = today()->format('Y-m-d');
        $this->modePaiementFiltre = 'tous';
        $this->recherche = '';
        $this->resetPage();
    }

    /**
     * Rafraîchir la liste
     */
    public function rafraichir()
    {
        unset($this->ventes);
        unset($this->statistiquesPeriode);
        $this->messageSucces = 'Liste mise à jour';
    }

    /**
     * Observer les changements de filtres pour reset pagination
     */
    public function updatedDateDebut()
    {
        $this->resetPage();
    }

    public function updatedDateFin()
    {
        $this->resetPage();
    }

    public function updatedModePaiementFiltre()
    {
        $this->resetPage();
    }

    public function updatedRecherche()
    {
        $this->resetPage();
    }

    /**
     * Effacer les messages
     */
    public function effacerMessages()
    {
        $this->messageSucces = null;
        $this->messageErreur = null;
    }

    /**
     * Render
     */
    public function render()
    {
        return view('livewire.pos.mes-ventes')
            ->layout('layouts.caissier');
    }
}