<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Professeur extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table  = "professeurs";
    protected $fillable = ['nom', 'type_prof', 'max_heures', 'disponibilites'];
    protected $casts = [
        'disponibilites' => 'array', // Cast la colonne JSON en array
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_professeur');
    }

}
