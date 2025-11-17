<?php

// app/Helpers/helpers.php

if (!function_exists('setting')) {
    /**
     * Helper global pour récupérer facilement les paramètres
     * Utilisation : setting('logo') → retourne le chemin du logo
     *              setting('nom_entreprise', 'Milla Ice Cream') → valeur par défaut
     */
    function setting($key = null, $default = null)
    {
composition:        if (is_null($key)) {
            return app(\App\Models\Setting::class);
        }

        return \App\Models\Setting::get($key, $default);
    }
}