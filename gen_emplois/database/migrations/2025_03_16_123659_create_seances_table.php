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
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('salle_id')->constrained()->onDelete('cascade');
            $table->foreignId('creneau_id')->constrained()->onDelete('cascade');
            $table->foreignId('jour_id')->constrained()->onDelete('cascade');
            $table->foreignId('classe_id')->constrained()->onDelete('cascade'); // Classe associée
            $table->foreignId('professeur_id')->constrained('professeurs')->onDelete('cascade'); // Professeur associé
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
