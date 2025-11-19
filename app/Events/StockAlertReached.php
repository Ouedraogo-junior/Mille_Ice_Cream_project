<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;  // ← AJOUT OBLIGATOIRE

class StockAlertReached implements ShouldBroadcast, ShouldQueue  // ← AJOUT ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $productName;
    public $variantName;
    public $currentStock;
    public $threshold;
    public $productId;
    public $variantId;

    public function __construct(
        string $productName,
        string $variantName,
        int $currentStock,
        int $threshold,
        int $productId,
        ?int $variantId = null
    ) {
        $this->productName = $productName;
        $this->variantName = $variantName;
        $this->currentStock = $currentStock;
        $this->threshold = $threshold;
        $this->productId = $productId;
        $this->variantId = $variantId;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('admin.notifications');
    }

    public function broadcastAs(): string
    {
        return 'stock.alert'; // ← Nom simple et clair
    }

    public function broadcastWith(): array
    {
        $type = $this->currentStock <= 0 ? 'rupture_stock' : 'stock_alert';
        $emoji = $this->currentStock <= 0 ? 'Rupture' : 'Attention';

        return [
            'type' => $type,
            'message' => $this->currentStock <= 0
                ? "{$emoji} RUPTURE DE STOCK : {$this->productName} - {$this->variantName}"
                : "{$emoji} Stock faible : {$this->productName} - {$this->variantName} ({$this->currentStock}/{$this->threshold})",
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