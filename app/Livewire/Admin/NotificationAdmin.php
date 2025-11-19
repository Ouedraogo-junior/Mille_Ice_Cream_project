<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationAdmin extends Component
{
    public $unreadCount = 0;
    public $notifications = [];
    public $showDropdown = false;

    public function mount()
    {
        $this->refreshNotifications();
    }

    /**
     * Configuration des écouteurs d'événements
     * Format: echo-notification:nom-du-canal,NomEvent
     */
    public function getListeners()
    {
        $userId = Auth::id();
        
        return [
            // Écouter le canal privé des notifications de cet admin
            "echo-notification:notifications.{$userId},.NotificationSent" => 'handleNewNotification',
            
            // Événements Livewire internes
            'notificationRead' => 'refreshNotifications',
            'refreshNotifications' => 'refreshNotifications'
        ];
    }

    /**
     * Gère la réception d'une nouvelle notification via Reverb
     */
    public function handleNewNotification($payload)
    {
        // Rafraîchir la liste
        $this->refreshNotifications();
        
        // Afficher un toast de notification
        $this->dispatch('toast', [
            'type' => $payload['type'] === 'rupture_stock' ? 'error' : 'warning',
            'message' => $payload['message'] ?? 'Nouvelle notification'
        ]);

        // Jouer un son (optionnel)
        $this->dispatch('playNotificationSound');
    }

    /**
     * Rafraîchit la liste des notifications
     */
    public function refreshNotifications()
    {
        $user = Auth::user();
        
        if (!$user) {
            return;
        }

        $this->unreadCount = $user->notifications()
            ->where('read', false)
            ->count();
            
        $this->notifications = $user->notifications()
            ->latest()
            ->take(15)
            ->get();
    }

    /**
     * Toggle du dropdown de notifications
     */
    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
        
        if ($this->showDropdown) {
            $this->refreshNotifications();
        }
    }

    /**
     * Marque une notification comme lue
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            $this->refreshNotifications();
            $this->dispatch('notificationRead');
        }
    }

    /**
     * Marque toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()
            ->where('read', false)
            ->update([
                'read' => true,
                'read_at' => now()
            ]);
            
        $this->refreshNotifications();
        $this->dispatch('notificationRead');
    }

    /**
     * Supprime une notification
     */
    public function deleteNotification($id)
    {
        Auth::user()->notifications()
            ->where('id', $id)
            ->delete();
            
        $this->refreshNotifications();
    }

    public function render()
    {
        return view('livewire.admin.notification-admin');
    }
}