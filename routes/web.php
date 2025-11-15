<?php

use App\Livewire\Pos\EcranCaisse;
use App\Livewire\Admin\GestionProduits;
use App\Livewire\Admin\GestionCaissiers;
use App\Livewire\Admin\Rapports;

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// ==================== ON GARDE TOUT BREEZE (dashboard, settings, etc.) ====================

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');
    
    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                []
            )
        )
        ->name('two-factor.show');
});


Route::middleware('auth')->group(function () {

    // ==================== ÉCRAN CAISSE (accessible à tous : admin + caissier) ====================
    Route::get('/caisse', \App\Livewire\Pos\EcranCaisse::class)
        ->name('caisse');

    // ==================== SECTION ADMIN (réservé au rôle admin) ====================
    Route::prefix('admin')->middleware('can:admin')->group(function () {

        // DASHBOARD = PAGE D'ACCUEIL DE L'ADMIN (comme dans le cahier des charges)
        Route::get('/', \App\Livewire\Admin\Dashboard::class)
            ->name('admin.dashboard');

        // Alias /admin/dashboard pour compatibilité (optionnel)
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)
            ->name('admin.dashboard');

        // Gestion des produits
        Route::get('/produits', \App\Livewire\Admin\GestionProduits::class)
            ->name('admin.produits');

        // Gestion des catégories
        Route::get('/categories', \App\Livewire\Admin\GestionCategories::class)
            ->name('admin.categories');

        // Gestion des caissiers
        Route::get('/caissiers', \App\Livewire\Admin\GestionCaissiers::class)
            ->name('admin.caissiers');

        // Rapports (journalier, par caissier, top produits)
        Route::get('/rapports', \App\Livewire\Admin\Rapports::class)
            ->name('admin.rapports');

        // Historique des ventes (optionnel, si tu veux séparer)
        /* Route::get('/ventes', \App\Livewire\Admin\VentesHistorique::class)
            ->name('admin.ventes'); */
    });
});

// Déconnexion propre (remplace celle de Breeze si besoin)
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');