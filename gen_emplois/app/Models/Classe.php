<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Module;

class Classe extends Model
{
    protected $fillable = ['nom', 'effectif'];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'classe_module');
    }
}
