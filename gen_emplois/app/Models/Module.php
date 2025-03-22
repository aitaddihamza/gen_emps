<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Professeur;
use App\Models\Classe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Module extends Model
{
    use HasFactory;
    use Notifiable;
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
