<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GestionCaissiers extends Component
{
    public $caissiers;

    // Champs du formulaire
    public $user_id;
    public $name;
    public $email;
    public $pseudo;
    public $password;

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
        $user = User::findOrFail($id);

        $this->user_id   = $user->id;
        $this->name      = $user->name;
        $this->email     = $user->email;
        $this->pseudo    = $user->pseudo;
        $this->password  = ''; 
        $this->showForm  = true;
    }

    public function sauvegarder()
{
    // Règles de validation
    $rules = [
        'name'     => 'required|string|max:255',
        'pseudo'   => 'nullable|string|max:30|unique:users,pseudo,' . $this->user_id,
        'email'    => 'nullable|email|max:255|unique:users,email,' . $this->user_id,
        'password' => $this->user_id ? 'nullable|min:4' : 'required|min:4',
    ];

    $this->validate($rules);

    // Au moins un identifiant doit être renseigné
    if (empty($this->email) && empty($this->pseudo)) {
        $this->addError('email', 'Vous devez renseigner soit l\'email, soit le pseudo.');
        $this->addError('pseudo', 'Vous devez renseigner soit l\'email, soit le pseudo.');
        return;
    }

    // Préparer les données
    $data = [
        'name'  => $this->name,
        'role'  => 'caissier',
        'email' => !empty($this->email) ? $this->email : null,  // ← Important : NULL si vide
        'pseudo' => !empty($this->pseudo) ? strtolower($this->pseudo) : null,  // ← Important : NULL si vide
    ];

    // Mot de passe uniquement si renseigné
    if (!empty($this->password)) {
        $data['password'] = Hash::make($this->password);
    }

    User::updateOrCreate(['id' => $this->user_id], $data);

    $this->caissiers = User::where('role', 'caissier')->get();
    $this->showForm = false;
    $this->dispatch('toast', message: 'Caissier sauvegardé avec succès !', type: 'success');
}

    private function resetForm()
    {
        $this->reset(['user_id', 'name', 'email', 'pseudo', 'password']);
    }

    public function annuler()
    {
        $this->showForm = false;
        $this->resetForm();
    }
}