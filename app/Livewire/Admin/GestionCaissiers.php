<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GestionCaissiers extends Component
{
    public $caissiers;
    public $user_id, $name, $email, $password;
    public $showForm = false;

    public function mount()
    {
        $this->caissiers = User::where('role', 'caissier')->get();
    }

    public function render()
    {
        return view('livewire.admin.gestion-caissiers')
            ->layout('layouts.admin');
    }

    public function ajouter()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editer($id)
    {
        $user = User::find($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->showForm = true;
    }

    public function sauvegarder()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$this->user_id,
            'password' => $this->user_id ? 'nullable|min:4' : 'required|min:4'
        ]);

        User::updateOrCreate(['id' => $this->user_id], [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password ? Hash::make($this->password) : null,
            'role' => 'caissier'
        ]);

        $this->caissiers = User::where('role', 'caissier')->get();
        $this->showForm = false;
    }

    private function resetForm()
    {
        $this->reset(['user_id', 'name', 'email', 'password']);
    }
}