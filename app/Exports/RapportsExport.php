<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class RapportsExport implements WithMultipleSheets
{
    protected $dateDebut;
    protected $dateFin;
    protected $chiffreAffaires;
    protected $nombreVentes;
    protected $panierMoyen;
    protected $produitsVendus;
    protected $topProduits;
    protected $ventesParCategorie;
    protected $performanceCaissiers;
    protected $evolutionVentes;

    public function __construct(
        $dateDebut,
        $dateFin,
        $chiffreAffaires,
        $nombreVentes,
        $panierMoyen,
        $produitsVendus,
        $topProduits,
        $ventesParCategorie,
        $performanceCaissiers,
        $evolutionVentes
    ) {
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->chiffreAffaires = $chiffreAffaires;
        $this->nombreVentes = $nombreVentes;
        $this->panierMoyen = $panierMoyen;
        $this->produitsVendus = $produitsVendus;
        $this->topProduits = $topProduits;
        $this->ventesParCategorie = $ventesParCategorie;
        $this->performanceCaissiers = $performanceCaissiers;
        $this->evolutionVentes = $evolutionVentes;
    }

    public function sheets(): array
    {
        return [
            new ResumSheet(
                $this->dateDebut,
                $this->dateFin,
                $this->chiffreAffaires,
                $this->nombreVentes,
                $this->panierMoyen,
                $this->produitsVendus
            ),
            new TopProduitsSheet($this->topProduits),
            new CategoriesSheet($this->ventesParCategorie),
            new CaissiersSheet($this->performanceCaissiers),
            new EvolutionSheet($this->evolutionVentes),
        ];
    }
}

// Feuille Résumé
class ResumSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $dateDebut;
    protected $dateFin;
    protected $chiffreAffaires;
    protected $nombreVentes;
    protected $panierMoyen;
    protected $produitsVendus;

    public function __construct($dateDebut, $dateFin, $chiffreAffaires, $nombreVentes, $panierMoyen, $produitsVendus)
    {
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->chiffreAffaires = $chiffreAffaires;
        $this->nombreVentes = $nombreVentes;
        $this->panierMoyen = $panierMoyen;
        $this->produitsVendus = $produitsVendus;
    }

    public function collection()
    {
        return collect([
            ['Période du', Carbon::parse($this->dateDebut)->format('d/m/Y') . ' au ' . Carbon::parse($this->dateFin)->format('d/m/Y')],
            ['', ''],
            ['Chiffre d\'affaires', number_format($this->chiffreAffaires, 0, ',', ' ') . ' F'],
            ['Nombre de ventes', number_format($this->nombreVentes)],
            ['Panier moyen', number_format($this->panierMoyen, 0, ',', ' ') . ' F'],
            ['Produits vendus', number_format($this->produitsVendus) . ' unités'],
        ]);
    }

    public function headings(): array
    {
        return ['Indicateur', 'Valeur'];
    }

    public function title(): string
    {
        return 'Résumé';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0891B2']],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
            ],
            'A' => ['font' => ['bold' => true]],
        ];
    }
}

// Feuille Top Produits
class TopProduitsSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $topProduits;

    public function __construct($topProduits)
    {
        $this->topProduits = $topProduits;
    }

    public function collection()
    {
        return $this->topProduits->map(function($item, $index) {
            return [
                $index + 1,
                $item->produit_nom,
                $item->variant_nom && $item->variant_nom !== $item->produit_nom ? $item->variant_nom : '-',
                $item->total_vendus,
                number_format($item->total_ca, 0, ',', ' ') . ' F',
            ];
        });
    }

    public function headings(): array
    {
        return ['#', 'Produit', 'Variante', 'Quantité', 'CA Total'];
    }

    public function title(): string
    {
        return 'Top Produits';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '10B981']],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }
}

// Feuille Catégories
class CategoriesSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $ventesParCategorie;

    public function __construct($ventesParCategorie)
    {
        $this->ventesParCategorie = $ventesParCategorie;
    }

    public function collection()
    {
        return $this->ventesParCategorie->map(function($item) {
            return [
                $item->nom,
                number_format($item->total_ca, 0, ',', ' ') . ' F',
            ];
        });
    }

    public function headings(): array
    {
        return ['Catégorie', 'CA Total'];
    }

    public function title(): string
    {
        return 'Ventes par Catégorie';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'A855F7']],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }
}

// Feuille Caissiers
class CaissiersSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $performanceCaissiers;

    public function __construct($performanceCaissiers)
    {
        $this->performanceCaissiers = $performanceCaissiers;
    }

    public function collection()
    {
        return $this->performanceCaissiers->map(function($item) {
            return [
                $item->name,
                $item->nombre_ventes,
                number_format($item->total_ca, 0, ',', ' ') . ' F',
            ];
        });
    }

    public function headings(): array
    {
        return ['Caissier', 'Nb Ventes', 'CA Total'];
    }

    public function title(): string
    {
        return 'Performance Caissiers';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6366F1']],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }
}

// Feuille Évolution
class EvolutionSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $evolutionVentes;

    public function __construct($evolutionVentes)
    {
        $this->evolutionVentes = $evolutionVentes;
    }

    public function collection()
    {
        return $this->evolutionVentes->map(function($item) {
            return [
                Carbon::parse($item->date)->format('d/m/Y'),
                $item->nombre,
                number_format($item->total, 0, ',', ' ') . ' F',
            ];
        });
    }

    public function headings(): array
    {
        return ['Date', 'Nb Ventes', 'CA Total'];
    }

    public function title(): string
    {
        return 'Évolution Ventes';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '06B6D4']],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }
}