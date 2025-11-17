<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationAdmin extends Component
{
    public $unreadCount = 0;
    public $notifications = [];
    public $showDropdown = false;

    protected $listeners = [
        'echo:notifications,NotificationSent' => 'refreshNotifications',
        'notificationRead' => 'refreshNotifications'
    ];

    public function mount()
    {
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        $user = Auth::user();
        $this->unreadCount = $user->notifications()->where('read', false)->count();
        $this->notifications = $user->notifications()->latest()->take(15)->get();
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
        Auth::user()->notifications()->where('id', $id)->update([
            'read' => true,
            'read_at' => now()
        ]);
        $this->refreshNotifications();
        $this->dispatch('notificationRead');
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()->where('read', false)->update([
            'read' => true,
            'read_at' => now()
        ]);
        $this->refreshNotifications();
        $this->dispatch('notificationRead');
    }

    public function render()
    {
        return view('livewire.admin.notification-admin');
    }
}