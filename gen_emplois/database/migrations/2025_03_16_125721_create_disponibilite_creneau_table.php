<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisponibiliteCreneauTable extends Migration
{
    public function up()
    {
        Schema::create('disponibilite_creneau', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disponibilite_id')->constrained()->onDelete('cascade');
            $table->foreignId('creneau_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('disponibilite_creneau');
    }
}
