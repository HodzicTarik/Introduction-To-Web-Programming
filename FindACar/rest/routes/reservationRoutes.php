<?php
require_once __DIR__ . '/../services/ReservationService.php';

Flight::route('GET /reservations', function() {
    Flight::auth_middleware()->authorizeAnyRole([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::reservation_service()->getAllReservations());
});

Flight::route('GET /reservations/@id', function($id) {
    Flight::auth_middleware()->authorizeAnyRole([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::reservation_service()->getReservationById($id));
});

Flight::route('POST /reservations', function() {
    Flight::auth_middleware()->authorizeAnyRole([Roles::USER, Roles::ADMIN]);

    $data = Flight::request()->data->getData();
    $user = Flight::get('user');

    // ✅ dodaj user_id u rezervaciju iz tokena
    $data['user_id'] = $user->id;

    Flight::json(Flight::reservation_service()->createReservation($data));
});


Flight::route('PUT /reservations/@id', function($id) {
    Flight::auth_middleware()->authorizeAnyRole([Roles::USER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reservation_service()->updateReservation($id, $data));
});

Flight::route('DELETE /reservations/@id', function($id) {
    Flight::auth_middleware()->authorizeAnyRole([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::reservation_service()->deleteReservation($id));
});
