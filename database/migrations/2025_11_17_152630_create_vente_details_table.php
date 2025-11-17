<?php
// 📁 database/migrations/xxxx_create_vente_details_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vente_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vente_id')->constrained()->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produit')->onDelete('restrict');
            $table->integer('quantite');
            $table->decimal('prix_unitaire', 10, 2); // Prix au moment de la vente
            $table->decimal('sous_total', 10, 2); // prix_unitaire * quantite
            $table->timestamps();

            // Index pour statistiques
            $table->index(['vente_id', 'produit_id']);
            $table->index('produit_id'); // Pour top produits
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vente_details');
    }
};