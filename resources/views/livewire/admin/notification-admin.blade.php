<div class="relative" x-data="{ open: {{ $showDropdown ? 'true' : 'false' }} }">
    <!-- Cloche -->
    <button
        @click="open = !open; $wire.toggleDropdown()"
        class="relative p-3 text-gray-600 hover:text-cyan-600 hover:bg-gray-100 rounded-xl transition-all duration-200 z-10">
        <i class="fas fa-bell text-2xl"></i>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center animate-pulse">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown ULTRA RESPONSIVE (version définitive 2025) -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-x-4 top-20 mx-auto                          /* Pleine largeur avec marges sécurisées */
               sm:absolute sm:inset-x-auto sm:top-full sm:mt-2 sm:right-0   /* Retour à droite sur ≥640px */
               w-auto max-w-sm sm:max-w-96
               bg-white rounded-2xl shadow-2xl border border-gray-200 z-50 overflow-hidden">

        <!-- En-tête -->
        <div class="p-4 bg-gradient-to-r from-cyan-500 to-blue-600 text-white">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Notifications</h3>
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead" class="text-sm hover:underline opacity-90">
                        Tout marquer comme lu
                    </button>
                @endif
            </div>
        </div>

        <!-- Contenu -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notif)
<div wire:key="{{ $notif['id'] }}"
class="p-4 border-b border-gray-100 hover:bg-gray-50 transition {{ !$notif['read'] ? 'bg-blue-50' : '' }}">
<div class="flex justify-between items-start gap-3">
<div class="flex-1 min-w-0">
    <p class="text-sm font-medium text-gray-800 truncate">{{ $notif['message'] }}</p>
    <p class="text-xs text-gray-500 mt-1">
        <i class="fas fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($notif['created_at'])->diffForHumans() }}
    </p>
</div>
@if(!$notif['read'])
    <button wire:click="markAsRead({{ $notif['id'] }})"
            class="flex-shrink-0 text-cyan-600 hover:text-cyan-700">
        <i class="fas fa-check-circle"></i>
    </button>
@endif
</div>
</div>
@empty
    <div class="p-12 text-center text-gray-400">
        <i class="fas fa-bell-slash text-5xl mb-4"></i>
        <p class="text-lg">Aucune notification</p>
        <p class="text-sm mt-2">Vous êtes à jour !</p>
    </div>
@endforelse
        </div>
    </div>
</div>