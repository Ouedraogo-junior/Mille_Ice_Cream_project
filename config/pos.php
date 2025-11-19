<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Objectif journalier
    |--------------------------------------------------------------------------
    |
    | Définit l'objectif de chiffre d'affaires quotidien pour chaque caissier.
    | Affiché sur le dashboard sous forme de barre de progression.
    |
    */
    'objectif_journalier' => env('POS_OBJECTIF_JOURNALIER', 50000),

    /*
    |--------------------------------------------------------------------------
    | Impression automatique
    |--------------------------------------------------------------------------
    |
    | Active l'impression automatique des tickets après chaque vente.
    |
    */
    'impression_auto' => env('POS_IMPRESSION_AUTO', false),

    /*
    |--------------------------------------------------------------------------
    | Type d'imprimante par défaut
    |--------------------------------------------------------------------------
    |
    | Types supportés: "browser", "bluetooth", "usb"
    |
    */
    'imprimante_defaut' => env('POS_IMPRIMANTE_DEFAUT', 'browser'),

    /*
    |--------------------------------------------------------------------------
    | Taille du papier
    |--------------------------------------------------------------------------
    |
    | Tailles supportées: "58mm", "80mm"
    |
    */
    'taille_papier' => env('POS_TAILLE_PAPIER', '58mm'),

    /*
    |--------------------------------------------------------------------------
    | Auto-refresh du dashboard
    |--------------------------------------------------------------------------
    |
    | Intervalle de rafraîchissement automatique en secondes (0 = désactivé)
    |
    */
    'dashboard_refresh' => env('POS_DASHBOARD_REFRESH', 300), // 5 minutes

    /*
    |--------------------------------------------------------------------------
    | Nombre de ventes récentes à afficher
    |--------------------------------------------------------------------------
    */
    'nombre_ventes_recentes' => 8,

    /*
    |--------------------------------------------------------------------------
    | Nombre de top produits à afficher
    |--------------------------------------------------------------------------
    */
    'nombre_top_produits' => 5,
];