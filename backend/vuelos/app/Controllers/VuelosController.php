<?php

namespace App\Controllers;

use App\Repositories\VuelosRepositorios;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class VuelosController
{
    private $repo;

    public function __construct()
    {
        $this->repo = new VuelosRepositorios();
    }

    private function json(Response $response, $data, int $status = 200)
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($status);
    }

    // 2.1 Registrar vuelo
    public function create(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $vuelo = $this->repo->create($data);
        return $this->json($response, $vuelo, 201);
    }

    // 2.2 Consultar todos los vuelos
    public function index(Request $request, Response $response)
    {
        return $this->json($response, $this->repo->getAll());
    }

    // 2.3 Buscar vuelos
    public function search(Request $request, Response $response)
    {
        $filters = $request->getQueryParams();
        $result = $this->repo->search($filters);
        return $this->json($response, $result);
    }

    // 2.4 Actualizar vuelo
    public function update(Request $request, Response $response, array $args)
    {
        $id = (int)$args['id'];
        $data = $request->getParsedBody();

        $vuelo = $this->repo->update($id, $data);

        if (!$vuelo) {
            return $this->json($response, ['error' => 'Vuelo no encontrado'], 404);
        }

        return $this->json($response, ['message' => 'Vuelo actualizado']);
    }

    // 2.5 Eliminar vuelo

   public function delete(Request $request, Response $response, $args)
{
    $repo = new VuelosRepositorios();
    $result = $repo->delete($args['id']);

    $response->getBody()->write(json_encode($result));

    if (isset($result['error'])) {
        return $response->withStatus(400)
                        ->withHeader('Content-Type', 'application/json');
    }

    return $response->withHeader('Content-Type', 'application/json');
}


}
