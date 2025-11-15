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

// ==================== ON AJOUTE LE POS GLACIER (ce qui manque) ====================

Route::middleware('auth')->group(function () {

    // Écran caisse → tous les utilisateurs connectés (caissier + admin)
    Route::get('/caisse', EcranCaisse::class)
        ->name('caisse');

    // Section admin → réservé UNIQUEMENT aux utilisateurs avec role = 'admin'
    Route::prefix('admin')->middleware('can:admin')->group(function () {

        // Page d'accueil admin
        Route::get('/', fn() => redirect()->route('admin.produits'));

        // Gestion des produits, catégories, stocks
        Route::get('/produits', GestionProduits::class)
            ->name('admin.produits');

        // Gestion des caissiers (création, blocage, mot de passe)
        Route::get('/caissiers', GestionCaissiers::class)
            ->name('admin.caissiers');

        // Rapports : journalier, par caissier, top produits
        Route::get('/rapports', Rapports::class)
            ->name('admin.rapports');
    });
});

// Déconnexion propre (remplace celle de Breeze si besoin)
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');