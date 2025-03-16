<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Disponibilite;

class Jour extends Model
{
    protected $fillable = ['nom'];

    public function disponibilites()
    {
        return $this->hasMany(Disponibilite::class);
    }
}
