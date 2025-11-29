<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nave extends Model
{
    protected $table = 'naves';

    protected $fillable = [
        'name',
        'capacity',
        'model'
    ];

    // Relación con vuelos
    public function vuelos()
    {
        return $this->hasMany(Vuelos::class, 'nave_id');
    }
}
