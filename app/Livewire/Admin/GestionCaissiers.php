<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class GestionCaissiers extends Component
{
    public function render()
    {
        return view('livewire.admin.gestion-caissiers')
                ->layout('layouts.admin');
    }
}
