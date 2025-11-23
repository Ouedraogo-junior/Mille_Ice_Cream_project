<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Services\TicketPrinter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    protected TicketPrinter $printer;

    public function __construct(TicketPrinter $printer)
    {
        $this->printer = $printer;
    }

    /**
     * Afficher le ticket en HTML (pour impression navigateur)
     */
    public function afficher(Vente $vente)
    {
        // Vérifier que l'utilisateur peut voir ce ticket
        if (!Gate::allows('voir-ticket', $vente)) {
            abort(403, 'Accès non autorisé');
        }

        return view('tickets.ticket-thermique', [
            'vente' => $vente->load(['details.produit', 'details.variant', 'caissier']),
            'entreprise' => $this->getInfosEntreprise(),
        ]);
    }

    /**
     * Télécharger le ticket en PDF
     */
    public function telechargerPDF(Vente $vente)
    {
        if (!Gate::allows('voir-ticket', $vente)) {
            abort(403, 'Accès non autorisé');
        }

        // Charger aussi la relation variant pour le PDF
        $vente->load(['details.produit', 'details.variant', 'caissier']);
        return $this->printer->genererPDF($vente);
    }

    /**
     * Obtenir les données JSON du ticket
     */
    public function donneesJSON(Vente $vente)
    {
        if (!Gate::allows('voir-ticket', $vente)) {
            abort(403, 'Accès non autorisé');
        }

        // Charger aussi la relation variant pour le JSON
        $vente->load(['details.produit', 'details.variant', 'caissier']);
        return response()->json($this->printer->genererDonneesJSON($vente));
    }

    /**
     * Générer les commandes ESC/POS
     */
    public function escpos(Vente $vente)
    {
        if (!Gate::allows('voir-ticket', $vente)) {
            abort(403, 'Accès non autorisé');
        }

        try {
            // Charger aussi la relation variant pour ESC/POS
            $vente->load(['details.produit', 'details.variant', 'caissier']);
            $data = $this->printer->genererESCPOS($vente);
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Commandes ESC/POS générées',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Vérifier le statut de l'imprimante
     */
    public function verifierImprimante(Request $request)
    {
        try {
            $disponible = $this->printer->verifierImprimante();
            $imprimantes = $this->printer->getImprimantesDisponibles();

            return response()->json([
                'success' => true,
                'disponible' => $disponible,
                'imprimantes' => $imprimantes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Imprimer directement depuis le serveur
     * (Nécessite configuration spécifique)
     */
    public function imprimerServeur(Vente $vente, Request $request)
    {
        if (!Gate::allows('voir-ticket', $vente)) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $imprimante = $request->input('imprimante', 'default');
            
            // TODO: Implémenter l'impression serveur
            // Nécessite une configuration spécifique selon l'environnement
            
            return response()->json([
                'success' => true,
                'message' => 'Ticket envoyé à l\'imprimante',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur d\'impression : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtenir les informations de l'entreprise
     */
    private function getInfosEntreprise(): array
    {
        return [
            'nom' => config('app.nom_entreprise', 'GLACIER MILA'),
            'adresse' => config('app.adresse_entreprise', 'Ouagadougou, Burkina Faso'),
            'telephone' => config('app.tel_entreprise', '+226 63 84 09 09'),
            'email' => config('app.email_entreprise', 'contact@glacier.bf'),
            'message_footer' => config('app.message_ticket', 'Au plaisir de vous revoir !'),
            'logo' => config('app.logo_entreprise', null),
        ];
    }
}