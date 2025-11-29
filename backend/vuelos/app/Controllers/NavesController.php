<?php

namespace App\Controllers;

use App\Repositories\NavesRepositorios;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class NavesController
{
    private $repo;

    public function __construct()
    {
        $this->repo = new NavesRepositorios();
    }

    private function json(Response $response, $data, $status = 200)
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($status);
    }

    public function all(Request $request, Response $response)
    {
        return $this->json($response, $this->repo->getAll());
    }

    public function create(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $nave = $this->repo->create($data);
        return $this->json($response, $nave, 201);
    }

    public function update(Request $request, Response $response, $args)
    {
        $nave = $this->repo->update($args['id'], $request->getParsedBody());

        if (!$nave) {
            return $this->json($response, ['error' => 'Nave no encontrada'], 404);
        }

        return $this->json($response, ['message' => 'Nave actualizada']);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $respuesta = $this->repo->delete($args['id']);

        if (isset($respuesta['error'])) {
            return $this->json($response, $respuesta, 400);
        }

        return $this->json($response, $respuesta);
    }
}
