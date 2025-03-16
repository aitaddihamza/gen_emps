<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classe;
use App\Models\Module;
use App\Models\Creneau;
use App\Models\Jour;
use App\Models\Salle;

class Seance extends Model
{
    protected $fillable = ['module_id', 'salle_id', 'creneau_id', 'jour_id', 'classe_id'];

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
}
