<?php
// 📁 app/Models/Vente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'mode_paiement',
        'date_vente',
        'numero_ticket',
        'note',
        'est_annulee',
        'annulee_le',
        'annulee_par',
        'raison_annulation',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'date_vente' => 'datetime',
        'est_annulee' => 'boolean',
        'annulee_le' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement le numéro de ticket
        static::creating(function ($vente) {
            if (!$vente->numero_ticket) {
                $vente->numero_ticket = 'TKT-' . now()->format('Ymd') . '-' . str_pad(
                    Vente::whereDate('created_at', now())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }

    /**
     * Une vente appartient à un utilisateur (caissier)
     */
    public function caissier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Une vente a plusieurs détails
     */
    public function details(): HasMany
    {
        return $this->hasMany(VenteDetail::class);
    }

    /**
     * Utilisateur qui a annulé la vente
     */
    public function annuleePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'annulee_par');
    }

    /**
     * Scope pour ventes non annulées
     */
    public function scopeNonAnnulee($query)
    {
        return $query->where('est_annulee', false);
    }

    /**
     * Scope pour ventes d'aujourd'hui
     */
    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_vente', today());
    }

    /**
     * Scope pour ventes d'un caissier
     */
    public function scopeDuCaissier($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour une période
     */
    public function scopePeriode($query, $debut, $fin)
    {
        return $query->whereBetween('date_vente', [$debut, $fin]);
    }

    /**
     * Formater le total
     */
    public function getTotalFormateAttribute(): string
    {
        return number_format($this->total, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Obtenir le libellé du mode de paiement
     */
    public function getModePaiementLibelleAttribute(): string
    {
        return match($this->mode_paiement) {
            'espece' => '💵 Espèce',
            'mobile' => '📱 Mobile Money',
            'carte' => '💳 Carte bancaire',
            default => $this->mode_paiement,
        };
    }
}