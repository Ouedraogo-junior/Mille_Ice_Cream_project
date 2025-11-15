<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class GestionProduits extends Component
{
   public function render()
    {
        return view('livewire.admin.gestion-produits')
                ->layout('layouts.admin');
    }
}
