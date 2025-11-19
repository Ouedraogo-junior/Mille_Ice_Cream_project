<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'notifications';
    
    protected $fillable = [
        'user_id', 'type', 'message', 'data', 'read', 'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead(): void
    {
        $this->update([
            'read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Récupère l'icône selon le type de notification
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'stock_alert' => '⚠️',
            'rupture_stock' => '🔴',
            'commande' => '🛒',
            'livraison' => '🚚',
            default => '🔔'
        };
    }

    /**
     * Récupère la couleur selon le type
     */
    public function getColorAttribute(): string
    {
        return match($this->type) {
            'stock_alert' => 'warning',
            'rupture_stock' => 'danger',
            'commande' => 'success',
            default => 'info'
        };
    }
}