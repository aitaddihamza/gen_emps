<?php

// app/Models/Seance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'salle_id',
        'creneau_id',
        'jour_id',
        'classe_id',
        'professeur_id',
        'semaine_debut',
        'semaine_fin',
    ];

    // Relations
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function creneau()
    {
        return $this->belongsTo(Creneau::class);
    }

    public function jour()
    {
        return $this->belongsTo(Jour::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function professeur()
    {
        return $this->belongsTo(Professeur::class);
    }
}
