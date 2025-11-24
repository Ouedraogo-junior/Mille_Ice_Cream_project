<?php
// 📁 database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\Variant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ========================================
        // UTILISATEURS
        // ========================================
        
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

        // ========================================
        // CATÉGORIES
        // ========================================
        
        $categories = [
            [
                'nom' => 'Glaces',
                'couleur' => '#3B82F6',
                'icone' => '🍦',
                'ordre' => 1,
            ],
            [
                'nom' => 'Cookies',
                'couleur' => '#F59E0B',
                'icone' => '🍪',
                'ordre' => 2,
            ],
            [
                'nom' => 'Bubble Waffle',
                'couleur' => '#EC4899',
                'icone' => '🧇',
                'ordre' => 3,
            ],
            [
                'nom' => 'Boissons',
                'couleur' => '#10B981',
                'icone' => '💧',
                'ordre' => 4,
            ],
        ];

        foreach ($categories as $cat) {
            Categorie::create($cat);
        }

        // ========================================
        // PRODUITS ET VARIANTES
        // ========================================

        // -------------------- CATÉGORIE 1 : GLACES --------------------
        
        // Glace de base (avec 3 formats)
        $glaceBase = Produit::create([
            'categorie_id' => 1,
            'nom' => 'Glace',
            'description' => 'Glace artisanale',
            'is_favori' => true,
            'ordre' => 1,
        ]);
        
        Variant::create(['produit_id' => $glaceBase->id, 'nom' => 'Petit format', 'prix' => 1000, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]);
        Variant::create(['produit_id' => $glaceBase->id, 'nom' => 'Moyen format', 'prix' => 2000, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]);
        Variant::create(['produit_id' => $glaceBase->id, 'nom' => 'Grand format', 'prix' => 2500, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]);

        // Glace avec toppings - Vermicelles
        $glaceVermicelles = Produit::create([
            'categorie_id' => 1,
            'nom' => 'Glace avec Vermicelles',
            'description' => 'Glace avec vermicelles colorés',
            'is_favori' => false,
            'ordre' => 2,
        ]);
        
        Variant::create(['produit_id' => $glaceVermicelles->id, 'nom' => 'Petit format', 'prix' => 1500, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]); // 1000 + 500
        Variant::create(['produit_id' => $glaceVermicelles->id, 'nom' => 'Moyen format', 'prix' => 2500, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]); // 2000 + 500
        Variant::create(['produit_id' => $glaceVermicelles->id, 'nom' => 'Grand format', 'prix' => 3000, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]); // 2500 + 500

        // Glace avec toppings - Coulis Chocolat
        $glaceCoulisChoco = Produit::create([
            'categorie_id' => 1,
            'nom' => 'Glace avec Coulis Chocolat',
            'description' => 'Glace nappée de coulis chocolat',
            'is_favori' => true,
            'ordre' => 3,
        ]);
        
        Variant::create(['produit_id' => $glaceCoulisChoco->id, 'nom' => 'Petit format', 'prix' => 1500, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]);
        Variant::create(['produit_id' => $glaceCoulisChoco->id, 'nom' => 'Moyen format', 'prix' => 2500, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]);
        Variant::create(['produit_id' => $glaceCoulisChoco->id, 'nom' => 'Grand format', 'prix' => 3000, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]);

        // Glace avec toppings - Fraise
        $glaceFraise = Produit::create([
            'categorie_id' => 1,
            'nom' => 'Glace avec Fraise',
            'description' => 'Glace avec coulis fraise',
            'is_favori' => false,
            'ordre' => 4,
        ]);
        
        Variant::create(['produit_id' => $glaceFraise->id, 'nom' => 'Petit format', 'prix' => 1500, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]);
        Variant::create(['produit_id' => $glaceFraise->id, 'nom' => 'Moyen format', 'prix' => 2500, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]);
        Variant::create(['produit_id' => $glaceFraise->id, 'nom' => 'Grand format', 'prix' => 3000, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]);

        // Autres glace (produit générique pour flexibilité)
        $autresGlace = Produit::create([
            'categorie_id' => 1,
            'nom' => 'Autres Glace',
            'description' => 'Autres variétés de glace',
            'is_favori' => false,
            'ordre' => 5,
        ]);
        
        Variant::create(['produit_id' => $autresGlace->id, 'nom' => 'Standard', 'prix' => 0, 'stock' => 0, 'seuil_alerte' => 10, 'active' => true]);

        // -------------------- CATÉGORIE 2 : COOKIES --------------------
        
        $cookies = [
            ['nom' => 'Cookies aux pépites de chocolat noir sucré', 'prix' => 700],
            ['nom' => 'Cookies Pistachio', 'prix' => 700],
            ['nom' => 'Cookies Red Velvet', 'prix' => 700],
            ['nom' => 'Cookies Tout Choco', 'prix' => 700],
            ['nom' => 'Cookies à la vanille et vermicelles', 'prix' => 700],
            ['nom' => 'Cookies au chocolat blanc', 'prix' => 700],
        ];

        $ordre = 1;
        foreach ($cookies as $cookie) {
            $prod = Produit::create([
                'categorie_id' => 2,
                'nom' => $cookie['nom'],
                'description' => 'Cookie artisanal',
                'is_favori' => $ordre <= 2, // Les 2 premiers en favoris
                'ordre' => $ordre++,
            ]);
            
            Variant::create([
                'produit_id' => $prod->id,
                'nom' => 'Unité',
                'prix' => $cookie['prix'],
                'stock' => 50,
                'seuil_alerte' => 10,
                'active' => true
            ]);
        }

        // Cookies en pack
        $cookiesPack4 = Produit::create([
            'categorie_id' => 2,
            'nom' => 'Cookies Pack de 4',
            'description' => 'Pack de 4 cookies au choix',
            'is_favori' => true,
            'ordre' => $ordre++,
        ]);
        Variant::create(['produit_id' => $cookiesPack4->id, 'nom' => 'Pack 4', 'prix' => 2800, 'stock' => 30, 'seuil_alerte' => 5, 'active' => true]);

        $cookiesPack6 = Produit::create([
            'categorie_id' => 2,
            'nom' => 'Cookies Pack de 6',
            'description' => 'Pack de 6 cookies au choix',
            'is_favori' => true,
            'ordre' => $ordre++,
        ]);
        Variant::create(['produit_id' => $cookiesPack6->id, 'nom' => 'Pack 6', 'prix' => 4200, 'stock' => 20, 'seuil_alerte' => 5, 'active' => true]);

        // Autres cookies
        $autresCookies = Produit::create([
            'categorie_id' => 2,
            'nom' => 'Autres Cookies',
            'description' => 'Autres variétés de cookies',
            'is_favori' => false,
            'ordre' => $ordre,
        ]);
        Variant::create(['produit_id' => $autresCookies->id, 'nom' => 'Standard', 'prix' => 0, 'stock' => 0, 'seuil_alerte' => 10, 'active' => true]);

        // -------------------- CATÉGORIE 3 : BUBBLE WAFFLE --------------------
        
        $bubbleSucre = Produit::create([
            'categorie_id' => 3,
            'nom' => 'Bubble Sucré',
            'description' => 'Bubble waffle nature sucré',
            'is_favori' => false,
            'ordre' => 1,
        ]);
        Variant::create(['produit_id' => $bubbleSucre->id, 'nom' => 'Standard', 'prix' => 1000, 'stock' => 50, 'seuil_alerte' => 10, 'active' => true]);

        $extraCoulis = Produit::create([
            'categorie_id' => 3,
            'nom' => 'Extra Coulis Vermicelles',
            'description' => 'Supplément coulis et vermicelles',
            'is_favori' => false,
            'ordre' => 2,
        ]);
        Variant::create(['produit_id' => $extraCoulis->id, 'nom' => 'Extra', 'prix' => 250, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]);

        $bubbleGlace = Produit::create([
            'categorie_id' => 3,
            'nom' => 'Bubble avec Glace',
            'description' => 'Bubble waffle garni de glace',
            'is_favori' => true,
            'ordre' => 3,
        ]);
        Variant::create(['produit_id' => $bubbleGlace->id, 'nom' => 'Standard', 'prix' => 3000, 'stock' => 40, 'seuil_alerte' => 10, 'active' => true]);

        $autreBubble = Produit::create([
            'categorie_id' => 3,
            'nom' => 'Autre Bubble',
            'description' => 'Autres variétés de bubble waffle',
            'is_favori' => false,
            'ordre' => 4,
        ]);
        Variant::create(['produit_id' => $autreBubble->id, 'nom' => 'Standard', 'prix' => 0, 'stock' => 0, 'seuil_alerte' => 10, 'active' => true]);

        $cornet1 = Produit::create([
            'categorie_id' => 3,
            'nom' => 'Cornet 1',
            'description' => 'Cornet simple',
            'is_favori' => false,
            'ordre' => 5,
        ]);
        Variant::create(['produit_id' => $cornet1->id, 'nom' => 'Standard', 'prix' => 200, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]);

        $cornet2 = Produit::create([
            'categorie_id' => 3,
            'nom' => 'Cornet 2',
            'description' => 'Cornet premium',
            'is_favori' => false,
            'ordre' => 6,
        ]);
        Variant::create(['produit_id' => $cornet2->id, 'nom' => 'Standard', 'prix' => 300, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]);

        $milkshake = Produit::create([
            'categorie_id' => 3,
            'nom' => 'Milkshake',
            'description' => 'Milkshake onctueux',
            'is_favori' => true,
            'ordre' => 7,
        ]);
        Variant::create(['produit_id' => $milkshake->id, 'nom' => 'Standard', 'prix' => 2500, 'stock' => 30, 'seuil_alerte' => 10, 'active' => true]);

        // -------------------- CATÉGORIE 4 : BOISSONS --------------------
        
        $eauLafi15 = Produit::create([
            'categorie_id' => 4,
            'nom' => 'Eau Lafi 1,5L',
            'description' => 'Eau minérale Lafi 1,5 litre',
            'is_favori' => false,
            'ordre' => 1,
        ]);
        Variant::create(['produit_id' => $eauLafi15->id, 'nom' => '1,5L', 'prix' => 600, 'stock' => 100, 'seuil_alerte' => 20, 'active' => true]);

        $eauLafi05 = Produit::create([
            'categorie_id' => 4,
            'nom' => 'Eau Lafi 0,5L',
            'description' => 'Eau minérale Lafi 0,5 litre',
            'is_favori' => true,
            'ordre' => 2,
        ]);
        Variant::create(['produit_id' => $eauLafi05->id, 'nom' => '0,5L', 'prix' => 300, 'stock' => 150, 'seuil_alerte' => 30, 'active' => true]);

        // ========================================
        // MESSAGES DE CONFIRMATION
        // ========================================
        
        $this->command->info('✅ Base de données initialisée avec succès !');
        $this->command->info('');
        $this->command->info('👥 UTILISATEURS :');
        $this->command->info('📧 Admin : admin@glacier.bf / password');
        $this->command->info('📧 Caissier 1 : amina@gmail.com / password');
        $this->command->info('📧 Caissier 2 : moussa@glacier.bf / password');
        $this->command->info('');
        $this->command->info('🏪 PRODUITS CRÉÉS :');
        $this->command->info('🍦 Glaces : 5 produits avec variantes');
        $this->command->info('🍪 Cookies : 9 produits');
        $this->command->info('🧇 Bubble Waffle : 7 produits');
        $this->command->info('💧 Boissons : 2 produits');
    }
}