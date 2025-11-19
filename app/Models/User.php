<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'notification_preferences'
    ];

    protected $hidden = [
        'password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token',
    ];

    // AJOUTE ÇA ICI (remplace ta fonction casts actuelle)
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notification_preferences' => 'array', // ← Stocké en JSON dans la BDD
        ];
    }

    // Valeur par défaut si jamais vide
    public function getNotificationPreferencesAttribute($value)
    {
        return $value ?? [
            'low_stock' => true,
            'new_sale' => true,
            'daily_report' => true,
        ];
    }

    // Pour sauvegarder facilement
    public function updateNotificationPreferences(array $preferences): void
    {
        $this->update(['notification_preferences' => $preferences]);
    }

    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')
            ->orderBy('created_at', 'desc');
    }

    public function unreadNotificationsCount()
    {
        return $this->notifications()->where('read', false)->count();
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function isAdmin()
    { 
        return $this->role === 'admin'; 
    }

    public function isCaissier()
    { 
        return $this->role === 'caissier'; 
    }
}