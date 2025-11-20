<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Objectif extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titre',
        'type',
        'description',
        'objectif',
        'actuel',
        'date_debut',
        'date_fin',
        'statut',
        'cree_par',
    ];

    protected $casts = [
        'objectif' => 'decimal:2',
        'actuel' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    protected $dates = [
        'date_debut',
        'date_fin',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Relation avec l'utilisateur créateur
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    /**
     * Calculer la progression en pourcentage
     */
    public function progression(): int
    {
        if ($this->objectif <= 0) {
            return 0;
        }

        $progression = ($this->actuel / $this->objectif) * 100;
        return (int) round(min($progression, 100));
    }

    /**
     * Vérifier si l'objectif est atteint
     */
    public function estAtteint(): bool
    {
        return $this->actuel >= $this->objectif;
    }

    /**
     * Vérifier si l'objectif est en retard
     */
    public function estEnRetard(): bool
    {
        if ($this->statut === 'atteint' || $this->statut === 'annule') {
            return false;
        }

        return now()->isAfter($this->date_fin) && !$this->estAtteint();
    }

    /**
     * Obtenir le nombre de jours restants
     */
    public function joursRestants(): int
    {
        return now()->diffInDays($this->date_fin, false);
    }

    /**
     * Obtenir la progression quotidienne moyenne nécessaire
     */
    public function progressionQuotidienneNecessaire(): float
    {
        $joursRestants = $this->joursRestants();
        
        if ($joursRestants <= 0) {
            return 0;
        }

        $restantAAtteindre = max(0, $this->objectif - $this->actuel);
        return round($restantAAtteindre / $joursRestants, 2);
    }

    /**
     * Obtenir le pourcentage du temps écoulé
     */
    public function pourcentageTempsEcoule(): int
    {
        $debut = $this->date_debut;
        $fin = $this->date_fin;
        $maintenant = now();

        if ($maintenant->isBefore($debut)) {
            return 0;
        }

        if ($maintenant->isAfter($fin)) {
            return 100;
        }

        $dureeTotal = $debut->diffInDays($fin);
        $dureeEcoulee = $debut->diffInDays($maintenant);

        if ($dureeTotal <= 0) {
            return 100;
        }

        return (int) round(($dureeEcoulee / $dureeTotal) * 100);
    }

    /**
     * Vérifier si l'objectif est en bonne voie
     */
    public function estEnBonneVoie(): bool
    {
        return $this->progression() >= $this->pourcentageTempsEcoule();
    }

    /**
     * Obtenir la couleur de statut
     */
    public function couleurStatut(): string
    {
        if ($this->statut === 'atteint') {
            return 'emerald';
        }

        if ($this->statut === 'annule') {
            return 'gray';
        }

        if ($this->estEnRetard()) {
            return 'red';
        }

        if ($this->estEnBonneVoie()) {
            return 'green';
        }

        return 'orange';
    }

    /**
     * Formater la valeur avec l'unité
     */
    public function valeurFormatee($valeur = null): string
    {
        $val = $valeur ?? $this->actuel;

        if ($this->unite === 'FCFA') {
            return number_format($val, 0, ',', ' ') . ' F';
        }

        return number_format($val, 0, ',', ' ') . ' ' . $this->unite;
    }

    /**
     * Scope pour les objectifs en cours
     */
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    /**
     * Scope pour les objectifs atteints
     */
    public function scopeAtteints($query)
    {
        return $query->where('statut', 'atteint');
    }

    /**
     * Scope pour les objectifs en retard
     */
    public function scopeEnRetard($query)
    {
        return $query->where('statut', 'en_cours')
                    ->where('date_fin', '<', now())
                    ->whereRaw('actuel < objectif');
    }

    /**
     * Scope pour les objectifs créés par un utilisateur
     */
    public function scopeCreePar($query, $userId)
    {
        return $query->where('cree_par', $userId);
    }
}