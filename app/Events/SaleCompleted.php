<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SaleCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $saleId;
    public $amount;
    public $customerName;
    public $items;
    public $paymentMethod;

    /**
     * Créer une nouvelle instance de l'événement
     *
     * @param int $userId ID de l'admin à notifier
     * @param int $saleId ID de la vente
     * @param float $amount Montant total de la vente
     * @param string|null $customerName Nom du client (optionnel)
     * @param array $items Liste des articles vendus (optionnel)
     * @param string|null $paymentMethod Méthode de paiement (optionnel)
     */
    public function __construct($userId, $saleId, $amount, $customerName = null, $items = [], $paymentMethod = null)
    {
        $this->userId = $userId;
        $this->saleId = $saleId;
        $this->amount = $amount;
        $this->customerName = $customerName;
        $this->items = $items;
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * Obtenir les canaux sur lesquels l'événement doit être diffusé
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new Channel('notifications.' . $this->userId);
    }

    /**
     * Nom de l'événement pour le broadcasting
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'NotificationSent';
    }

    /**
     * Données à envoyer avec l'événement
     *
     * @return array
     */
    public function broadcastWith()
    {
        // Construction du message
        $customerInfo = $this->customerName ? " - {$this->customerName}" : '';
        $itemsCount = count($this->items) > 0 ? ' (' . count($this->items) . ' articles)' : '';
        
        return [
            'type' => 'sale_completed',
            'message' => "🛒 Nouvelle vente : {$this->amount} FCFA{$customerInfo}{$itemsCount}",
            'sale_id' => $this->saleId,
            'amount' => $this->amount,
            'customer_name' => $this->customerName,
            'items' => $this->items,
            'payment_method' => $this->paymentMethod,
            'timestamp' => now()->toISOString(),
        ];
    }
}