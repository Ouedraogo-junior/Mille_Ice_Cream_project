<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Vente;
use App\Models\User;
use App\Models\Categorie;
use App\Models\VenteDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class Rapports extends Component
{
    public $dateDebut;
    public $dateFin;

    public $chiffreAffaires = 0;
    public $nombreVentes = 0;
    public $panierMoyen = 0;
    public $produitsVendus = 0;
    public $evolutionCA = 0;

    public $topProduits;
    public $ventesParCategorie;
    public $performanceCaissiers;
    public $evolutionVentes;

    public function mount()
    {
        $this->dateDebut = now()->startOfMonth()->format('Y-m-d');
        $this->dateFin = now()->format('Y-m-d');

        $this->chargerDonnees();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['dateDebut', 'dateFin'])) {
            $this->chargerDonnees();
        }
    }

    public function setToday()
    {
        $this->dateDebut = now()->format('Y-m-d');
        $this->dateFin = now()->format('Y-m-d');
        $this->chargerDonnees();
    }

    public function setWeek()
    {
        $this->dateDebut = now()->startOfWeek()->format('Y-m-d');
        $this->dateFin = now()->endOfWeek()->format('Y-m-d');
        $this->chargerDonnees();
    }

    public function setMonth()
    {
        $this->dateDebut = now()->startOfMonth()->format('Y-m-d');
        $this->dateFin = now()->format('Y-m-d');
        $this->chargerDonnees();
    }

    public function chargerDonnees()
    {
        if (!Schema::hasTable('ventes')) {
            $this->resetStats();
            return;
        }

        $ventes = Vente::whereBetween('created_at', [
            $this->dateDebut . ' 00:00:00',
            $this->dateFin . ' 23:59:59'
        ])->get();

        $this->nombreVentes = $ventes->count();
        $this->chiffreAffaires = $ventes->sum('montant');
        $this->panierMoyen = $this->nombreVentes > 0
            ? $this->chiffreAffaires / $this->nombreVentes
            : 0;

        $this->produitsVendus = VenteDetail::whereIn('vente_id', $ventes->pluck('id'))
            ->sum('quantite');

        $joursDiff = Carbon::parse($this->dateDebut)
            ->diffInDays(Carbon::parse($this->dateFin)) + 1;

        $datePrecedenteDebut = Carbon::parse($this->dateDebut)
            ->subDays($joursDiff)->format('Y-m-d');
        $datePrecedenteFin = Carbon::parse($this->dateFin)
            ->subDays($joursDiff)->format('Y-m-d');

        $caPrecedent = Vente::whereBetween('created_at', [
            $datePrecedenteDebut . ' 00:00:00',
            $datePrecedenteFin . ' 23:59:59'
        ])->sum('montant');

        $this->evolutionCA = $caPrecedent > 0
            ? (($this->chiffreAffaires - $caPrecedent) / $caPrecedent * 100)
            : 0;

        $this->topProduits = VenteDetail::select(
            'produit.nom as produit_nom',           // ← table "produit"
            'variants.nom as variant_nom',
            'variants.id as variant_id',
            DB::raw('SUM(vente_details.quantite) AS total_vendus'),
            DB::raw('SUM(vente_details.prix_unitaire * vente_details.quantite) AS total_ca')
        )
        ->join('ventes', 'vente_details.vente_id', '=', 'ventes.id')
        ->join('variants', 'vente_details.variant_id', '=', 'variants.id')
        ->join('produit', 'variants.produit_id', '=', 'produit.id')  // ← "produit" au singulier !
        ->whereBetween('ventes.created_at', [
            $this->dateDebut . ' 00:00:00',
            $this->dateFin . ' 23:59:59'
        ])
        ->groupBy('variants.id', 'variants.nom', 'produit.nom')     // ← aussi ici
        ->orderByDesc('total_ca')
        ->limit(10)
        ->get();

        /**
         * 🔥 VENTES PAR CATÉGORIE
         */
        $this->ventesParCategorie = VenteDetail::select(
                'categorie.nom',
                'categorie.couleur',
                DB::raw('SUM(vente_details.prix_unitaire * vente_details.quantite) AS total_ca')
            )
            ->join('ventes', 'vente_details.vente_id', '=', 'ventes.id')
            ->join('variants', 'vente_details.variant_id', '=', 'variants.id')
            ->join('produit', 'variants.produit_id', '=', 'produit.id')
            ->join('categorie', 'produit.categorie_id', '=', 'categorie.id')
            ->whereBetween('ventes.created_at', [
                $this->dateDebut . ' 00:00:00',
                $this->dateFin . ' 23:59:59'
            ])
            ->groupBy('categorie.id', 'categorie.nom', 'categorie.couleur')
            ->orderByDesc('total_ca')
            ->get();

        /**
         * 🔥 PERFORMANCE DES CAISSIERS
         */
        $this->performanceCaissiers = Vente::select(
                'users.name',
                DB::raw('COUNT(ventes.id) as nombre_ventes'),
                DB::raw('SUM(ventes.montant) as total_ca')
            )
            ->join('users', 'ventes.user_id', '=', 'users.id')
            ->whereBetween('ventes.created_at', [
                $this->dateDebut . ' 00:00:00',
                $this->dateFin . ' 23:59:59'
            ])
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_ca')
            ->get();

        /**
         * 🔥 ÉVOLUTION DES VENTES PAR JOUR
         */
        $this->evolutionVentes = Vente::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as nombre'),
                DB::raw('SUM(montant) as total')
            )
            ->whereBetween('created_at', [
                $this->dateDebut . ' 00:00:00',
                $this->dateFin . ' 23:59:59'
            ])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
    }

    /**
     * Exporter les rapports en PDF
     */
    public function exporterPDF()
{
    try {
        // Recharger les données
        $this->chargerDonnees();
        
        // IMPORTANT : Convertir TOUTES les collections en tableaux
        // Car DomPDF ne gère pas bien les collections Livewire
        $donnees = [
            'dateDebut' => $this->dateDebut,
            'dateFin' => $this->dateFin,
            'chiffreAffaires' => $this->chiffreAffaires,
            'nombreVentes' => $this->nombreVentes,
            'panierMoyen' => $this->panierMoyen,
            'produitsVendus' => $this->produitsVendus,
            'evolutionCA' => $this->evolutionCA,
            'dateGeneration' => Carbon::now()->format('d/m/Y H:i'),
            
            // Convertir les collections en objets stdClass pour éviter les problèmes
            'topProduits' => $this->topProduits->map(function($item) {
                return (object)[
                    'produit_nom' => $item->produit_nom ?? '',
                    'variant_nom' => $item->variant_nom ?? '',
                    'variant_id' => $item->variant_id ?? 0,
                    'total_vendus' => $item->total_vendus ?? 0,
                    'total_ca' => $item->total_ca ?? 0,
                ];
            }),
            
            'ventesParCategorie' => $this->ventesParCategorie->map(function($item) {
                return (object)[
                    'nom' => $item->nom ?? '',
                    'couleur' => $item->couleur ?? 'gray',
                    'total_ca' => $item->total_ca ?? 0,
                ];
            }),
            
            'performanceCaissiers' => $this->performanceCaissiers->map(function($item) {
                return (object)[
                    'name' => $item->name ?? '',
                    'nombre_ventes' => $item->nombre_ventes ?? 0,
                    'total_ca' => $item->total_ca ?? 0,
                ];
            }),
            
            'evolutionVentes' => $this->evolutionVentes->map(function($item) {
                return (object)[
                    'date' => $item->date ?? now(),
                    'nombre' => $item->nombre ?? 0,
                    'total' => $item->total ?? 0,
                ];
            }),
        ];

        // Log pour vérifier
        \Log::info('PDF - Top Produits count: ' . $donnees['topProduits']->count());
        \Log::info('PDF - Catégories count: ' . $donnees['ventesParCategorie']->count());
        
        // Générer le PDF
        $pdf = Pdf::loadView('admin.rapports-pdf', $donnees)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);

        $nomFichier = 'rapport_ventes_' . 
            Carbon::parse($this->dateDebut)->format('Y-m-d') . '_' . 
            Carbon::parse($this->dateFin)->format('Y-m-d') . '.pdf';

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, $nomFichier);

    } catch (\Exception $e) {
        \Log::error('Erreur export PDF: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        session()->flash('error', 'Erreur lors de l\'export PDF : ' . $e->getMessage());
        return null;
    }
}

