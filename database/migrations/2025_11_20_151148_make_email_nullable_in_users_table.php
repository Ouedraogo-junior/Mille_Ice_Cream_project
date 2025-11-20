<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // On supprime l'ancien index unique sur email
            $table->dropUnique(['email']);

            // On rend la colonne nullable
            $table->string('email')->nullable()->change();

            // On recrée l'index unique (mais qui accepte les null)
            $table->unique(['email']);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->string('email')->nullable(false)->change();
            $table->unique('email');
        });
    }
};