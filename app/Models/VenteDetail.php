<?php
// 📁 app/Models/VenteDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VenteDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'vente_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
        'sous_total',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'decimal:2',
        'sous_total' => 'decimal:2',
    ];

    /**
     * Un détail appartient à une vente
     */
    public function vente(): BelongsTo
    {
        return $this->belongsTo(Vente::class);
    }

    /**
     * Un détail concerne un produit
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Formater le sous-total
     */
    public function getSousTotalFormateAttribute(): string
    {
        return number_format($this->sous_total, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Formater le prix unitaire
     */
    public function getPrixUnitaireFormateAttribute(): string
    {
        return number_format($this->prix_unitaire, 0, ',', ' ') . ' FCFA';
    }
}