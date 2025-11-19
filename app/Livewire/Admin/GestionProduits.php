<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;           
use App\Models\Produit;
use App\Models\Categorie;

class GestionProduits extends Component
{
    use WithPagination;
    use WithFileUploads;                 

    public $search = '';
    public $categorie_filter = '';

    // Modal de suppression
    public $showDeleteModal = false;
    public $produitToDelete = null;
    public $produitNomToDelete = '';
    public $produitImageToDelete = '';

    // Formulaire modification/ajout
    public $produit_id;
    public $nom;
    public $categorie_id;
    public $image;                        
    public $variants = [];
    public $showForm = false;

    public function render()
    {
        $produits = Produit::with(['categorie', 'variants'])
            ->when($this->search, fn($q) => $q->where('nom', 'like', "%{$this->search}%"))
            ->when($this->categorie_filter, fn($q) => $q->where('categorie_id', $this->categorie_filter))
            ->latest()
            ->paginate(12);

        $categories = Categorie::all();

        return view('livewire.admin.gestion-produits', [
            'produits' => $produits,
            'categories' => $categories
        ])->layout('layouts.admin');
    }

    public function ajouter()
    {
        $this->resetForm();
        $this->image = null;
        $this->variants = [['nom' => 'Standard', 'prix' => '', 'stock' => 999]];
        $this->showForm = true;
    }

    public function editer($id)
    {
        $p = Produit::with('variants')->findOrFail($id);
        $this->produit_id = $p->id;
        $this->nom = $p->nom;
        $this->categorie_id = $p->categorie_id;
        $this->image = null; 
        $this->variants = $p->variants->map(fn($v) => [
            'id' => $v->id,
            'nom' => $v->nom,
            'prix' => $v->prix,
            'stock' => $v->stock
        ])->toArray() ?: [['nom' => '', 'prix' => '', 'stock' => 999]];
        $this->showForm = true;
    }

    public function ajouterVariant()
    {
        $this->variants[] = ['nom' => '', 'prix' => '', 'stock' => 999];
    }

    public function supprimerVariant($index)
    {
        if (isset($this->variants[$index]['id'])) {
            \App\Models\Variant::destroy($this->variants[$index]['id']);
        }
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function sauvegarder()
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categorie,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'variants.*.nom' => 'required|string',
            'variants.*.prix' => 'required|numeric|min:100'
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('produits', 'public');
        }

        $produit = Produit::updateOrCreate(['id' => $this->produit_id], [
            'nom' => $this->nom,
            'categorie_id' => $this->categorie_id,
            'image' => $imagePath ?? ($this->produit_id ? Produit::find($this->produit_id)->image : null),
            // 'active' => true
        ]);

        foreach ($this->variants as $variant) {
            if (isset($variant['id'])) {
                \App\Models\Variant::find($variant['id'])->update([
                    'nom' => $variant['nom'],
                    'prix' => $variant['prix'],
                    'stock' => $variant['stock'] ?? 999
                ]);
            } else {
                $produit->variants()->create([
                    'nom' => $variant['nom'],
                    'prix' => $variant['prix'],
                    'stock' => $variant['stock'] ?? 999
                ]);
            }
        }

        $this->showForm = false;
        $this->resetForm();
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Produit sauvegardé avec succès !']);

        $this->dispatch('$refresh');
    }

    // Méthode pour ouvrir le modal de confirmation de suppression
    public function confirmDelete($id)
    {
        $produit = Produit::findOrFail($id);
        $this->produitToDelete = $id;
        $this->produitNomToDelete = $produit->nom;
        $this->produitImageToDelete = $produit->image ?? '';
        $this->showDeleteModal = true;
    }

    // Méthode de suppression effective
    public function supprimer($id)
    {
        $produit = Produit::with('variants')->findOrFail($id);

        // Supprime l'image du produit si elle existe
        if ($produit->image) {
            \Storage::disk('public')->delete($produit->image);
        }

        // Supprime toutes les variantes associées
        foreach ($produit->variants as $variant) {
            $variant->delete();
        }

        // Supprime le produit
        $produit->delete();

        // Ferme le modal
        $this->showDeleteModal = false;
        
        // Réinitialise les données de suppression
        $this->produitToDelete = null;
        $this->produitNomToDelete = '';
        $this->produitImageToDelete = '';

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Produit supprimé avec succès !'
        ]);

        $this->dispatch('$refresh');
    }

    private function resetForm()
    {
        $this->reset([
            'produit_id', 
            'nom', 
            'categorie_id', 
            'image', 
            'variants'
        ]);
    }
}