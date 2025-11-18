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
    Schema::table('categorie', function (Blueprint $table) {
        $table->integer('ordre')->default(1)->after('couleur');
        $table->string('icone')->nullable()->after('ordre');
    });
}

public function down()
{
    Schema::table('categorie', function (Blueprint $table) {
        $table->dropColumn(['ordre', 'icone']);
    });
}

};
