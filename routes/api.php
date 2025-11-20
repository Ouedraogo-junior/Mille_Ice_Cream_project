<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OfflineController;

// Routes pour le mode offline (nécessite authentification)
Route::middleware(['auth:sanctum'])->prefix('offline')->group(function () {
    
    // Télécharger les données pour le cache
    Route::get('/produits', [OfflineController::class, 'produits']);
    Route::get('/categories', [OfflineController::class, 'categories']);
    Route::get('/config', [OfflineController::class, 'config']);
    
    // Synchroniser une vente enregistrée offline
    Route::post('/sync-vente', [OfflineController::class, 'syncVente']);
    
    // Vérifier les stocks actuels
    Route::post('/check-stocks', [OfflineController::class, 'checkStocks']);
    
    // Statut de synchronisation
    Route::get('/sync-status', [OfflineController::class, 'syncStatus']);
});