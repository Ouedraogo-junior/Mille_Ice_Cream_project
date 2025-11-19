{{-- resources/views/livewire/admin/gestion-categories.blade.php --}}
<div class="min-h-screen">

    {{-- 1. HEADER (Titre et bouton "Nouvelle catégorie") --}}
    @include('livewire.admin.categories.partials.category-header')

    {{-- 2. GRILLE DES CATÉGORIES (La boucle @foreach et le message "Aucun") --}}
    @include('livewire.admin.categories.partials.category-grid')

    {{-- 3. MODAL DE FORMULAIRE (Ajouter/Modifier) --}}
    @include('livewire.admin.categories.partials.category-form-modal')

    {{-- 4. MODAL DE CONFIRMATION DE SUPPRESSION --}}
    @include('livewire.admin.categories.partials.category-delete-modal')

</div>