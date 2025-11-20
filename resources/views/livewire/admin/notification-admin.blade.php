<div class="relative" x-data="{ open: false }">
    <!-- Cloche -->
    <button
        @click="open = !open"
        type="button"
        class="relative p-3 text-gray-600 hover:text-cyan-600 hover:bg-gray-100 rounded-xl transition-all duration-200">
        <i class="fas fa-bell text-2xl"></i>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center animate-pulse ring-4 ring-red-200">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition
        class="absolute top-full mt-2 right-0 w-96 bg-white rounded-2xl shadow-2xl border border-gray-200 z-50 overflow-hidden">
        
        <div class="p-4 bg-gradient-to-r from-cyan-500 to-blue-600 text-white flex justify-between items-center">
            <h3 class="text-lg font-bold">Alertes Stock</h3>
            @if($unreadCount > 0)
                <button wire:click="resetAlerts" class="text-sm hover:underline">
                    Réinitialiser
                </button>
            @endif
        </div>

        <div class="p-8 text-center">
            @if($unreadCount == 0)
                <i class="fas fa-check-circle text-6xl text-green-500 mb-4"></i>
                <p class="text-lg font-medium text-gray-700">Tout est sous contrôle !</p>
            @else
                <i class="fas fa-exclamation-triangle text-7xl text-orange-500 animate-pulse mb-4"></i>
                <p class="text-3xl font-bold text-orange-600">{{ $unreadCount }}</p>
                <p class="text-lg text-gray-600 mt-2">
                    {{ \Illuminate\Support\Str::plural('produit en stock critique', $unreadCount) }}
                </p>
            @endif
        </div>
    </div>
</div>