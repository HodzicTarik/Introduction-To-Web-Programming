<?php
require_once __DIR__ . '/../services/ReservationService.php';

Flight::route('GET /api/reservations', function() {
    $service = new ReservationService();
    Flight::json($service->getAllReservations());
});

Flight::route('GET /api/reservations/@id', function($id) {
    $service = new ReservationService();
    Flight::json($service->getReservationById($id));
});

Flight::route('POST /api/reservations', function() {
    $data = Flight::request()->data->getData();
    $service = new ReservationService();
    Flight::json($service->createReservation($data));
});

Flight::route('PUT /api/reservations/@id', function($id) {
    $data = Flight::request()->data->getData();
    $service = new ReservationService();
    Flight::json($service->updateReservation($id, $data));
});

Flight::route('DELETE /api/reservations/@id', function($id) {
    $service = new ReservationService();
    Flight::json($service->deleteReservation($id));
});
