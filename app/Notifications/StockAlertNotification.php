<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;
use App\Models\Variant;
use Illuminate\Broadcasting\InteractsWithSockets;

class StockAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use InteractsWithSockets;

    public function __construct(
        public Variant $variant,
        public int $stockActuel,
        public bool $isRupture = false
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable): array
    {
        $type = $this->isRupture ? 'rupture_stock' : 'stock_alert';
        $emoji = $this->isRupture ? 'Rupture' : 'Attention';

        return [
            'type' => $type,
            'message' => $this->isRupture
                ? "{$emoji} RUPTURE DE STOCK : {$this->variant->produit->nom} - {$this->variant->nom}"
                : "{$emoji} Stock faible : {$this->variant->produit->nom} - {$this->variant->nom} ({$this->stockActuel}/{$this->variant->seuil_alerte})",
            'product_id' => $this->variant->produit->id,
            'variant_id' => $this->variant->id,
            'product_name' => $this->variant->produit->nom,
            'variant_name' => $this->variant->nom,
            'current_stock' => $this->stockActuel,
            'threshold' => $this->variant->seuil_alerte ?? 10,
            'timestamp' => now()->toISOString(),
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    // CHANGEMENT CLÉ #1
    public function broadcastAs(): string
    {
        return 'stock.alert'; // ← même nom que dans le listener Livewire
    }

    // CHANGEMENT CLÉ #2 (le plus important !)
    public function broadcastOn(): array
    {
        // UN SEUL CANAL PRIVÉ POUR TOUS LES ADMINS
        return [new PrivateChannel('admin.notifications')];
    }
}