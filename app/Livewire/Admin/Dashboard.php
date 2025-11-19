<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Produit;
use App\Models\Variant;
use App\Models\User;
use App\Models\Categorie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Dashboard extends Component
{
    public $totalVentesAujourdHui = 0;
    public $totalProduits = 0;
    public $produitsEnRupture = 0;
    public $caissiersActifs = 0;
    public $totalVariants = 0;
    public $totalCategories = 0;
    public $valeurStock = 0;
    public $produitsEnAlerte;

    public function mount()
    {
        // Statistiques de base
        $this->totalProduits = Variant::where('active', true)->count();
        $this->caissiersActifs = User::where('role', 'caissier')->count();
        $this->produitsEnRupture = Variant::where('stock', '<=', 5)->count();
        $this->totalVariants = Variant::count();
        $this->totalCategories = Categorie::where('active', true)->count();

        // Produits en alerte de stock (pour affichage détaillé)
        $this->produitsEnAlerte = Variant::with('produit')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        // Valeur totale du stock (prix * stock de toutes les variantes)
        $this->valeurStock = Variant::selectRaw('SUM(prix * stock) as total')
            ->value('total') ?? 0;

        // Ventes du jour (sécurisé)
        if (Schema::hasTable('ventes')) {
            $this->totalVentesAujourdHui = DB::table('ventes')
                ->whereDate('created_at', today())
                ->sum('montant');
        } else {
            $this->totalVentesAujourdHui = 0;
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('layouts.admin');
    }
}