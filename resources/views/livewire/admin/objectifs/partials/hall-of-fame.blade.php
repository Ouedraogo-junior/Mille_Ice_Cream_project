{{-- resources/views/livewire/admin/objectifs/partials/hall-of-fame.blade.php --}}
@if($atteints->count())
    <div class="mt-16">
        <div class="text-center mb-8">
            <h2 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-green-600 mb-3 inline-flex items-center gap-3">
                <i class="fas fa-trophy text-yellow-500"></i>
                Hall of Fame
            </h2>
            <p class="text-gray-600">{{ $atteints->count() }} objectif(s) atteint(s) 🎉</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @foreach($atteints as $a)
                <div class="group bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50 border-2 border-emerald-200 rounded-2xl p-6 text-center shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 hover:-rotate-1">
                    <div class="text-5xl mb-3 group-hover:animate-bounce">🏆</div>
                    <div class="font-bold text-emerald-800 text-sm mb-2">{{ $a->titre }}</div>
                    <div class="text-xs text-emerald-600">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ $a->updated_at->format('d/m/Y') }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif