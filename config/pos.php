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

    /*
    |--------------------------------------------------------------------------
    | Mode Offline
    |--------------------------------------------------------------------------
    |
    | Active/désactive les fonctionnalités hors ligne
    |
    */
    'offline_mode' => env('APP_OFFLINE_MODE', true),

    /*
    |--------------------------------------------------------------------------
    | Intervalle de Synchronisation
    |--------------------------------------------------------------------------
    |
    | Temps en millisecondes entre chaque tentative de synchronisation
    | automatique (par défaut: 5 minutes = 300000ms)
    |
    */
    'sync_interval' => env('APP_SYNC_INTERVAL', 300000),

    /*
    |--------------------------------------------------------------------------
    | Version du Cache
    |--------------------------------------------------------------------------
    |
    | Version du cache Service Worker. Incrémenter pour forcer la mise à jour
    |
    */
    'cache_version' => env('APP_CACHE_VERSION', '1.0.0'),

    /*
    |--------------------------------------------------------------------------
    | Durée de Rétention des Ventes Synchronisées
    |--------------------------------------------------------------------------
    |
    | Nombre de jours avant suppression des ventes déjà synchronisées
    |
    */
    'ventes_retention_days' => env('VENTES_RETENTION_DAYS', 7),

    /*
    |--------------------------------------------------------------------------
    | Nombre Maximum de Tentatives de Sync
    |--------------------------------------------------------------------------
    |
    | Nombre de tentatives avant abandon de la synchronisation
    |
    */
    'max_sync_attempts' => env('MAX_SYNC_ATTEMPTS', 3),

    /*
    |--------------------------------------------------------------------------
    | Objectif Journalier
    |--------------------------------------------------------------------------
    |
    | Objectif de vente journalier en FCFA
    |
    */
    'objectif_journalier' => env('POS_OBJECTIF_JOURNALIER', 50000),

    /*
    |--------------------------------------------------------------------------
    | Mode Debug Offline
    |--------------------------------------------------------------------------
    |
    | Active les logs détaillés pour le débogage
    |
    */
    'debug_offline' => env('APP_DEBUG_OFFLINE', false),

    /*
    |--------------------------------------------------------------------------
    | Assets à Mettre en Cache
    |--------------------------------------------------------------------------
    |
    | Liste des fichiers à mettre en cache pour le mode offline
    |
    */
    'cached_assets' => [
        '/',
        '/offline.html',
        '/caisse',
        '/mes-ventes',
        '/dashboard',
    ],

    /*
    |--------------------------------------------------------------------------
    | Stratégie de Cache
    |--------------------------------------------------------------------------
    |
    | Stratégie utilisée pour le cache des ressources:
    | - 'network-first': Tenter le réseau d'abord, puis le cache
    | - 'cache-first': Utiliser le cache d'abord, puis le réseau
    | - 'network-only': Toujours utiliser le réseau
    | - 'cache-only': Toujours utiliser le cache
    |
    */
    'cache_strategy' => [
        'api' => 'network-first',
        'static' => 'cache-first',
        'images' => 'cache-first',
    ],

    /*
    |--------------------------------------------------------------------------
    | Limites de Stockage
    |--------------------------------------------------------------------------
    |
    | Limites de stockage IndexedDB
    |
    */
    'storage_limits' => [
        'max_produits' => 1000,
        'max_ventes_pending' => 100,
        'max_cache_size' => 50, // en MB
    ],

    /*
    |--------------------------------------------------------------------------
    | Informations Entreprise (pour tickets)
    |--------------------------------------------------------------------------
    */
    'entreprise' => [
        'nom' => env('APP_NOM_ENTREPRISE', 'GLACIER MILA'),
        'adresse' => env('APP_ADRESSE_ENTREPRISE', 'Ouagadougou, Burkina Faso'),
        'telephone' => env('APP_TEL_ENTREPRISE', '+226  XX'),
        'email' => env('APP_EMAIL_ENTREPRISE', 'contact@glacier.bf'),
        'logo' => env('APP_LOGO_ENTREPRISE', null),
        'message_footer' => env('APP_MESSAGE_TICKET', 'Merci de votre visite ! À bientôt.'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Modes de Paiement Actifs
    |--------------------------------------------------------------------------
    */
    'modes_paiement' => [
        'espece' => true,
        'mobile' => true,
        'carte' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration Imprimante
    |--------------------------------------------------------------------------
    */
    'imprimante' => [
        'largeur_ticket' => 48, // caractères
        'hauteur_ligne' => 24, // pixels
        'police' => 'monospace',
        'taille_police' => 12,
        'auto_cut' => true,
        'open_drawer' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'sync_success' => true,
        'sync_error' => true,
        'offline_mode' => true,
        'low_stock' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sécurité
    |--------------------------------------------------------------------------
    */
    'securite' => [
        'require_https' => env('APP_ENV') === 'production',
        'session_timeout' => 3600, // 1 heure
        'max_login_attempts' => 5,
        'lockout_duration' => 900, // 15 minutes
    ],
];