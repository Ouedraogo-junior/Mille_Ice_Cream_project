<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockAlertReached implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $productName;
    public $variantName;
    public $currentStock;
    public $threshold;
    public $productId;
    public $variantId;

    /**
     * @param int $userId ID de l'admin à notifier
     * @param string $productName Nom du produit
     * @param string $variantName Nom du variant
     * @param int $currentStock Stock actuel
     * @param int $threshold Seuil d'alerte
     * @param int $productId ID du produit
     * @param int|null $variantId ID du variant
     */
    public function __construct($userId, $productName, $variantName, $currentStock, $threshold, $productId, $variantId = null)
    {
        $this->userId = $userId;
        $this->productName = $productName;
        $this->variantName = $variantName;
        $this->currentStock = $currentStock;
        $this->threshold = $threshold;
        $this->productId = $productId;
        $this->variantId = $variantId;
    }

    /**
     * Canal de broadcast (utilisez PrivateChannel pour sécuriser)
     */
    public function broadcastOn()
    {
        // Utiliser un canal privé pour plus de sécurité
        return new PrivateChannel('notifications.' . $this->userId);
        
        // OU utilisez un canal public si vous préférez
        // return new Channel('notifications.' . $this->userId);
    }

    /**
     * Nom de l'événement écouté côté frontend
     */
    public function broadcastAs()
    {
        return 'NotificationSent';
    }

    /**
     * Données envoyées au frontend
     */
    public function broadcastWith()
    {
        $type = $this->currentStock <= 0 ? 'rupture_stock' : 'stock_alert';
        $emoji = $this->currentStock <= 0 ? '🔴' : '⚠️';
        
        return [
            'type' => $type,
            'message' => "{$emoji} " . ($this->currentStock <= 0 
                ? "RUPTURE DE STOCK : {$this->productName} - {$this->variantName}"
                : "Stock faible : {$this->productName} - {$this->variantName} ({$this->currentStock}/{$this->threshold})"),
            'product_name' => $this->productName,
            'variant_name' => $this->variantName,
            'current_stock' => $this->currentStock,
            'threshold' => $this->threshold,
            'product_id' => $this->productId,
            'variant_id' => $this->variantId,
            'timestamp' => now()->toISOString(),
        ];
    }
}