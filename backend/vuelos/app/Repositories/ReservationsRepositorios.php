<?php

namespace App\Repositories;

use App\Models\Reservation;
use App\Models\Vuelos;
use App\Models\Usuarios;

class ReservationsRepositorios
{
    public function create($data)
    {
        // Verificar si el vuelo existe
        $vuelo = Vuelos::find($data['flight_id']);

        if (!$vuelo) {
            return ['error' => 'El vuelo no existe'];
        }

        return Reservation::create([
            'user_id'     => $data['user_id'],
            'flight_id'   => $data['flight_id'],
            'status'      => 'activa',
            'reserved_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getAll()
    {
        return Reservation::with('vuelo')->get();
    }

    public function getByUser($id)
    {
        return Reservation::where('user_id', $id)
            ->with('vuelo')
            ->get();
    }

    public function cancel($id)
    {
        $reserva = Reservation::find($id);

        if (!$reserva) return null;

        $reserva->status = 'cancelada';
        $reserva->save();

        return $reserva;
    }

    public function getUserByToken($token)
    {
        if (!$token) return null;

        return Usuarios::where('token', $token)->first();
    }
}

