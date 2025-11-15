<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Rapports extends Component
{
    public function render()
    {
        return view('livewire.admin.rapports')
                ->layout('layouts.admin');
    }
}
