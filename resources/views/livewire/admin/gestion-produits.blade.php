{{-- resources/views/livewire/admin/gestion-produits.blade.php --}}
<div class="min-h-screen">

    @include('livewire.admin.produits.partials.header')

    @include('livewire.admin.produits.partials.filters')

    @include('livewire.admin.produits.partials.product-grid')

    @if($showForm)
        @include('livewire.admin.produits.partials.product-form-modal')
    @endif

    @if($showDeleteModal)
        @include('livewire.admin.produits.partials.delete-modal')
    @endif

</div>