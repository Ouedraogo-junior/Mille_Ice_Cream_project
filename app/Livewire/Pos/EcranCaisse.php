<?php

namespace App\Livewire\Pos;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Vente;
use App\Models\VenteDetail;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Events\StockAlerteEvent;

class EcranCaisse extends Component
{
    // Panier
    public array $panier = [];
    
    // Filtres et recherche
    public ?int $categorieSelectionnee = null;
    public string $recherche = '';
    
    // Mode de paiement
    public string $modePaiement = 'espece';
    // Paiement en espèces : somme encaissée par le client
    // Note: remove typed property to avoid Livewire compatibility issues with some versions
    public $sommeEncaissee = 0;
    
    // UI States
    public bool $showConfirmation = false;
    public ?int $derniereVenteId = null;
    public bool $isProcessing = false;
    
    // Messages
    public ?string $messageSucces = null;
    public ?string $messageErreur = null;

    // Offline mode states
    public bool $isOfflineMode = false;
    public int $ventesPendingCount = 0;
    /**
     * Chargement initial du composant
     */
    public function mount()
    {
        // Initialiser le panier vide
        $this->panier = [];
        $this->categorieSelectionnee = null;

        // Vérifier le mode offline (via JavaScript)
        $this->dispatch('check-offline-status');
    }

    /**
     * Produits filtrés selon catégorie et recherche
     */
    #[Computed]
    public function produits()
    {
        // On ne retourne que les produits qui ont au moins une variante active
        return Produit::with(['variants' => function($q) {
            $q->where('active', true);
        }])
        ->when($this->categorieSelectionnee, function ($query) {
            $query->where('categorie_id', $this->categorieSelectionnee);
        })
        ->when($this->recherche, function ($query) {
            $query->where(function ($q) {
                $q->where('nom', 'like', '%' . $this->recherche . '%')
                  ->orWhere('description', 'like', '%' . $this->recherche . '%');
            });
        })
        ->whereHas('variants', function($q) { $q->where('active', true); })
        ->orderBy('nom')
        ->get();
    }

    /**
     * Liste des catégories
     */
    #[Computed]
    public function categories()
    {
        return Categorie::where('active', true)
            ->orderBy('nom')
            ->get();
    }

    /**
     * Calcul du total du panier
     */
    #[Computed]
    public function totalPanier()
    {
        return collect($this->panier)->sum(function ($item) {
            return $item['prix'] * $item['quantite'];
        });
    }

    /**
     * Nombre total d'articles dans le panier
     */
    #[Computed]
    public function nombreArticles()
    {
        return collect($this->panier)->sum('quantite');
    }

    /**
     * CORRECTION ICI : Calcul de la monnaie à rendre
     * Utiliser getMonnaieProperty() au lieu de monnaie()
     */
    public function getMonnaieProperty()
    {
        if ($this->modePaiement !== 'espece') {
            return 0;
        }
        
        $change = floatval($this->sommeEncaissee) - $this->totalPanier;
        return $change > 0 ? $change : 0;
    }

    /**
     * Ajouter un produit au panier
     */
    public function ajouterAuPanier(int $produitId)
    {
        $produit = Produit::with(['variants' => function($q) { $q->where('active', true); }])->find($produitId);

        if (!$produit || $produit->variants->isEmpty()) {
            $this->messageErreur = 'Produit non disponible';
            return;
        }

        $variant = $produit->variants->first();
        if (!$variant || !$variant->active) {
            $this->messageErreur = 'Aucune variante active pour ce produit';
            return;
        }

        // Vérifier le stock
        if ($variant->stock !== null && $variant->stock <= 0) {
            $this->messageErreur = 'Stock insuffisant pour ' . $produit->nom;
            return;
        }

        // Si le produit existe déjà dans le panier
        $cle = 'produit_' . $produitId;
        
        if (isset($this->panier[$cle])) {
            // Vérifier si on peut ajouter (stock)
            if ($variant->stock !== null && $this->panier[$cle]['quantite'] >= $variant->stock) {
                $this->messageErreur = 'Stock insuffisant pour ' . $produit->nom;
                return;
            }
            $this->panier[$cle]['quantite']++;
        } else {
            // Ajouter nouveau produit
            $this->panier[$cle] = [
                'id' => $produit->id,
                'nom' => $produit->nom,
                'prix' => $variant->prix,
                'quantite' => 1,
                'stock' => $variant->stock,
                'image' => $produit->image,
                'variant_id' => $variant->id,
            ];
        }

        $this->messageSucces = $produit->nom . ' ajouté au panier';
        $this->dispatch('produit-ajoute');
    }

