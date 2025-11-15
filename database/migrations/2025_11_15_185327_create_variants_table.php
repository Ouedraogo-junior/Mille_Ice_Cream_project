<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produit')->onDelete('cascade');
            $table->string('nom'); // ex: Petit, Moyen, Grand, Pack de 4, Pack de 6
            $table->decimal('prix', 10, 0);
            $table->integer('stock')->default(999);
            $table->integer('seuil_alerte')->default(10);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
