<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Récupérer une valeur de paramètre
     */
    public static function get($key, $default = null)
    {
        try {
            $setting = Cache::remember("setting_{$key}", 3600, function () use ($key) {
                return self::where('key', $key)->first();
            });

            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            \Log::error("Erreur Setting::get({$key}): " . $e->getMessage());
            return $default;
        }
    }

    /**
     * Définir une valeur de paramètre
     */
    public static function set($key, $value)
    {
        try {
            $setting = self::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );

            Cache::forget("setting_{$key}");
            Cache::forget('all_settings');

            return $setting;
        } catch (\Exception $e) {
            \Log::error("Erreur Setting::set({$key}): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Supprimer un paramètre
     */
    public static function remove($key)
    {
        Cache::forget("setting_{$key}");
        Cache::forget('all_settings');
        
        return self::where('key', $key)->delete();
    }

    /**
     * Récupérer tous les paramètres
     */
    public static function all($columns = ['*'])
    {
        return Cache::remember('all_settings', 3600, function () use ($columns) {
            return parent::all($columns)->pluck('value', 'key');
        });
    }

    /**
     * Vérifier si une clé existe
     */
    public static function has($key)
    {
        return self::where('key', $key)->exists();
    }
}