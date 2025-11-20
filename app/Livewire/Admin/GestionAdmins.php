<?php
// app/Livewire/Admin/GestionAdmins.php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class GestionAdmins extends Component
{
    public $admins;
    public $user_id, $name, $email, $pseudo, $password, $password_confirmation;
    public $showForm = false;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->loadAdmins();
    }

    public function loadAdmins()
    {
        $this->admins = User::where('role', 'admin')
                            ->orWhere('is_super_admin', true)
                            ->orderByDesc('is_super_admin')
                            ->get();
    }

    public function ajouter()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editer($id)
    {
        $user = User::findOrFail($id);

        $this->user_id = $user->id;
        $this->name    = $user->name;
        $this->email   = $user->email;
        $this->pseudo  = $user->pseudo;
        $this->showForm = true;
    }

    public function sauvegarder()
    {
        // Validation
        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,'.$this->user_id,
            'pseudo'=> 'nullable|string|max:30|unique:users,pseudo,'.$this->user_id,
            'password' => $this->user_id ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',
        ]);

        if (empty($this->email) && empty($this->pseudo)) {
            $this->addError('email', 'Vous devez renseigner soit l\'email, soit le pseudo.');
            return;
        }

        User::updateOrCreate(['id' => $this->user_id], [
            'name'     => $this->name,
            'email'    => $this->email ? strtolower($this->email) : null,
            'pseudo'   => $this->pseudo ? strtolower($this->pseudo) : null,
            'password' => $this->password ? Hash::make($this->password) : null,
            'role'     => 'admin',
            'is_super_admin' => false, // un admin ne peut PAS créer un super-admin
        ]);

        $this->dispatch('toast', message: 'Administrateur sauvegardé !', type: 'success');
        $this->showForm = false;
        $this->loadAdmins();
    }

    public function supprimer($id)
    {
        $user = User::findOrFail($id);

        // Protection ultime
        if ($user->is_super_admin) {
            $this->dispatch('toast', message: 'Impossible de supprimer un super-administrateur.', type: 'error');
            return;
        }

        if ($user->id === auth()->id()) {
            $this->dispatch('toast', message: 'Vous ne pouvez pas supprimer votre propre compte !', type: 'error');
            return;
        }

        $adminCount = User::where('role', 'admin')->where('is_super_admin', false)->count();
        if ($adminCount <= 1 && !$user->is_super_admin) {
            $this->dispatch('toast', message: 'Impossible : il doit rester au moins un administrateur.', type: 'error');
            return;
        }

        $user->delete();
        $this->dispatch('toast', message: 'Administrateur supprimé.', type: 'success');
        $this->loadAdmins();
    }

    private function resetForm()
    {
        $this->reset(['user_id', 'name', 'email', 'pseudo', 'password', 'password_confirmation']);
        $this->resetValidation();
    }

    public function annuler()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.admin.gestion-admins')->layout('layouts.admin');
    }
}