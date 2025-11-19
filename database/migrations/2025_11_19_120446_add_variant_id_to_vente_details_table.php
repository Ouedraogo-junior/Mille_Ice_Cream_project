<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ÉTAPE 1 : On ajoute la colonne NULLABLE d'abord (SQLite accepte)
        Schema::table('vente_details', function (Blueprint $table) {
            $table->foreignId('variant_id')
                  ->nullable()
                  ->after('produit_id')
                  ->constrained('variants')
                  ->onDelete('cascade');
        });

        // ÉTAPE 2 : On remplit toutes les lignes avec la première variante du produit
        DB::statement("
            UPDATE vente_details 
            SET variant_id = (
                SELECT id 
                FROM variants 
                WHERE variants.produit_id = vente_details.produit_id 
                ORDER BY id ASC 
                LIMIT 1
            )
            WHERE variant_id IS NULL
        ");

        // ÉTAPE 3 : Maintenant qu’il n’y a plus de NULL → on passe en NOT NULL
        Schema::table('vente_details', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('vente_details', function (Blueprint $table) {
            $table->dropConstrainedForeignId('variant_id');
        });
    }
};