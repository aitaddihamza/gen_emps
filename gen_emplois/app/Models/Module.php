<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Professeur;
use App\Models\Classe;

class Module extends Model
{
    protected $fillable = ['nom', 'volume_horaire', 'tp_seances'];

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'classe_module');
    }

    public function professeurs()
    {
        return $this->belongsToMany(Professeur::class, 'module_professeur');
    }
}
