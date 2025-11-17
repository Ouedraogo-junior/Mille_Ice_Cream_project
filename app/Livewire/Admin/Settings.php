<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Settings extends Component
{
    use WithFileUploads;

    public $section = 'profil';

    // Entreprise
    public $logoPreview;
    public $logoActuel;
    public $nomEntreprise = 'Mille Ice Cream';
    public $adresse = '';
    public $telephone = '';

    // Notifications
    public $notifStockFaible = true;
    public $notifVentes = false;
    public $notifRapport = true;

    public function mount()
    {
        // Charger les paramètres de l'entreprise
        $this->loadSettings();
    }

    public function loadSettings()
    {
        try {
            // Charger les paramètres de l'entreprise
            $this->nomEntreprise = Setting::get('nom_entreprise', 'Mille Ice Cream');
            $this->adresse = Setting::get('adresse', '');
            $this->telephone = Setting::get('telephone', '');
            $this->logoActuel = Setting::get('logo', null);

            // Charger les notifications (convertir en booléen)
            $this->notifStockFaible = (bool) Setting::get('notif_stock_faible', true);
            $this->notifVentes = (bool) Setting::get('notif_ventes', false);
            $this->notifRapport = (bool) Setting::get('notif_rapport', true);
        } catch (\Exception $e) {
            // Si la table n'existe pas encore, ignorer l'erreur
            \Log::warning('Erreur lors du chargement des paramètres: ' . $e->getMessage());
        }
    }

    public function sauvegarderEntreprise()
    {
        // Validation
        $this->validate([
            'nomEntreprise' => 'required|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'logoPreview' => 'nullable|image|max:2048',
        ], [
            'nomEntreprise.required' => 'Le nom de l\'entreprise est obligatoire',
            'logoPreview.image' => 'Le fichier doit être une image',
            'logoPreview.max' => 'L\'image ne doit pas dépasser 2 Mo',
        ]);

        try {
            // Sauvegarder le logo si présent
            if ($this->logoPreview) {
                // Supprimer l'ancien logo s'il existe
                if ($this->logoActuel) {
                    Storage::disk('public')->delete($this->logoActuel);
                }
                
                $logoPath = $this->logoPreview->store('logos', 'public');
                Setting::set('logo', $logoPath);
                $this->logoActuel = $logoPath;
                $this->logoPreview = null;
            }

            // Sauvegarder les autres informations
            Setting::set('nom_entreprise', $this->nomEntreprise);
            Setting::set('adresse', $this->adresse);
            Setting::set('telephone', $this->telephone);

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Informations de l\'entreprise mises à jour !'
            ]);
            
            // Rafraîchir la page pour voir le nouveau logo dans le sidebar
            $this->dispatch('refreshPage');
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Erreur lors de la sauvegarde : ' . $e->getMessage()
            ]);
        }
    }

    public function updated($propertyName)
    {
        // Sauvegarder automatiquement les notifications
        if (str_starts_with($propertyName, 'notif')) {
            try {
                // Convertir le booléen en entier pour le stockage
                $value = $this->$propertyName ? '1' : '0';
                Setting::set($propertyName, $value);
                
                $this->dispatch('toast', [
                    'type' => 'success',
                    'message' => 'Préférence enregistrée !'
                ]);
            } catch (\Exception $e) {
                $this->dispatch('toast', [
                    'type' => 'error',
                    'message' => 'Erreur lors de la sauvegarde'
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.settings')->layout('layouts.admin');
    }
}