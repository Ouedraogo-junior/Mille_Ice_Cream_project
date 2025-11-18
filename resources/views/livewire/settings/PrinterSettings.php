<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class PrinterSettings extends Component
{
    // Paramètres d'impression
    public string $printerType = 'browser'; // browser, bluetooth, usb
    public bool $autoprint = false;
    public bool $printCopies = false;
    public int $copies = 1;
    public string $paperSize = '58mm'; // 58mm, 80mm
    
    // Paramètres entreprise
    public string $nomEntreprise;
    public string $adresseEntreprise;
    public string $telEntreprise;
    public string $emailEntreprise;
    public string $messageTicket;
    
    // Messages
    public ?string $messageSucces = null;
    public ?string $messageErreur = null;
    
    // Test
    public bool $showTestTicket = false;

    /**
     * Chargement des paramètres
     */
    public function mount()
    {
        // Charger les paramètres d'impression
        $this->printerType = Cache::get('printer.type', 'browser');
        $this->autoprint = Cache::get('printer.autoprint', false);
        $this->printCopies = Cache::get('printer.copies_enabled', false);
        $this->copies = Cache::get('printer.copies_count', 1);
        $this->paperSize = Cache::get('printer.paper_size', '58mm');
        
        // Charger les infos entreprise
        $this->nomEntreprise = config('app.nom_entreprise', '');
        $this->adresseEntreprise = config('app.adresse_entreprise', '');
        $this->telEntreprise = config('app.tel_entreprise', '');
        $this->emailEntreprise = config('app.email_entreprise', '');
        $this->messageTicket = config('app.message_ticket', '');
    }

    /**
     * Sauvegarder les paramètres d'impression
     */
    public function sauvegarderImpression()
    {
        // Validation
        $this->validate([
            'printerType' => 'required|in:browser,bluetooth,usb',
            'copies' => 'required|integer|min:1|max:10',
            'paperSize' => 'required|in:58mm,80mm',
        ]);

        try {
            // Sauvegarder en cache
            Cache::forever('printer.type', $this->printerType);
            Cache::forever('printer.autoprint', $this->autoprint);
            Cache::forever('printer.copies_enabled', $this->printCopies);
            Cache::forever('printer.copies_count', $this->copies);
            Cache::forever('printer.paper_size', $this->paperSize);

            $this->messageSucces = 'Paramètres d\'impression sauvegardés';
        } catch (\Exception $e) {
            $this->messageErreur = 'Erreur : ' . $e->getMessage();
        }
    }

    /**
     * Sauvegarder les infos entreprise
     * Note: Nécessite la modification manuelle du .env ou une table de config en BDD
     */
    public function sauvegarderEntreprise()
    {
        // Validation
        $this->validate([
            'nomEntreprise' => 'required|string|max:255',
            'adresseEntreprise' => 'required|string|max:500',
            'telEntreprise' => 'required|string|max:50',
            'emailEntreprise' => 'nullable|email|max:255',
            'messageTicket' => 'nullable|string|max:500',
        ], [
            'nomEntreprise.required' => 'Le nom de l\'entreprise est requis',
            'adresseEntreprise.required' => 'L\'adresse est requise',
            'telEntreprise.required' => 'Le téléphone est requis',
            'emailEntreprise.email' => 'Email invalide',
        ]);

        try {
            // Sauvegarder en cache (temporaire)
            // Pour une solution permanente, créer une table settings en BDD
            Cache::forever('entreprise.nom', $this->nomEntreprise);
            Cache::forever('entreprise.adresse', $this->adresseEntreprise);
            Cache::forever('entreprise.tel', $this->telEntreprise);
            Cache::forever('entreprise.email', $this->emailEntreprise);
            Cache::forever('entreprise.message', $this->messageTicket);

            $this->messageSucces = 'Informations de l\'entreprise sauvegardées. Note: Pour une sauvegarde permanente, modifiez le fichier .env';
        } catch (\Exception $e) {
            $this->messageErreur = 'Erreur : ' . $e->getMessage();
        }
    }

    /**
     * Tester l'impression
     */
    public function testerImpression()
    {
        $this->showTestTicket = true;
        $this->dispatch('tester-impression');
    }

    /**
     * Détecter l'imprimante
     */
    public function detecterImprimante()
    {
        $this->dispatch('detecter-imprimante');
    }

    /**
     * Effacer les messages
     */
    public function effacerMessages()
    {
        $this->messageSucces = null;
        $this->messageErreur = null;
    }

    /**
     * Render
     */
    public function render()
    {
        return view('livewire.settings.printer-settings');
    }
}