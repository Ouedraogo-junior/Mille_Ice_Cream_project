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
    Schema::table('produit', function (Blueprint $table) {
        $table->decimal('prix', 10, 2)->default(0)->after('description');
        $table->boolean('is_favori')->default(false)->after('prix');
        $table->integer('ordre')->default(1)->after('is_favori');
    });
}

public function down()
{
    Schema::table('produits', function (Blueprint $table) {
        $table->dropColumn(['prix', 'is_favori', 'ordre']);
    });
}

};
