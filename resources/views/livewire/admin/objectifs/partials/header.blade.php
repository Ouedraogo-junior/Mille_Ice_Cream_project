{{-- resources/views/livewire/admin/objectifs/partials/header.blade.php --}}
<div class="bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-600 rounded-3xl shadow-2xl p-8 mb-8">
    <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
        <div class="text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-3 flex items-center gap-3">
                <i class="fas fa-bullseye"></i>
                Objectifs du Glacier
            </h1>
            <p class="text-cyan-100 text-lg">
                Suivez et motivez l'équipe avec des objectifs clairs et ambitieux !
            </p>
        </div>
        
        {{-- Statistiques rapides --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-3 text-center">
                <div class="text-2xl font-bold text-white">{{ $statistiques['en_cours'] }}</div>
                <div class="text-xs text-cyan-100">En cours</div>
            </div>
            <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-3 text-center">
                <div class="text-2xl font-bold text-white">{{ $statistiques['atteints'] }}</div>
                <div class="text-xs text-cyan-100">Atteints</div>
            </div>
            <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-3 text-center">
                <div class="text-2xl font-bold text-white">{{ $statistiques['taux_reussite'] }}%</div>
                <div class="text-xs text-cyan-100">Taux succès</div>
            </div>
            <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-3 text-center">
                <i class="fas fa-calendar-day text-white text-xl mb-1"></i>
                <div class="text-xs text-cyan-100">{{ now()->locale('fr')->isoFormat('D MMM') }}</div>
            </div>
        </div>
    </div>
</div>