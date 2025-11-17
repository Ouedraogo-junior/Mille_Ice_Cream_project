<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Categorie;

class GestionCategories extends Component
{
    // Propriétés du formulaire
    public $nom = '';
    public $couleur = 'blue';
    public $categorie_id;
    public $showForm = false;

    // Propriétés pour la suppression
    public $showDeleteModal = false;
    public $categorieIdToDelete;
    public $categorieNomToDelete = '';
    public $categorieColorToDelete = '';
    public $categorieProduitCount = 0;

    protected $couleurs = [
        'red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald',
        'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'pink', 'rose'
    ];

    // Règles de validation pour le formulaire
    protected function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'couleur' => 'required|in:'.implode(',', $this->couleurs)
        ];
    }

    public function render()
    {
        $categories = Categorie::withCount('produits')->get();

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
        $this->validate();

        Categorie::updateOrCreate(
            ['id' => $this->categorie_id],
            ['nom' => $this->nom, 'couleur' => $this->couleur, 'active' => true]
        );

        $this->showForm = false;
        $this->dispatch('toast', [
            'message' => $this->categorie_id ? 'Catégorie modifiée !' : 'Nouvelle catégorie ajoutée !',
            'type' => 'success'
        ]);

        $this->resetErrorBag();
    }

    public function toggleActive($id)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->active = !$categorie->active;
        $categorie->save();

        $this->dispatch('toast', [
            'type' => $categorie->active ? 'success' : 'info',
            'message' => $categorie->active 
                ? 'Catégorie activée avec succès !' 
                : 'Catégorie désactivée avec succès !'
        ]);
    }

    public function confirmDelete($id)
    {
        $cat = Categorie::withCount('produits')->findOrFail($id);
        
        $this->categorieIdToDelete = $cat->id;
        $this->categorieNomToDelete = $cat->nom;
        $this->categorieColorToDelete = $cat->couleur;
        $this->categorieProduitCount = $cat->produits_count;
        
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->categorieIdToDelete) {
            $cat = Categorie::withCount('produits')->findOrFail($this->categorieIdToDelete);
            
            // VÉRIFICATION : Empêcher la suppression si des produits sont liés
            if ($cat->produits_count > 0) {
                $this->showDeleteModal = false;
                
                $this->dispatch('toast', [
                    'message' => "Impossible de supprimer cette catégorie ! Elle contient {$cat->produits_count} produit(s). Veuillez d'abord réassigner ou supprimer ces produits.",
                    'type' => 'error'
                ]);
                
                $this->reset(['categorieIdToDelete', 'categorieNomToDelete', 'categorieColorToDelete', 'categorieProduitCount']);
                return;
            }
            
            // Suppression autorisée (aucun produit lié)
            $cat->delete();
            
            $this->dispatch('toast', [
                'message' => 'Catégorie supprimée avec succès !',
                'type' => 'success'
            ]);
        }

        $this->showDeleteModal = false;
        $this->reset(['categorieIdToDelete', 'categorieNomToDelete', 'categorieColorToDelete', 'categorieProduitCount']);
    }
}