<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = 'categorie';
    protected $fillable = ['nom', 'active', 'couleur', 'icone']; 

    public function produits()
    {
        return $this->hasMany(Produit::class, 'categorie_id');
    }
}