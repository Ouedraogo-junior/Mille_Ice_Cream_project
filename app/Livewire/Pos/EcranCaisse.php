<?php

namespace App\Livewire\Pos;

use Livewire\Component;

class EcranCaisse extends Component
{
    public function render()
{
    return view('livewire.pos.ecran-caisse')
            ->layout('layouts.caissier');
}
}