    /**
     * Modifier la quantité d'un article
     */
    public function modifierQuantite(string $cle, int $nouvelleQuantite)
    {
        if (!isset($this->panier[$cle])) {
            return;
        }

        if ($nouvelleQuantite <= 0) {
            $this->retirerDuPanier($cle);
            return;
        }

        // Vérifier le stock
        $stock = $this->panier[$cle]['stock'];
        if ($stock !== null && $nouvelleQuantite > $stock) {
            $this->messageErreur = 'Stock insuffisant';
            return;
        }

        $this->panier[$cle]['quantite'] = $nouvelleQuantite;
    }

    /**
     * Retirer un produit du panier
     */
    public function retirerDuPanier(string $cle)
    {
        if (isset($this->panier[$cle])) {
            $nomProduit = $this->panier[$cle]['nom'];
            unset($this->panier[$cle]);
            $this->messageSucces = $nomProduit . ' retiré du panier';
        }
    }

    /**
     * Vider complètement le panier
     */
    public function viderPanier()
    {
        $this->panier = [];
        $this->messageSucces = 'Panier vidé';
    }

    /**
     * Changer le filtre de catégorie
     */
    public function filtrerCategorie(?int $categorieId)
    {
        $this->categorieSelectionnee = $categorieId;
        $this->recherche = ''; // Reset recherche
    }

    /**
 * Valider vente avec support offline
 */
public function validerVente()
{
    if (empty($this->panier)) {
        $this->messageErreur = 'Le panier est vide';
        return;
    }

    // Si mode offline, enregistrer localement
    if ($this->isOfflineMode) {
        $this->validerVenteOffline();
        return;
    }

    // Sinon, mode normal (code existant)
    $this->validerVenteOnline();
}

/**
 * Enregistrer vente en mode offline
 */
private function validerVenteOffline()
{
    try {
        $venteData = [
            'panier' => array_values($this->panier),
            'total' => $this->totalPanier,
            'mode_paiement' => $this->modePaiement,
            'montant' => $this->modePaiement === 'espece' ? floatval($this->sommeEncaissee) : $this->totalPanier,
            'monnaie_rendue' => $this->modePaiement === 'espece' ? max(0, floatval($this->sommeEncaissee) - $this->totalPanier) : 0,
            'timestamp' => now()->timestamp,
        ];

        // Envoyer au JavaScript pour sauvegarde locale
        $this->dispatch('save-offline-vente', venteData: $venteData);
        
        $this->panier = [];
        $this->sommeEncaissee = 0;
        $this->messageSucces = 'Vente enregistrée en mode hors ligne. Elle sera synchronisée automatiquement.';
        
    } catch (\Exception $e) {
        $this->messageErreur = 'Erreur : ' . $e->getMessage();
    }
}
    /**
     * Valider et enregistrer la vente en mode online
     */
    public function validerVenteOnline()
    {
        // Validation
        if (empty($this->panier)) {
            $this->messageErreur = 'Le panier est vide';
            return;
        }

        $this->isProcessing = true;
        $this->messageErreur = null;

        // Si paiement en espèces, vérifier que la somme encaissée couvre le total
        if ($this->modePaiement === 'espece') {
            if ($this->sommeEncaissee <= 0 || $this->sommeEncaissee < $this->totalPanier) {
                $this->messageErreur = 'Somme encaissée insuffisante';
                $this->isProcessing = false;
                return;
            }
        }

        try {
            DB::beginTransaction();

            // Créer la vente
            $montant = $this->modePaiement === 'espece'
                ? floatval($this->sommeEncaissee)
                : $this->totalPanier;
            $monnaieRendue = $this->modePaiement === 'espece'
                ? max(0, floatval($this->sommeEncaissee) - $this->totalPanier)
                : 0;
            $vente = Vente::create([
                'user_id' => auth()->id(),
                'total' => $this->totalPanier,
                'mode_paiement' => $this->modePaiement,
                'date_vente' => now(),
                'montant' => $montant,
                'monnaie_rendue' => $monnaieRendue,
            ]);

            // Créer les détails et mettre à jour les stocks
            foreach ($this->panier as $item) {
                VenteDetail::create([
                    'vente_id' => $vente->id,
                    'produit_id' => $item['id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix'],
                    'sous_total' => $item['prix'] * $item['quantite'],
                ]);

                // Décrémenter le stock de la variante
                $variant = \App\Models\Variant::find($item['variant_id'] ?? null);
                if ($variant && $variant->stock !== null) {
    $ancienStock = $variant->stock + $item['quantite']; // stock avant vente
    $variant->decrement('stock', $item['quantite']);

    // Recharge la variante pour avoir le nouveau stock
    $variant->refresh();

    // Si on passe SOUS le seuil d'alerte pendant cette vente
    if ($variant->seuil_alerte !== null 
        && $ancienStock > $variant->seuil_alerte 
        && $variant->stock <= $variant->seuil_alerte) {
        
        event(new StockAlerteEvent($variant));
        // ou StockAlerteEvent::dispatch($variant);
    }
}
            }

            DB::commit();

            // Sauvegarder l'ID de la vente pour l'impression
            $this->derniereVenteId = $vente->id;
            
            // Vider le panier
            $this->panier = [];
            // Réinitialiser somme encaissée
            $this->sommeEncaissee = 0;
            
            // Afficher confirmation
            $this->showConfirmation = true;
            $this->messageSucces = 'Vente enregistrée avec succès !';
            
            // Émettre événement pour impression
            $this->dispatch('vente-validee', venteId: $vente->id);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->messageErreur = 'Erreur lors de la vente : ' . $e->getMessage();
            \Log::error('Erreur vente POS', ['error' => $e->getMessage(), 'user' => auth()->id()]);
        } finally {
            $this->isProcessing = false;
        }
    }

