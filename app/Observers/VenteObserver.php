<?php

namespace App\Observers;

use App\Models\Vente;
use App\Models\User;
use App\Notifications\StockAlertNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class VenteObserver
{
    /**
     * Appelé après la création d'une vente
     * Le stock est décrémenté UNIQUEMENT ICI
     */
    public function created(Vente $vente)
    {
        // Si la vente est déjà annulée à la création, ne rien faire
        if ($vente->est_annulee) {
            return;
        }

        $this->decrementerStock($vente);
        $this->verifierAlerteStock($vente);
    }

    /**
     * Appelé après la mise à jour d'une vente
     * Pour gérer les annulations
     */
    public function updated(Vente $vente)
    {
        // Si la vente vient d'être annulée, restaurer le stock
        if ($vente->isDirty('est_annulee') && $vente->est_annulee) {
            $this->restaurerStock($vente);
            Log::info("Stock restauré pour la vente annulée #{$vente->id}");
        }
    }

    /**
     * Décrémente le stock pour chaque produit vendu
     */
    private function decrementerStock(Vente $vente)
    {
        foreach ($vente->details as $detail) {
            $variant = $detail->variant;
            
            if (!$variant) {
                Log::warning("Variant introuvable pour le détail #{$detail->id}");
                continue;
            }

            // Vérifier si le stock est suffisant
            if ($variant->stock >= $detail->quantite) {
                $variant->decrement('stock', $detail->quantite);
                Log::info("Stock décrémenté : {$variant->produit->nom} - {$variant->nom} (-{$detail->quantite})");
            } else {
                Log::error("Stock insuffisant pour {$variant->produit->nom} - {$variant->nom}");
            }
        }
    }

    /**
     * Restaure le stock lors de l'annulation d'une vente
     */
    private function restaurerStock(Vente $vente)
    {
        foreach ($vente->details as $detail) {
            $variant = $detail->variant;
            
            if ($variant) {
                $variant->increment('stock', $detail->quantite);
            }
        }
    }

    /**
     * Vérifie le stock et envoie des alertes si nécessaire
     */
    private function verifierAlerteStock(Vente $vente)
    {
        foreach ($vente->details as $detail) {
            $variant = $detail->variant;
            
            if (!$variant) {
                continue;
            }

            // Recharger pour avoir le stock à jour
            $variant->refresh();
            
            $stockActuel = $variant->stock;
            $seuilAlerte = $variant->seuil_alerte ?? 10; // Utiliser le seuil du variant

            // 🔴 Rupture de stock (0 ou négatif)
            if ($stockActuel <= 0) {
                $this->envoyerNotification($variant, $stockActuel, true);
            }
            // 🚨 Stock faible (entre 1 et seuil d'alerte)
            elseif ($stockActuel <= $seuilAlerte) {
                $this->envoyerNotification($variant, $stockActuel, false);
            }
        }
    }

    /**
     * Envoie une notification à tous les admins
     */
    private function envoyerNotification($variant, $stockActuel, $isRupture)
    {
        // Récupérer tous les admins
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            Log::warning("Aucun admin trouvé pour envoyer l'alerte stock");
            return;
        }

        foreach ($admins as $admin) {
            // Anti-spam : vérifier si une alerte similaire a été envoyée récemment
            $alerteRecente = $admin->notifications()
                ->where('type', StockAlertNotification::class)
                ->where('data->variant_id', $variant->id)
                ->where('created_at', '>=', now()->subHours(6))
                ->exists();

            if ($alerteRecente) {
                continue;
            }

            try {
                // Envoyer la notification via le système Laravel
                $admin->notify(new StockAlertNotification($variant, $stockActuel, $isRupture));

                $type = $isRupture ? 'rupture' : 'alerte';
                Log::info("✅ Notification {$type} envoyée : {$variant->produit->nom} - {$variant->nom} → Admin #{$admin->id}");
                
            } catch (\Exception $e) {
                Log::error("❌ Erreur envoi notification: " . $e->getMessage());
            }
        }
    }
}