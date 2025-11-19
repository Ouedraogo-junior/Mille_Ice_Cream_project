<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DailyReportGenerated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $date;
    public $totalSales;
    public $revenue;
    public $topProducts;
    public $lowStockItems;

    /**
     * Créer une nouvelle instance de l'événement
     *
     * @param int $userId ID de l'admin à notifier
     * @param string $date Date du rapport (format: d/m/Y)
     * @param int $totalSales Nombre total de ventes
     * @param float $revenue Chiffre d'affaires total
     * @param array $topProducts Produits les plus vendus (optionnel)
     * @param array $lowStockItems Articles en rupture de stock (optionnel)
     */
    public function __construct($userId, $date, $totalSales, $revenue, $topProducts = [], $lowStockItems = [])
    {
        $this->userId = $userId;
        $this->date = $date;
        $this->totalSales = $totalSales;
        $this->revenue = $revenue;
        $this->topProducts = $topProducts;
        $this->lowStockItems = $lowStockItems;
    }

    /**
     * Obtenir les canaux sur lesquels l'événement doit être diffusé
     *
     * @return \Illuminate\Broadcasting\Channel|array
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
        // Formatage du montant
        $formattedRevenue = number_format($this->revenue, 0, ',', ' ');
        
        // Informations supplémentaires sur les stocks faibles
        $lowStockCount = count($this->lowStockItems);
        $stockAlert = $lowStockCount > 0 
            ? " | ⚠️ {$lowStockCount} produit(s) en stock faible" 
            : '';
        
        return [
            'type' => 'daily_report',
            'message' => "📊 Rapport du {$this->date} : {$this->totalSales} vente(s) - {$formattedRevenue} FCFA{$stockAlert}",
            'date' => $this->date,
            'total_sales' => $this->totalSales,
            'revenue' => $this->revenue,
            'formatted_revenue' => $formattedRevenue,
            'top_products' => $this->topProducts,
            'low_stock_items' => $this->lowStockItems,
            'timestamp' => now()->toISOString(),
        ];
    }
}