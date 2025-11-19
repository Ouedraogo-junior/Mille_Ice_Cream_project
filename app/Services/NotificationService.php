<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Vérifie si l'utilisateur a activé un type de notification
     */
    public function isNotificationEnabled(User $user, string $type): bool
    {
        $preferences = $user->notification_preferences ?? [];
        
        // Par défaut, toutes les notifications sont activées
        return $preferences[$type] ?? true;
    }

    /**
     * Envoie une notification si l'utilisateur l'a activée
     */
    public function sendNotification(User $user, string $type, string $message, array $data = [])
    {
        if (!$this->isNotificationEnabled($user, $type)) {
            return null; // Notification désactivée
        }

        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'message' => $message,
            'data' => $data,
            'read' => false,
        ]);
    }

    /**
     * Met à jour les préférences de notification
     */
    public function updatePreferences(User $user, array $preferences)
    {
        $user->update([
            'notification_preferences' => $preferences
        ]);
    }

    /**
     * Récupère les préférences par défaut
     */
    public function getDefaultPreferences(): array
    {
        return [
            'stock_alert' => true,
            'sale_completed' => true,
            'daily_report' => true,
        ];
    }
}