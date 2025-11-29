<?php

namespace App\Controllers;

use App\Repositories\ReservationsRepositorios;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReservationsController
{
    private $repo;

    public function __construct()
    {
        $this->repo = new ReservationsRepositorios();
    }

    private function json(Response $response, $data, $status = 200)
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($status);
    }

    public function create(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        $reserva = $this->repo->create($data);

        if (isset($reserva['error'])) {
            return $this->json($response, $reserva, 400);
        }

        return $this->json($response, $reserva, 201);
    }

    public function all(Request $request, Response $response)
    {
        return $this->json($response, $this->repo->getAll());
    }

    public function byUser(Request $request, Response $response, $args)
    {
        return $this->json($response, $this->repo->getByUser($args['id']));
    }

    public function cancel(Request $request, Response $response, $args)
    {
        $reserva = $this->repo->cancel($args['id']);

        if (!$reserva) {
            return $this->json($response, ['error' => 'Reserva no encontrada'], 404);
        }

        return $this->json($response, ['message' => 'Reserva cancelada']);
    }
    public function mine(Request $request, Response $response)
{
    // Obtener token del header
    $token = $request->getHeaderLine('Authorization');
    $token = str_replace('Bearer ', '', $token);

    if (!$token) {
        return $this->json($response, ['error' => 'Falta token'], 401);
    }

    // Obtener user_id desde el repositorio
    $user = $this->repo->getUserByToken($token);

    if (!$user) {
        return $this->json($response, ['error' => 'Token inválido'], 401);
    }

    // Obtener reservas del usuario
    $reservas = $this->repo->getByUser($user->id);

    return $this->json($response, $reservas);
}
public function mias(Request $request, Response $response)
{
    $token = $request->getHeaderLine('Authorization');
    $token = str_replace('Bearer ', '', $token);

    $user = $this->repo->getUserByToken($token);

    if (!$user) {
        return $this->json($response, ['error' => 'Token inválido'], 401);
    }

    $reservas = $this->repo->getByUser($user->id);

    return $this->json($response, $reservas);
}


  

}
