{{-- resources/views/livewire/admin/gestion-objectifs.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-cyan-50 p-4 md:p-8">

    {{-- En-tête avec statistiques --}}
    @include('livewire.admin.objectifs.partials.header', ['statistiques' => $statistiques])

    {{-- Barre d'actions --}}
    @include('livewire.admin.objectifs.partials.actions-bar')

    {{-- Liste des objectifs --}}
    @include('livewire.admin.objectifs.partials.liste-objectifs', ['objectifs' => $objectifs])

    {{-- Hall of Fame --}}
    @include('livewire.admin.objectifs.partials.hall-of-fame', ['atteints' => $atteints])

    {{-- Modals --}}
    @include('livewire.admin.objectifs.partials.modal-formulaire')
    @include('livewire.admin.objectifs.partials.modal-suppression')

    {{-- ✅ CORRECTION : Le style est maintenant À L'INTÉRIEUR de la div racine --}}
    <style>
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes scale-in {
            from { 
                opacity: 0;
                transform: scale(0.9);
            }
            to { 
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
        
        .animate-scale-in {
            animation: scale-in 0.3s ease-out;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

</div> {{-- Fin de la div racine unique --}}

{{-- @push n'est pas rendu dans le DOM du composant, donc il peut rester dehors --}}
@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Alpine.js est déjà chargé
    });
</script>
@endpush