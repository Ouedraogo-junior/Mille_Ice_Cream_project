<?php

namespace App\Observers;

use App\Models\Vente;
use App\Models\Notification;
use App\Models\User;
use App\Events\StockAlertReached;
use Illuminate\Support\Facades\Log;

class VenteObserver
{
    /**
     * Seuil d'alerte de stock (modifiable selon vos besoins)
     */
    const SEUIL_ALERTE = 10;

    /**
     * Appelé après la création d'une vente
     * C'est ici que le stock est décrémenté
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

            $produit = $variant->produit;
            $stockActuel = $variant->fresh()->stock; // Recharger pour avoir le stock à jour

            // 🚨 Stock faible (entre 1 et seuil d'alerte)
            if ($stockActuel > 0 && $stockActuel <= self::SEUIL_ALERTE) {
                $this->envoyerAlerteStock($produit, $variant, $stockActuel);
            }

            // 🔴 Rupture de stock (0 ou négatif)
            if ($stockActuel <= 0) {
                $this->envoyerAlerteRupture($produit, $variant);
            }
        }
    }

    /**
     * Envoie une alerte de stock faible à tous les admins
     */
    private function envoyerAlerteStock($produit, $variant, $stockActuel)
    {
        // Récupérer tous les admins
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            Log::warning("Aucun admin trouvé pour envoyer l'alerte stock");
            return;
        }

        foreach ($admins as $admin) {
            // Éviter le spam : vérifier si une alerte similaire n'a pas été envoyée dans les 6 dernières heures
            $alerteRecente = Notification::where('user_id', $admin->id)
                ->where('type', 'stock_alert')
                ->where('data->variant_id', $variant->id)
                ->where('created_at', '>=', now()->subHours(6))
                ->exists();

            if ($alerteRecente) {
                continue;
            }

            // Créer la notification en base de données
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'stock_alert',
                'message' => "⚠️ Stock faible : {$produit->nom} - {$variant->nom} ({$stockActuel}/" . self::SEUIL_ALERTE . " unités)",
                'data' => [
                    'product_id' => $produit->id,
                    'variant_id' => $variant->id,
                    'product_name' => $produit->nom,
                    'variant_name' => $variant->nom,
                    'current_stock' => $stockActuel,
                    'threshold' => self::SEUIL_ALERTE,
                    'vente_id' => $vente->id ?? null
                ],
                'read' => false
            ]);

            // Broadcaster l'événement en temps réel via Reverb
            try {
                broadcast(new StockAlertReached(
                    $admin->id,
                    $produit->nom,
                    $variant->nom,
                    $stockActuel,
                    self::SEUIL_ALERTE,
                    $produit->id,
                    $variant->id
                ));

                Log::info("✅ Alerte stock envoyée : {$produit->nom} - {$variant->nom} (stock: {$stockActuel}) → Admin #{$admin->id}");
            } catch (\Exception $e) {
                Log::error("❌ Erreur broadcast alerte stock: " . $e->getMessage());
            }
        }
    }

    /**
     * Envoie une alerte de rupture de stock
     */
    private function envoyerAlerteRupture($produit, $variant)
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            // Éviter le spam pour les ruptures aussi
            $alerteRecente = Notification::where('user_id', $admin->id)
                ->where('type', 'rupture_stock')
                ->where('data->variant_id', $variant->id)
                ->where('created_at', '>=', now()->subHours(6))
                ->exists();

            if ($alerteRecente) {
                continue;
            }

            Notification::create([
                'user_id' => $admin->id,
                'type' => 'rupture_stock',
                'message' => "🔴 RUPTURE DE STOCK : {$produit->nom} - {$variant->nom}",
                'data' => [
                    'product_id' => $produit->id,
                    'variant_id' => $variant->id,
                    'product_name' => $produit->nom,
                    'variant_name' => $variant->nom,
                    'current_stock' => 0,
                    'threshold' => self::SEUIL_ALERTE
                ],
                'read' => false
            ]);

            try {
                broadcast(new StockAlertReached(
                    $admin->id,
                    $produit->nom,
                    $variant->nom,
                    0,
                    self::SEUIL_ALERTE,
                    $produit->id,
                    $variant->id
                ));

                Log::info("🔴 Alerte rupture stock envoyée : {$produit->nom} - {$variant->nom} → Admin #{$admin->id}");
            } catch (\Exception $e) {
                Log::error("❌ Erreur broadcast rupture stock: " . $e->getMessage());
            }
        }
    }
}