<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('objectifs', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('type')->default('journalier'); // 'journalier', 'mensuel'. Important pour le filtrage auto.
            $table->text('description')->nullable();
            $table->decimal('objectif', 15, 2); // Valeur à atteindre
            $table->decimal('actuel', 15, 2)->default(0); // Valeur actuelle
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['en_cours', 'atteint', 'annule'])->default('en_cours');
            $table->foreignId('cree_par')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            // Index pour optimiser les requêtes
           $table->index(['type', 'statut', 'date_fin']);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objectifs');
    }
};