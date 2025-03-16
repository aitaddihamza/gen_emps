<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Professeur;
use App\Models\Creneau;
use App\Models\Jour;

class Disponibilite extends Model
{
    protected $fillable = ['professeur_id', 'jour_id'];

    public function professeur()
    {
        return $this->belongsTo(Professeur::class);
    }

    public function jour()
    {
        return $this->belongsTo(Jour::class);
    }

    public function creneaux()
    {
        return $this->belongsToMany(Creneau::class, 'disponibilite_creneau');
    }
}
