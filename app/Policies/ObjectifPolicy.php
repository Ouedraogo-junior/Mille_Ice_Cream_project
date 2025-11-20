<?php

namespace App\Policies;

use App\Models\Objectif;
use App\Models\User;

class ObjectifPolicy
{
    /**
     * Determine if the user can view any objectives.
     */
    public function viewAny(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent voir les objectifs
    }

    /**
     * Determine if the user can view the objective.
     */
    public function view(User $user, Objectif $objectif): bool
    {
        return true; // Tous peuvent voir un objectif spécifique
    }

    /**
     * Determine if the user can create objectives.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can update the objective.
     */
    public function update(User $user, Objectif $objectif): bool
    {
        // Les admins peuvent tout modifier
        // Les créateurs peuvent modifier leurs propres objectifs
        return $user->role === 'admin' || $objectif->cree_par === $user->id;
    }

    /**
     * Determine if the user can delete the objective.
     */
    public function delete(User $user, Objectif $objectif): bool
    {
        // Seuls les admins peuvent supprimer
        // Ou le créateur peut supprimer son propre objectif s'il n'est pas atteint
        return $user->role === 'admin' || 
               ($objectif->cree_par === $user->id && $objectif->statut !== 'atteint');
    }

    /**
     * Determine if the user can restore the objective.
     */
    public function restore(User $user, Objectif $objectif): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can permanently delete the objective.
     */
    public function forceDelete(User $user, Objectif $objectif): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can cancel the objective.
     */
    public function cancel(User $user, Objectif $objectif): bool
    {
        return $user->role === 'admin' || $objectif->cree_par === $user->id;
    }

    /**
     * Determine if the user can reactivate the objective.
     */
    public function reactivate(User $user, Objectif $objectif): bool
    {
        return $user->role === 'admin';
    }
}