<?php

namespace App\Repositories;

use App\Models\Nave;

class NavesRepositorios
{
    public function getAll()
    {
        return Nave::all();
    }

    public function create($data)
    {
        return Nave::create($data);
    }

    public function update($id, $data)
    {
        $nave = Nave::find($id);

        if (!$nave) {
            return null;
        }

        $nave->update($data);
        return $nave;
    }

    public function delete($id)
    {
        $nave = Nave::find($id);

        if (!$nave) {
            return null;
        }

        // Validar si tiene vuelos asociados
        if ($nave->vuelos()->count() > 0) {
            return ['error' => 'No se puede eliminar una nave con vuelos asociados'];
        }

        $nave->delete();
        return ['message' => 'Nave eliminada'];
    }
}
