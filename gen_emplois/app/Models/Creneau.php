<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Disponibilite;

class Creneau extends Model
{
    protected $fillable = ['horaire'];

    public function disponibilites()
    {
        return $this->belongsToMany(Disponibilite::class, 'disponibilite_creneau');
    }
}
