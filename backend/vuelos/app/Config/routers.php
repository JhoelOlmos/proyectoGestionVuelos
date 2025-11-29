<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\VuelosController;
use App\Controllers\ReservationsController;

return function ($app) {

    // ============================
    // Rutas de Vuelos
    // ============================
    $app->group('/vuelos', function ($group) {
        $group->get('/all', [VuelosController::class, 'index']);
        $group->post('/create', [VuelosController::class, 'create']);
        $group->put('/update/{id}', [VuelosController::class, 'update']);
        $group->delete('/delete/{id}', [VuelosController::class, 'delete']);
    });

    // ============================
    // Rutas de Reservas
    // ============================
    $app->group('/reservas', function ($group) {

        // Crear una reserva
        $group->post('/crear', [ReservationsController::class, 'create']);

        // Reservas del usuario autenticado
        $group->get('/mias', [ReservationsController::class, 'mias']);

        // Cancelar reserva
        $group->delete('/cancelar/{id}', [ReservationsController::class, 'cancel']);
    });
    $app->group('/naves', function ($group) {
    $group->get('/all', 'App\Controllers\NavesController:all');
    $group->post('/create', 'App\Controllers\NavesController:create');
    $group->put('/update/{id}', 'App\Controllers\NavesController:update');
    $group->delete('/delete/{id}', 'App\Controllers\NavesController:delete');
});


};








