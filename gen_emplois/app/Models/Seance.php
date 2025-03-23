<?php

// app/Models/Seance.php

namespace App\Models;

use App\Models\Salle;
use App\Models\Classe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'module_nom',
        'salle',
        'creneau',
        'jour',
        'classe_id',
        'professeur_nom',
        'semaine_debut',
        'semaine_fin',
    ];



    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

}
