<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Disponibilite;
use App\Models\Module;

class Professeur extends Model
{
    protected $fillable = ['nom', 'type_prof', 'max_heures', 'disponibilites'];
    protected $casts = [
        'disponibilites' => 'array', // Cast la colonne JSON en array
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_professeur');
    }

    public function disponibilites()
    {
        return $this->hasMany(Disponibilite::class);
    }
}