    /**
 * Écouter le changement de statut offline
 */
#[On('offline-status-changed')]
public function updateOfflineStatus($isOffline)
{
    $this->isOfflineMode = $isOffline;
}
    /**
     * Fermer la confirmation et recommencer
     */
    public function nouvelleVente()
    {
        $this->showConfirmation = false;
        $this->derniereVenteId = null;
        $this->modePaiement = 'espece';
        $this->messageSucces = null;
        $this->messageErreur = null;
        $this->sommeEncaissee = 0;
    }

    /**
     * Ajouter un montant rapide à la somme encaissée (ex: boutons +500, +1000)
     */
    public function ajouterMontantEncaisse(float $montant)
    {
        $this->sommeEncaissee = floatval($this->sommeEncaissee) + $montant;
        // S'assurer que ce n'est jamais négatif
        if ($this->sommeEncaissee < 0) {
            $this->sommeEncaissee = 0;
        }
    }

    /**
     * Définir la somme encaissée exactement égale au total (payer exactement)
     */
    public function definirMontantExact()
    {
        $this->sommeEncaissee = $this->totalPanier;
    }

    /**
     * Réimprimer le dernier ticket
     */
    public function reimprimerTicket()
    {
        if ($this->derniereVenteId) {
            $this->dispatch('reimprimer-ticket', venteId: $this->derniereVenteId);
        }
    }

    /**
     * Écouter les événements (pour synchronisation)
     */
    #[On('stock-updated')]
    public function rafraichirStock()
    {
        // Force le recalcul des produits
        unset($this->produits);
    }

    /**
     * Obtenir l'URL du ticket pour impression
     */
    public function getUrlTicket(?int $venteId = null): ?string
    {
        $id = $venteId ?? $this->derniereVenteId;
        if (!$id) {
            return null;
        }
        return route('ticket.imprimer', ['vente' => $id]);
    }

    /**
     * Effacer les messages après quelques secondes
     */
    public function effacerMessages()
    {
        $this->messageSucces = null;
        $this->messageErreur = null;
    }

    /**
     * Render du composant
     */
    public function render()
    {
        return view('livewire.pos.ecran-caisse')
            ->layout('layouts.caissier');
    }
}