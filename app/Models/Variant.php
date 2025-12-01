<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $table = 'variants';

    protected $fillable = [
        'produit_id',
        'nom',
        'prix',
        'stock',
        'seuil_alerte',
        'active',
        'gerer_stock',
        'gerer_stock' 
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    // Accesseur pour vérifier si le stock est faible
    public function getStockFaibleAttribute()
    {
        if (!$this->gerer_stock) {
            return false; // Pas d'alerte si stock non géré
        }
        return $this->stock <= $this->seuil_alerte;
    }

    // Accesseur pour l'affichage du stock
    public function getStockDisplayAttribute()
    {
        return $this->gerer_stock ? $this->stock : '∞';
    }
}