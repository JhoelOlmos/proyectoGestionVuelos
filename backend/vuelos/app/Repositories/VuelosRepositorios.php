<?php

namespace App\Repositories;

use App\Models\Vuelos;

class VuelosRepositorios
{
    public function getAll()
    {
        return Vuelos::all();
    }

    public function find($id)
    {
        return Vuelos::find($id);
    }

    public function search($filters)
    {
        $query = Vuelos::query();

        if (!empty($filters['origin'])) {
            $query->where('origin', 'LIKE', "%{$filters['origin']}%");
        }

        if (!empty($filters['destination'])) {
            $query->where('destination', 'LIKE', "%{$filters['destination']}%");
        }

        if (!empty($filters['date'])) {
            $query->whereDate('departure', $filters['date']);
        }

        return $query->get();
    }

    public function create(array $data)
    {
        return Vuelos::create($data);
    }

    public function update($id, array $data)
    {
        $vuelo = Vuelos::find($id);
        if ($vuelo) {
            $vuelo->update($data);
        }
        return $vuelo;
    }
    public function delete($id)
{
    $vuelo = Vuelos::find($id);

    if (!$vuelo) return null;

    if ($vuelo->reservations()->where('status', 'activa')->count() > 0) {
        return ['error' => 'No se puede eliminar un vuelo con reservas activas'];
    }

    $vuelo->delete();
    return ['message' => 'Vuelo eliminado'];
}


}