public function testerDonnees()
{
    $this->chargerDonnees();
    
    $info = [
        'dateDebut' => $this->dateDebut,
        'dateFin' => $this->dateFin,
        'nombreVentes' => $this->nombreVentes,
        'chiffreAffaires' => $this->chiffreAffaires,
        'topProduits_count' => $this->topProduits ? $this->topProduits->count() : 0,
        'topProduits_data' => $this->topProduits ? $this->topProduits->take(2)->toArray() : [],
        'categories_count' => $this->ventesParCategorie ? $this->ventesParCategorie->count() : 0,
        'caissiers_count' => $this->performanceCaissiers ? $this->performanceCaissiers->count() : 0,
        'evolution_count' => $this->evolutionVentes ? $this->evolutionVentes->count() : 0,
    ];
    
    \Log::info('=== TEST DONNÉES RAPPORTS ===', $info);
    
    session()->flash('success', 'Données testées ! Vérifiez storage/logs/laravel.log');
}

    /**
     * Exporter les rapports en Excel
     */
    public function exporterExcel()
    {
        try {
            $nomFichier = 'rapport_ventes_' . 
                Carbon::parse($this->dateDebut)->format('Y-m-d') . '_' . 
                Carbon::parse($this->dateFin)->format('Y-m-d') . '.xlsx';

            return Excel::download(
                new \App\Exports\RapportsExport(
                    $this->dateDebut,
                    $this->dateFin,
                    $this->chiffreAffaires,
                    $this->nombreVentes,
                    $this->panierMoyen,
                    $this->produitsVendus,
                    $this->topProduits,
                    $this->ventesParCategorie,
                    $this->performanceCaissiers,
                    $this->evolutionVentes
                ),
                $nomFichier
            );

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de l\'export Excel : ' . $e->getMessage());
        }
    }

    private function resetStats()
    {
        $this->chiffreAffaires = 0;
        $this->nombreVentes = 0;
        $this->panierMoyen = 0;
        $this->produitsVendus = 0;
        $this->evolutionCA = 0;
        $this->topProduits = collect();
        $this->ventesParCategorie = collect();
        $this->performanceCaissiers = collect();
        $this->evolutionVentes = collect();
    }

    public function render()
    {
        return view('livewire.admin.rapports')->layout('layouts.admin');
    }
}
