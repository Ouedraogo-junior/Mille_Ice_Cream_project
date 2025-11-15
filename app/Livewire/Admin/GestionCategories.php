<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Categorie;

class GestionCategories extends Component
{
    public $nom = '';
    public $couleur = 'blue';
    public $categorie_id;
    public $showForm = false;

    protected $couleurs = [
        'red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald',
        'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'pink', 'rose'
    ];

    public function render()
    {
        $categories = Categorie::all();

        // ON PASSE $couleurs À LA VUE ICI AUSSI (OBLIGATOIRE)
        return view('livewire.admin.gestion-categories', [
            'categories' => $categories,
            'couleurs' => $this->couleurs
        ])->layout('layouts.admin');
    }

    public function ajouter()
    {
        $this->reset(['nom', 'couleur', 'categorie_id']);
        $this->couleur = 'blue';
        $this->showForm = true;
    }

    public function editer($id)
    {
        $cat = Categorie::findOrFail($id);
        $this->categorie_id = $cat->id;
        $this->nom = $cat->nom;
        $this->couleur = $cat->couleur;
        $this->showForm = true;
    }

    public function sauvegarder()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'couleur' => 'required|in:'.implode(',', $this->couleurs)
        ]);

        Categorie::updateOrCreate(
            ['id' => $this->categorie_id],
            ['nom' => $this->nom, 'couleur' => $this->couleur, 'active' => true]
        );

        $this->showForm = false;
        $this->dispatch('toast', 'Catégorie sauvegardée !');
    }

    public function desactiver($id)
    {
        Categorie::findOrFail($id)->update(['active' => false]);
        $this->dispatch('toast', 'Catégorie désactivée');
    }
}