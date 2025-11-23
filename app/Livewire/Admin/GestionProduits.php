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

    protected $listeners = ['refreshComponent' => '$refresh'];

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
        $this->variants = [[
            'nom' => 'Standard', 
            'prix' => '', 
            'stock' => '', // Vide par défaut = stock illimité
            'seuil_alerte' => ''
        ]];
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
            'stock' => $v->gerer_stock ? $v->stock : '', // Vide si non géré
            'seuil_alerte' => $v->gerer_stock ? $v->seuil_alerte : ''
        ])->toArray() ?: [[
            'nom' => '', 
            'prix' => '', 
            'stock' => '',
            'seuil_alerte' => ''
        ]];
        $this->showForm = true;
    }

    public function ajouterVariant()
    {
        $this->variants[] = [
            'nom' => '', 
            'prix' => '', 
            'stock' => '',
            'seuil_alerte' => ''
        ];
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
        // Validation de base
        $rules = [
            'nom' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categorie,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'variants.*.nom' => 'required|string',
            'variants.*.prix' => 'required|numeric|min:100',
        ];

        // Validation conditionnelle : si stock rempli, seuil aussi
        foreach ($this->variants as $index => $variant) {
            if (!empty($variant['stock']) && $variant['stock'] !== '') {
                $rules["variants.{$index}.stock"] = 'required|integer|min:0';
                $rules["variants.{$index}.seuil_alerte"] = 'required|integer|min:0';
            }
        }

        $this->validate($rules);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('produits', 'public');
        }

        $produit = Produit::updateOrCreate(['id' => $this->produit_id], [
            'nom' => $this->nom,
            'categorie_id' => $this->categorie_id,
            'image' => $imagePath ?? ($this->produit_id ? Produit::find($this->produit_id)->image : null),
        ]);

        foreach ($this->variants as $variant) {
            // Si stock est vide ou null = stock non géré (illimité)
            $stockRempli = !empty($variant['stock']) && $variant['stock'] !== '';
            
            $dataVariant = [
                'nom' => $variant['nom'],
                'prix' => $variant['prix'],
                'gerer_stock' => $stockRempli,
                'stock' => $stockRempli ? (int)$variant['stock'] : 999,
                'seuil_alerte' => $stockRempli ? (int)($variant['seuil_alerte'] ?? 10) : 0
            ];

            if (isset($variant['id'])) {
                \App\Models\Variant::find($variant['id'])->update($dataVariant);
            } else {
                $produit->variants()->create($dataVariant);
            }
        }

        $this->showForm = false;
        $this->resetForm();
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Produit sauvegardé avec succès !']);
        
        // Force le rechargement complet
        $this->reset(['search', 'categorie_filter']);
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $produit = Produit::findOrFail($id);
        $this->produitToDelete = $id;
        $this->produitNomToDelete = $produit->nom;
        $this->produitImageToDelete = $produit->image ?? '';
        $this->showDeleteModal = true;
    }

    public function supprimer($id)
    {
        $produit = Produit::with('variants')->findOrFail($id);

        if ($produit->image) {
            \Storage::disk('public')->delete($produit->image);
        }

        foreach ($produit->variants as $variant) {
            $variant->delete();
        }

        $produit->delete();

        $this->showDeleteModal = false;
        
        $this->produitToDelete = null;
        $this->produitNomToDelete = '';
        $this->produitImageToDelete = '';

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Produit supprimé avec succès !'
        ]);

        $this->resetPage();
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