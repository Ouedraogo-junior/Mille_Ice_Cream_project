<?php
// 📁 database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer un admin
        User::create([
            'name' => 'Admin Glacier',
            'email' => 'admin@glacier.bf',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Créer quelques caissiers
        User::create([
            'name' => 'Amina Ouédraogo',
            'email' => 'amina@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'caissier',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Moussa Traoré',
            'email' => 'moussa@glacier.bf',
            'password' => Hash::make('password'),
            'role' => 'caissier',
            'is_active' => true,
        ]);

        // Créer les catégories
        $categories = [
            [
                'nom' => 'Glaces',
                'couleur' => '#3B82F6',
                'icone' => '🍦',
                'ordre' => 1,
            ],
            [
                'nom' => 'Sorbets',
                'couleur' => '#10B981',
                'icone' => '🍧',
                'ordre' => 2,
            ],
            [
                'nom' => 'Milkshakes',
                'couleur' => '#F59E0B',
                'icone' => '🥤',
                'ordre' => 3,
            ],
            [
                'nom' => 'Toppings',
                'couleur' => '#EC4899',
                'icone' => '🍬',
                'ordre' => 4,
            ],
            [
                'nom' => 'Boissons',
                'couleur' => '#6366F1',
                'icone' => '🥤',
                'ordre' => 5,
            ],
        ];

        foreach ($categories as $cat) {
            Categorie::create($cat);
        }

        // Créer des produits
        $produits = [
            // Glaces
            [
                'categorie_id' => 1,
                'nom' => 'Vanille Classique',
                'description' => 'Glace vanille onctueuse',
                'prix' => 500,
                'stock' => 50,
                'is_favori' => true,
                'ordre' => 1,
            ],
            [
                'categorie_id' => 1,
                'nom' => 'Chocolat Intense',
                'description' => 'Glace au chocolat noir',
                'prix' => 600,
                'stock' => 45,
                'is_favori' => true,
                'ordre' => 2,
            ],
            [
                'categorie_id' => 1,
                'nom' => 'Fraise',
                'description' => 'Glace à la fraise',
                'prix' => 550,
                'stock' => 40,
                'ordre' => 3,
            ],
            [
                'categorie_id' => 1,
                'nom' => 'Pistache',
                'description' => 'Glace à la pistache',
                'prix' => 700,
                'stock' => 30,
                'ordre' => 4,
            ],
            [
                'categorie_id' => 1,
                'nom' => 'Caramel Beurre Salé',
                'description' => 'Glace caramel avec pointe de sel',
                'prix' => 650,
                'stock' => 35,
                'is_favori' => true,
                'ordre' => 5,
            ],
            [
                'categorie_id' => 1,
                'nom' => 'Menthe Chocolat',
                'description' => 'Glace menthe avec pépites de chocolat',
                'prix' => 600,
                'stock' => 25,
                'ordre' => 6,
            ],

            // Sorbets
            [
                'categorie_id' => 2,
                'nom' => 'Mangue',
                'description' => 'Sorbet mangue fraîche',
                'prix' => 500,
                'stock' => 40,
                'is_favori' => true,
                'ordre' => 1,
            ],
            [
                'categorie_id' => 2,
                'nom' => 'Citron',
                'description' => 'Sorbet citron acidulé',
                'prix' => 450,
                'stock' => 35,
                'ordre' => 2,
            ],
            [
                'categorie_id' => 2,
                'nom' => 'Passion',
                'description' => 'Sorbet fruit de la passion',
                'prix' => 550,
                'stock' => 30,
                'ordre' => 3,
            ],
            [
                'categorie_id' => 2,
                'nom' => 'Framboise',
                'description' => 'Sorbet framboise intense',
                'prix' => 600,
                'stock' => 25,
                'ordre' => 4,
            ],

            // Milkshakes
            [
                'categorie_id' => 3,
                'nom' => 'Milkshake Vanille',
                'description' => 'Milkshake vanille onctueux',
                'prix' => 1000,
                'stock' => 20,
                'ordre' => 1,
            ],
            [
                'categorie_id' => 3,
                'nom' => 'Milkshake Chocolat',
                'description' => 'Milkshake chocolat gourmand',
                'prix' => 1000,
                'stock' => 20,
                'is_favori' => true,
                'ordre' => 2,
            ],
            [
                'categorie_id' => 3,
                'nom' => 'Milkshake Fraise',
                'description' => 'Milkshake fraise délicieux',
                'prix' => 1000,
                'stock' => 20,
                'ordre' => 3,
            ],
            [
                'categorie_id' => 3,
                'nom' => 'Milkshake Banane',
                'description' => 'Milkshake banane crémeux',
                'prix' => 1000,
                'stock' => 15,
                'ordre' => 4,
            ],

            // Toppings
            [
                'categorie_id' => 4,
                'nom' => 'Coulis Chocolat',
                'description' => 'Nappage chocolat',
                'prix' => 200,
                'stock' => 100,
                'ordre' => 1,
            ],
            [
                'categorie_id' => 4,
                'nom' => 'Coulis Caramel',
                'description' => 'Nappage caramel',
                'prix' => 200,
                'stock' => 100,
                'ordre' => 2,
            ],
            [
                'categorie_id' => 4,
                'nom' => 'Chantilly',
                'description' => 'Crème chantilly',
                'prix' => 150,
                'stock' => 80,
                'ordre' => 3,
            ],
            [
                'categorie_id' => 4,
                'nom' => 'Vermicelles Chocolat',
                'description' => 'Vermicelles en chocolat',
                'prix' => 100,
                'stock' => 150,
                'ordre' => 4,
            ],
            [
                'categorie_id' => 4,
                'nom' => 'Noix Concassées',
                'description' => 'Mélange de noix',
                'prix' => 250,
                'stock' => 60,
                'ordre' => 5,
            ],
            [
                'categorie_id' => 4,
                'nom' => 'Smarties',
                'description' => 'Bonbons colorés',
                'prix' => 150,
                'stock' => 120,
                'ordre' => 6,
            ],

            // Boissons
            [
                'categorie_id' => 5,
                'nom' => 'Eau Minérale 50cl',
                'description' => 'Eau minérale fraîche',
                'prix' => 300,
                'stock' => 100,
                'ordre' => 1,
            ],
            [
                'categorie_id' => 5,
                'nom' => 'Coca-Cola 33cl',
                'description' => 'Coca-Cola canette',
                'prix' => 500,
                'stock' => 80,
                'is_favori' => true,
                'ordre' => 2,
            ],
            [
                'categorie_id' => 5,
                'nom' => 'Jus Orange',
                'description' => 'Jus d\'orange 100% pur',
                'prix' => 600,
                'stock' => 50,
                'ordre' => 3,
            ],
            [
                'categorie_id' => 5,
                'nom' => 'Thé Glacé',
                'description' => 'Thé glacé pêche',
                'prix' => 500,
                'stock' => 60,
                'ordre' => 4,
            ],
        ];

        foreach ($produits as $prod) {
            Produit::create($prod);
        }

        $this->command->info('✅ Base de données initialisée avec succès !');
        $this->command->info('📧 Admin : admin@glacier.bf / password');
        $this->command->info('📧 Caissier 1 : amina@gmail.com / password');
        $this->command->info('📧 Caissier 2 : moussa@glacier.bf / password');
    }
}