<?php

namespace App\Events;

use App\Models\Variant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockAlerteEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $stockRestant;
    public $seuil;

    public function __construct(Variant $variant)
    {
        // On charge le produit parent pour avoir le nom complet
        // Assure-toi que la relation 'produit' est définie dans ton modèle Variant
        $variant->load('produit'); 

        $nomComplet = $variant->produit->nom . ' (' . $variant->nom . ')';
        
        $this->message = "Stock critique : {$nomComplet}";
        $this->stockRestant = $variant->stock;
        $this->seuil = $variant->seuil_alerte;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('admin-alerts'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'stock.low';
    }
}