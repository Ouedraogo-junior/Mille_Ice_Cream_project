<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $table = 'variants';

    // AJOUTE ÇA OBLIGATOIREMENT !!!
    protected $fillable = [
        'produit_id',
        'nom',
        'prix',
        'stock',
        'seuil_alerte',
        'active'
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }
}