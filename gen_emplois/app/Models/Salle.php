<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Seance;

class Salle extends Model
{
    protected $fillable = ['nom', 'capacite', 'type_salle'];

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }
}
