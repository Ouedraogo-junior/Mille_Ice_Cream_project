{{-- resources/views/livewire/admin/categories/partials/category-grid.blade.php --}}

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
    @foreach($categories as $cat)
        @include('livewire.admin.categories.partials.category-card', ['cat' => $cat])
    @endforeach
</div>

@if($categories->count() === 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
        <i class="fas fa-layer-group text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucune catégorie</h3>
        <p class="text-gray-500 mb-6">Créez votre première catégorie pour organiser vos produits</p>
        <button wire:click="ajouter"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
            <i class="fas fa-plus"></i>
            <span>Créer une catégorie</span>
        </button>
    </div>
@endif