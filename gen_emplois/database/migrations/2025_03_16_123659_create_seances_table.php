<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeancesTable extends Migration
{
    public function up()
    {
        Schema::create('seances', function (Blueprint $table) {
            $table->id();
            $table->string('module_nom'); // Store module name instead of module_id
            $table->string('salle');
            $table->string('creneau');
            $table->string('jour');
            $table->string('classe');
            // $table->foreignId('classe_id')->constrained()->onDelete('cascade'); // Classe associée
            $table->string('professeur_nom'); // Store professor name instead of professeur_id
            $table->integer('semaine_debut'); // Semaine de début
            $table->integer('semaine_fin');   // Semaine de fin
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seances');
    }
}
