<?php
namespace App\Livewire\Admin;

use Livewire\Component;

class NotificationAdmin extends Component
{
    public $unreadCount = 0;
    public $showDropdown = false;

    // ✅ UTILISER $listeners
    protected $listeners = ['stockAlert'];

    public function mount()
    {
        \Log::info('🟢 NotificationAdmin monté');
    }

    public function stockAlert($data)
    {
        \Log::info('📢 stockAlert reçu', [
            'data' => $data,
            'unreadCount_avant' => $this->unreadCount
        ]);

        $this->unreadCount++;
        
        \Log::info('📢 unreadCount après: ' . $this->unreadCount);

        $type = ($data['restant'] ?? 1) == 0 ? 'error' : 'warning';
        $message = ($data['message'] ?? 'Stock bas') . ' – Restant : ' . ($data['restant'] ?? 0);
        
        $this->dispatch('toast', ['message' => $message, 'type' => $type]);
        $this->dispatch('playNotificationSound');
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function resetAlerts()
    {
        \Log::info('🔄 Reset alerts');
        $this->unreadCount = 0;
    }

    public function render()
    {
        return view('livewire.admin.notification-admin');
    }
}