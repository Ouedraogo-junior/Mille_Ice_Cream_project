<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $table = 'produit';
    protected $fillable = ['nom', 'description', 'prix', 'stock', 'seuil_alerte', 'categorie_id', 'active', 'image'];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'produit_id');
    }
}