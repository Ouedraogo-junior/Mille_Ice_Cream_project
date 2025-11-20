<?php
// app/Http/Controllers/Api/OfflineController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Vente;
use App\Models\VenteDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OfflineController extends Controller
{
    /**
     * Récupérer tous les produits pour le mode offline
     */
    public function produits()
    {
        try {
            $produits = Produit::with(['variants' => function($q) {
                $q->where('active', true);
            }, 'categorie'])
            ->whereHas('variants', function($q) {
                $q->where('active', true);
            })
            ->get()
            ->map(function($produit) {
                return [
                    'id' => $produit->id,
                    'nom' => $produit->nom,
                    'description' => $produit->description,
                    'image' => $produit->image,
                    'categorie_id' => $produit->categorie_id,
                    'categorie_nom' => $produit->categorie->nom ?? null,
                    'variants' => $produit->variants->map(function($v) {
                        return [
                            'id' => $v->id,
                            'nom' => $v->nom,
                            'prix' => $v->prix,
                            'stock' => $v->stock,
                            'active' => $v->active,
                        ];
                    }),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $produits,
                'count' => $produits->count(),
                'synced_at' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des produits',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Récupérer toutes les catégories
     */
    public function categories()
    {
        try {
            $categories = Categorie::where('active', true)
                ->orderBy('ordre')
                ->get()
                ->map(function($cat) {
                    return [
                        'id' => $cat->id,
                        'nom' => $cat->nom,
                        'couleur' => $cat->couleur,
                        'icone' => $cat->icone,
                        'ordre' => $cat->ordre,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $categories,
                'count' => $categories->count(),
                'synced_at' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des catégories',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Récupérer la configuration
     */
    public function config()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'nom_entreprise' => config('app.nom_entreprise', 'GLACIER MILA'),
                'adresse_entreprise' => config('app.adresse_entreprise'),
                'telephone_entreprise' => config('app.tel_entreprise'),
                'modes_paiement' => ['espece', 'mobile', 'carte'],
                'objectif_journalier' => config('pos.objectif_journalier', 50000),
            ],
        ]);
    }

    /**
     * Synchroniser une vente enregistrée offline
     */
    public function syncVente(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'panier' => 'required|array',
            'panier.*.id' => 'required|exists:produit,id',
            'panier.*.variant_id' => 'required|exists:variants,id',
            'panier.*.quantite' => 'required|integer|min:1',
            'panier.*.prix' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'mode_paiement' => 'required|in:espece,mobile,carte',
            'montant' => 'nullable|numeric',
            'monnaie_rendue' => 'nullable|numeric',
            'timestamp' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Créer la vente
            $vente = Vente::create([
                'user_id' => auth()->id(),
                'total' => $request->total,
                'mode_paiement' => $request->mode_paiement,
                'montant' => $request->montant,
                'monnaie_rendue' => $request->monnaie_rendue,
                'date_vente' => now(),
            ]);

            // Créer les détails
            foreach ($request->panier as $item) {
                VenteDetail::create([
                    'vente_id' => $vente->id,
                    'produit_id' => $item['id'],
                    'variant_id' => $item['variant_id'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix'],
                    'sous_total' => $item['prix'] * $item['quantite'],
                ]);

                // Décrémenter le stock
                $variant = \App\Models\Variant::find($item['variant_id']);
                if ($variant && $variant->stock !== null) {
                    $variant->decrement('stock', $item['quantite']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vente synchronisée avec succès',
                'vente_id' => $vente->id,
                'numero_ticket' => $vente->numero_ticket,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur sync vente offline', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la synchronisation',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Vérifier les stocks actuels
     */
    public function checkStocks(Request $request)
    {
        $variantIds = $request->input('variant_ids', []);
        
        $stocks = \App\Models\Variant::whereIn('id', $variantIds)
            ->get()
            ->mapWithKeys(function($variant) {
                return [$variant->id => $variant->stock];
            });

        return response()->json([
            'success' => true,
            'stocks' => $stocks,
        ]);
    }

    /**
     * Obtenir le statut de synchronisation
     */
    public function syncStatus()
    {
        $user = auth()->user();
        
        // Dernière vente synchronisée
        $derniereVente = Vente::where('user_id', $user->id)
            ->latest('created_at')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'derniere_vente' => $derniereVente ? $derniereVente->created_at->toIso8601String() : null,
                'ventes_aujourd_hui' => Vente::where('user_id', $user->id)
                    ->whereDate('date_vente', today())
                    ->count(),
                'serveur_disponible' => true,
            ],
        ]);
    }
}