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

    public function getListeners()
{
    return [
        // Cette syntaxe est la SEULE qui marche avec Reverb + queued notifications en 2025
        'echo-private:admin.notifications,Illuminate\Notifications\Events\BroadcastNotificationCreated' => 'handleRealtimeNotification',

        'notificationRead'      => 'refreshNotifications',
        'refreshNotifications' => 'refreshNotifications',
    ];
}

    // FONCTION QUI REÇOIT LA NOTIFICATION EN TEMPS RÉEL (même avec queue)
    public function handleRealtimeNotification($event)
    {
        // On ne traite que nos alertes stock
        if (($event['type'] ?? '') !== 'App\\Notifications\\StockAlertNotification') {
            return;
        }

        $data = $event['notification']['data'] ?? $event['data'];

        \Log::info('Notification stock reçue en temps réel !', $data);

        // Toast + son
        $this->dispatch('toast', [
            'type'    => $data['type'] === 'rupture_stock' ? 'error' : 'warning',
            'title'   => $data['type'] === 'rupture_stock' ? 'RUPTURE DE STOCK !' : 'Stock faible',
            'message' => $data['message'],
        ]);

        $this->dispatch('playNotificationSound');

        // Rafraîchit le badge et la liste instantanément
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        $user = Auth::user();
        if (!$user) return;

        $this->unreadCount = $user->unreadNotifications()->count();

        $this->notifications = $user->notifications()
            ->latest()
            ->take(15)
            ->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'type'       => $n->data['type'] ?? 'info',
                'message'    => $n->data['message'] ?? 'Notification',
                'data'       => $n->data,
                'read'       => !is_null($n->read_at),
                'created_at' => $n->created_at,
            ])->toArray();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
        if ($this->showDropdown) {
            $this->refreshNotifications();
        }
    }

    public function markAsRead($id)
    {
        Auth::user()->notifications()->find($id)?->markAsRead();
        $this->refreshNotifications();
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->refreshNotifications();
    }

    public function render()
    {
        return view('livewire.admin.notification-admin');
    }
}