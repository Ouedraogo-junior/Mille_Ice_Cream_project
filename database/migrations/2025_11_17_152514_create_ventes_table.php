<?php
// 📁 database/migrations/xxxx_create_ventes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict'); // Caissier
            $table->decimal('total', 10, 2);
            $table->enum('mode_paiement', ['espece', 'mobile', 'carte'])->default('espece');
            $table->timestamp('date_vente');
            $table->string('numero_ticket', 50)->unique()->nullable(); // Généré automatiquement
            $table->text('note')->nullable(); // Commentaire optionnel
            $table->boolean('est_annulee')->default(false);
            $table->timestamp('annulee_le')->nullable();
            $table->foreignId('annulee_par')->nullable()->constrained('users');
            $table->text('raison_annulation')->nullable();
            $table->timestamps();

            // Index pour rapports
            $table->index(['user_id', 'date_vente']);
            $table->index('date_vente');
            $table->index('est_annulee');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};