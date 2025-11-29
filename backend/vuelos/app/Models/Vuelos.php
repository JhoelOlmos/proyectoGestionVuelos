<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vuelos extends Model
{
    protected $table = 'flights';

    protected $fillable = [
        'nave_id',
        'origin',
        'destination',
        'departure',
        'arrival',
        'price'
    ];
    public function reservations()
{
    return $this->hasMany(\App\Models\Reservation::class, 'flight_id');
}

}

