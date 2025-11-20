<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $table = 'produit';
    protected $fillable = ['nom', 'description', 'categorie_id', 'image'];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'produit_id');
    }
}