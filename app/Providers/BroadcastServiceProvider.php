<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::routes();

        // AJOUTE ÇA (c’est LA ligne qui sécurise ton canal admin)
        Broadcast::channel('admin.notifications', function ($user) {
            // Tu adaptes selon comment tu gères les rôles dans ton projet
            return $user && $user->role === 'admin';
            
            // Variante si tu utilises Spatie Laravel-Permission :
            // return $user && $user->hasRole('admin');
            
            // Variante si tu as une colonne is_admin (booléen) :
            // return $user && $user->is_admin === true;
        });

        require base_path('routes/channels.php');
    }
}