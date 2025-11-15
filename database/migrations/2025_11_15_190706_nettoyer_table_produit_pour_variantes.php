<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('produit', function (Blueprint $table) {
            // On supprime les colonnes qui n'existent plus dans le nouveau modèle
            $table->dropColumn(['prix', 'stock', 'seuil_alerte']);
        });
    }

    public function down()
    {
        Schema::table('produit', function (Blueprint $table) {
            // On les remet au cas où (rollback)
            $table->decimal('prix', 10, 0)->after('description')->nullable();
            $table->integer('stock')->default(0)->after('prix');
            $table->integer('seuil_alerte')->default(10)->after('stock');
        });
    }
};