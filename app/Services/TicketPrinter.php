<?php

namespace App\Services;

use App\Models\Vente;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketPrinter
{
    /**
     * Générer un ticket PDF
     */
    public function genererPDF(Vente $vente): \Illuminate\Http\Response
    {
        $vente->load(['details.produit', 'caissier']);

        $pdf = Pdf::loadView('tickets.ticket-pdf', [
            'vente' => $vente,
            'entreprise' => $this->getInfosEntreprise(),
        ]);

        // Configuration pour ticket 58mm (environ 220px)
        $pdf->setPaper([0, 0, 220, 500], 'portrait');

        return $pdf->stream("ticket-{$vente->numero_ticket}.pdf");
    }

    /**
     * Générer HTML pour impression thermique
     */
    public function genererHTML(Vente $vente): string
    {
        $vente->load(['details.produit', 'caissier']);

        return view('tickets.ticket-thermique', [
            'vente' => $vente,
            'entreprise' => $this->getInfosEntreprise(),
        ])->render();
    }

    /**
     * Générer les données JSON pour impression JavaScript
     */
    public function genererDonneesJSON(Vente $vente): array
    {
        $vente->load(['details.produit', 'caissier']);

        return [
            'numero_ticket' => $vente->numero_ticket,
            'date' => $vente->date_vente->format('d/m/Y H:i:s'),
            'caissier' => $vente->caissier->name,
            'articles' => $vente->details->map(function ($detail) {
                return [
                    'nom' => $detail->produit->nom,
                    'quantite' => $detail->quantite,
                    'prix_unitaire' => $detail->prix_unitaire,
                    'sous_total' => $detail->sous_total,
                ];
            })->toArray(),
            'total' => $vente->total,
            'mode_paiement' => $vente->mode_paiement_libelle,
            'entreprise' => $this->getInfosEntreprise(),
        ];
    }

    /**
     * Générer commandes ESC/POS pour imprimante thermique
     * (Nécessite mike42/escpos-php)
     */
    public function genererESCPOS(Vente $vente): string
    {
        // Cette méthode nécessite la librairie escpos-php
        // composer require mike42/escpos-php
        
        $vente->load(['details.produit', 'caissier']);
        $entreprise = $this->getInfosEntreprise();

        try {
            // Créer un connecteur vers l'imprimante
            // Pour USB (recommandé pour test local)
            //$connector = new \Mike42\Escpos\PrintConnectors\FilePrintConnector("/dev/usb/lp0");
            // Ou sur Windows
            //$connector = new \Mike42\Escpos\PrintConnectors\WindowsPrintConnector("POS-80");

            // Pour Ethernet/Réseau (si imprimante connectée en IP)
            $connector = new \Mike42\Escpos\PrintConnectors\NetworkPrintConnector("192.168.123.100", 9100);

            // Pour Serial
            //$connector = new \Mike42\Escpos\PrintConnectors\SerialPrintConnector("/dev/ttyUSB0");
            $printer = new \Mike42\Escpos\Printer($connector);

            // Header
            $printer->setJustification(\Mike42\Escpos\Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->setTextSize(2, 2);
            $printer->text($entreprise['nom'] . "\n");
            $printer->setTextSize(1, 1);
            $printer->setEmphasis(false);
            $printer->text($entreprise['adresse'] . "\n");
            $printer->text($entreprise['telephone'] . "\n");
            $printer->feed(1);

            // Ligne de séparation
            $printer->text(str_repeat('-', 32) . "\n");

            // Numéro de ticket et date
            $printer->setJustification(\Mike42\Escpos\Printer::JUSTIFY_LEFT);
            $printer->setEmphasis(true);
            $printer->text("Ticket: " . $vente->numero_ticket . "\n");
            $printer->setEmphasis(false);
            $printer->text("Date: " . $vente->date_vente->format('d/m/Y H:i') . "\n");
            $printer->text("Caissier: " . $vente->caissier->name . "\n");
            $printer->text(str_repeat('-', 32) . "\n");

            // Articles
            foreach ($vente->details as $detail) {
                $printer->setEmphasis(true);
                $printer->text($detail->produit->nom . "\n");
                $printer->setEmphasis(false);
                
                $ligne = sprintf(
                    "  %d x %s = %s\n",
                    $detail->quantite,
                    number_format($detail->prix_unitaire, 0, ',', ' ') . ' F',
                    number_format($detail->sous_total, 0, ',', ' ') . ' F'
                );
                $printer->text($ligne);
            }

            // Total
            $printer->text(str_repeat('-', 32) . "\n");
            $printer->setTextSize(2, 2);
            $printer->setEmphasis(true);
            $printer->setJustification(\Mike42\Escpos\Printer::JUSTIFY_RIGHT);
            $printer->text("TOTAL: " . number_format($vente->total, 0, ',', ' ') . " F\n");
            $printer->setTextSize(1, 1);
            $printer->setEmphasis(false);
            $printer->setJustification(\Mike42\Escpos\Printer::JUSTIFY_LEFT);

            // Mode de paiement
            $printer->text("\n");
            $printer->text("Paiement: " . $vente->mode_paiement_libelle . "\n");

            // Footer
            $printer->feed(1);
            $printer->setJustification(\Mike42\Escpos\Printer::JUSTIFY_CENTER);
            $printer->text($entreprise['message_footer'] . "\n");
            $printer->text("Merci de votre visite !\n");
            
            // Couper le papier
            $printer->feed(2);
            $printer->cut();

            // Récupérer les données
            $data = $connector->getData();
            $printer->close();

            return base64_encode($data);

        } catch (\Exception $e) {
            \Log::error('Erreur génération ESC/POS', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Obtenir les informations de l'entreprise
     * (à stocker en config ou en BDD)
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

    /**
     * Vérifier si une imprimante est connectée
     */
    public function verifierImprimante(): bool
    {
        // TODO: Implémenter la détection d'imprimante
        // Peut utiliser l'API du navigateur ou une vérification serveur
        return true;
    }

    /**
     * Obtenir la liste des imprimantes disponibles
     */
    public function getImprimantesDisponibles(): array
    {
        // TODO: Lister les imprimantes
        // Nécessite une intégration spécifique selon l'OS
        return [];
    }
}