<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Classe extends Model
{
    use HasFactory;
    use Notifiable;
    protected $fillable = ['nom', 'effectif'];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'classe_module');
    }
}
