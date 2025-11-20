<?php

namespace App\Providers;

//use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
use App\Models\Vente;
use App\Observers\VenteObserver;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use App\Services\TicketPrinter;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [];
    /**
     * Register any application services.
     */
    public function register(): void
    {
       $this->app->singleton(TicketPrinter::class, function ($app) {
            return new TicketPrinter();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('caissier', function ($user) {
            return in_array($user->role, ['admin', 'caissier']);
        });

        // Gate pour accéder à la caisse (admin ET caissier peuvent y accéder)
        Gate::define('acces-caisse', function (User $user) {
            return $user->is_active && in_array($user->role, ['admin', 'caissier']);
        });

        // Gate pour gérer les produits (admin uniquement)
        Gate::define('gerer-produits', function (User $user) {
            return $user->role === 'admin' && $user->is_active;
        });

        // Gate pour gérer les utilisateurs (admin uniquement)
        Gate::define('gerer-utilisateurs', function (User $user) {
            return $user->role === 'admin' && $user->is_active;
        });

        // Gate pour voir les rapports (admin uniquement)
        Gate::define('voir-rapports', function (User $user) {
            return $user->role === 'admin' && $user->is_active;
        });

        // Gate pour annuler une vente (admin uniquement)
        Gate::define('annuler-vente', function (User $user) {
            return $user->role === 'admin' && $user->is_active;
        });

         // Enregistrer l'observer pour surveiller les ventes
        Vente::observe(VenteObserver::class);
        // Gate pour voir un ticket de vente (admin et caissier)
        Gate::define('voir-ticket', function (User $user, Vente $vente) {
            // Un admin peut voir tous les tickets
            if ($user->role === 'admin') {
                return true;
            }
    
            // Un caissier ne peut voir que ses propres tickets
            return $user->id === $vente->user_id;
        });

    }

}
