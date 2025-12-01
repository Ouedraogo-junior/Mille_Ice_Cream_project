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
    // app/Http/Controllers/TicketController.php
public function imprimerServeur(Vente $vente, Request $request)
{
    try {
        // Connexion USB (adapter le chemin selon ton OS)
        // Windows : "COM3", "LPT1", etc.
        // Linux : "/dev/usb/lp0", "/dev/ttyUSB0"
        $connector = new \Mike42\Escpos\PrintConnectors\WindowsPrintConnector("GA-E200");
        
        // Ou si tu utilises un serveur Linux/Unix :
        // $connector = new \Mike42\Escpos\PrintConnectors\FilePrintConnector("/dev/usb/lp0");
        
        $printer = new \Mike42\Escpos\Printer($connector);

        // Imprimer l'en-tête
        $printer->setJustification(\Mike42\Escpos\Printer::JUSTIFY_CENTER);
        $printer->setEmphasis(true);
        $printer->setTextSize(2, 2);
        $printer->text("GLACIER MILA\n");
        $printer->setTextSize(1, 1);
        $printer->setEmphasis(false);
        $printer->text("Ouagadougou, Burkina Faso\n");
        $printer->text("+226 63 84 09 09\n\n");

        // Ticket info
        $printer->setJustification(\Mike42\Escpos\Printer::JUSTIFY_LEFT);
        $printer->setEmphasis(true);
        $printer->text("Ticket: " . $vente->numero_ticket . "\n");
        $printer->setEmphasis(false);
        $printer->text("Date: " . $vente->date_vente->format('d/m/Y H:i') . "\n");
        $printer->text(str_repeat('-', 32) . "\n\n");

        // Articles
        foreach ($vente->details as $detail) {
            $printer->text($detail->produit->nom . "\n");
            $printer->text(sprintf(
                "  %d x %s F = %s F\n",
                $detail->quantite,
                number_format($detail->prix_unitaire, 0),
                number_format($detail->sous_total, 0)
            ));
        }

        // Total
        $printer->text("\n" . str_repeat('-', 32) . "\n");
        $printer->setTextSize(2, 2);
        $printer->setEmphasis(true);
        $printer->text("TOTAL: " . number_format($vente->total, 0) . " F\n");
        $printer->setTextSize(1, 1);
        $printer->setEmphasis(false);

        // Footer
        $printer->feed(2);
        $printer->setJustification(\Mike42\Escpos\Printer::JUSTIFY_CENTER);
        $printer->text("Merci de votre visite !\n");
        $printer->feed(3);
        $printer->cut();
        $printer->close();

        return response()->json([
            'success' => true,
            'message' => 'Ticket imprimé avec succès'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur : ' . $e->getMessage()
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